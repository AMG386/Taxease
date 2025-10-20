<?php

namespace App\Http\Controllers;

use App\Models\GstReturn;
use App\Models\GstReturnItem;
use App\Models\GstCompositionTurnover;
use App\Models\GstAuditFile;
use App\Support\Tax\GstMath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;

use Illuminate\Support\Str;
use Carbon\Carbon;

class GstReturnController extends Controller
{
    public function index(Request $req)
    {
        $q = GstReturn::query()
            ->where('user_id', auth()->id())
            ->when($req->type, fn($qq) => $qq->where('type', $req->type))
            ->when($req->status, fn($qq) => $qq->where('status', $req->status))
            ->orderByDesc('period_to');

        $returns = $q->paginate(20)->withQueryString();

        return view('gst.returns.index', compact('returns'));
    }

    public function create()
    {
        // default monthly period (last month)
        $from = now('Asia/Kolkata')->startOfMonth()->subMonth()->toDateString();
        $to   = now('Asia/Kolkata')->subMonthNoOverflow()->endOfMonth()->toDateString();

        return view('gst.returns.create', [
            'defaults' => [
                'type' => 'gstr3b',
                'period_from' => $from,
                'period_to' => $to,
                'frequency' => 'monthly',
            ]
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'type'        => 'required|string|in:gstr1,gstr3b,gstr4,gstr9,gstr9a,gstr9c,cmp08',
            'period_from' => 'required|date',
            'period_to'   => 'required|date|after_or_equal:period_from',
            'meta'        => 'nullable|array',
        ]);

        $data['user_id'] = auth()->id();
        $gst = GstReturn::create($data);

    return redirect()->route('gst.returns.show', $gst->id)
        ->with('ok', 'GST return created. Next, prepare data.');
}

public function show(GstReturn $gstReturn)
{
    $this->authorizeView($gstReturn);

    // Paginated items so Blade {{ $items->links() }} works
    $items = $gstReturn->items()
        ->orderBy('section')
        ->orderBy('invoice_date')
        ->paginate(25)
        ->withQueryString();

    // audits: cheap existence via loadCount + fetch list
    $gstReturn->loadCount('audits');
    $audits = $gstReturn->audits()->get(); // relation can already be latest()

    return view('gst.returns.show', compact('gstReturn', 'items', 'audits'));
}
    /**
     * Prepare a GST return by (re)building its items from sales & purchase invoices.
     * Route example: POST /gst/returns/{gstReturn}/prepare  named: gst.returns.prepare
     */
  public function prepare(\App\Models\GstReturn $gstReturn)
{
    $this->authorizeView($gstReturn);
    abort_if($gstReturn->status !== 'draft', 422, 'Only draft can be prepared.');

    // Use DATE boundaries to avoid time edge-cases
    $from = \Carbon\Carbon::parse($gstReturn->period_from)->toDateString();
    $to   = \Carbon\Carbon::parse($gstReturn->period_to)->toDateString();

    // Fixed destination columns (and their order) for gst_return_items
    $COLUMNS = [
        'cess','cgst','counterparty_gstin','gst_return_id','hsn','igst','invoice_date',
        'invoice_no','party_name','qty','raw','section','sgst','taxable_value','total','uom',
    ];

    \DB::beginTransaction();
    try {
        // Wipe existing rows for this return
        \App\Models\GstReturnItem::where('gst_return_id', $gstReturn->id)->delete();

        $items_norm = [];

        /**
         * 1) SALES → B2B
         * NOTE: no hard dependency on cess_amount; we fallback to 0 if column doesn't exist
         */
        $sales = \App\Models\SalesInvoice::query()
            ->whereDate('invoice_date', '>=', $from)
            ->whereDate('invoice_date', '<=', $to)
            ->get([
                'id','invoice_no','invoice_date','customer_gstin','customer_name',
                'hsn','qty','uom',
                \DB::raw('COALESCE(taxable_value, 0) AS taxable_value'),
                \DB::raw('COALESCE(tax_rate, 0) AS tax_rate'),
                \DB::raw('COALESCE(cgst_amount, 0) AS cgst_amount'),
                \DB::raw('COALESCE(sgst_amount, 0) AS sgst_amount'),
                \DB::raw('COALESCE(igst_amount, 0) AS igst_amount'),
                \DB::raw('0 AS cess_amount'), // fallback
                'place_of_supply','origin_state',
            ]);

        foreach ($sales as $s) {
            $taxable = self::n($s->taxable_value);
            $cgst    = self::n($s->cgst_amount);
            $sgst    = self::n($s->sgst_amount);
            $igst    = self::n($s->igst_amount);
            $cess    = self::n($s->cess_amount); // 0 via fallback
            $total   = $taxable + $cgst + $sgst + $igst + $cess;

            $raw = [
                'id'              => $s->id,
                'invoice_no'      => $s->invoice_no,
                'invoice_date'    => optional($s->invoice_date)->toDateString() ?? (string)$s->invoice_date,
                'customer_gstin'  => $s->customer_gstin,
                'customer_name'   => $s->customer_name,
                'hsn'             => $s->hsn,
                'qty'             => $s->qty,
                'uom'             => $s->uom,
                'taxable_value'   => (string)$s->taxable_value,
                'tax_rate'        => (string)$s->tax_rate,
                'cgst_amount'     => $s->cgst_amount,
                'sgst_amount'     => $s->sgst_amount,
                'igst_amount'     => $s->igst_amount,
                'cess_amount'     => $s->cess_amount, // 0 via fallback
                'place_of_supply' => $s->place_of_supply,
                'origin_state'    => $s->origin_state,
            ];

            $row = [
                'cess'               => $cess,
                'cgst'               => $cgst,
                'counterparty_gstin' => (string)($s->customer_gstin ?? ''),
                'gst_return_id'      => (int)$gstReturn->id,
                'hsn'                => (string)($s->hsn ?? ''),
                'igst'               => $igst,
                'invoice_date'       => \Carbon\Carbon::parse($s->invoice_date)->toDateString(),
                'invoice_no'         => (string)($s->invoice_no ?? ''),
                'party_name'         => (string)($s->customer_name ?? ''),
                'qty'                => self::n($s->qty),
                'raw'                => json_encode($raw, JSON_UNESCAPED_UNICODE),
                'section'            => 'B2B', // adjust if you classify B2C/EXP/etc.
                'sgst'               => $sgst,
                'taxable_value'      => $taxable,
                'total'              => $total,
                'uom'                => (string)($s->uom ?? ''),
            ];

            $items_norm[] = array_merge(array_fill_keys($COLUMNS, null), array_intersect_key($row, array_flip($COLUMNS)));
        }

        /**
         * 2) PURCHASES → ITC
         */
        $purchases = \App\Models\PurchaseInvoice::query()
            ->whereDate('invoice_date', '>=', $from)
            ->whereDate('invoice_date', '<=', $to)
            ->get([
                'id','invoice_no','invoice_date','supplier_gstin','supplier_name',
                'hsn','qty','uom',
                \DB::raw('COALESCE(taxable_value, 0) AS taxable_value'),
                \DB::raw('COALESCE(tax_rate, 0) AS tax_rate'),
                \DB::raw('COALESCE(cgst_amount, 0) AS cgst_amount'),
                \DB::raw('COALESCE(sgst_amount, 0) AS sgst_amount'),
                \DB::raw('COALESCE(igst_amount, 0) AS igst_amount'),
                \DB::raw('0 AS cess_amount'), // fallback
                'place_of_supply','origin_state',
            ]);

        foreach ($purchases as $p) {
            $taxable = self::n($p->taxable_value);
            $cgst    = self::n($p->cgst_amount);
            $sgst    = self::n($p->sgst_amount);
            $igst    = self::n($p->igst_amount);
            $cess    = self::n($p->cess_amount); // 0 via fallback
            $total   = $taxable + $cgst + $sgst + $igst + $cess;

            $raw = [
                'id'              => $p->id,
                'invoice_no'      => $p->invoice_no,
                'invoice_date'    => optional($p->invoice_date)->toDateString() ?? (string)$p->invoice_date,
                'supplier_gstin'  => $p->supplier_gstin,
                'supplier_name'   => $p->supplier_name,
                'hsn'             => $p->hsn,
                'qty'             => $p->qty,
                'uom'             => $p->uom,
                'taxable_value'   => (string)$p->taxable_value,
                'tax_rate'        => (string)$p->tax_rate,
                'cgst_amount'     => $p->cgst_amount,
                'sgst_amount'     => $p->sgst_amount,
                'igst_amount'     => $p->igst_amount,
                'cess_amount'     => $p->cess_amount, // 0 via fallback
                'place_of_supply' => $p->place_of_supply,
                'origin_state'    => $p->origin_state,
            ];

            $row = [
                'cess'               => $cess,
                'cgst'               => $cgst,
                'counterparty_gstin' => (string)($p->supplier_gstin ?? ''),
                'gst_return_id'      => (int)$gstReturn->id,
                'hsn'                => (string)($p->hsn ?? ''),
                'igst'               => $igst,
                'invoice_date'       => \Carbon\Carbon::parse($p->invoice_date)->toDateString(),
                'invoice_no'         => (string)($p->invoice_no ?? ''),
                'party_name'         => (string)($p->supplier_name ?? ''),
                'qty'                => self::n($p->qty),
                'raw'                => json_encode($raw, JSON_UNESCAPED_UNICODE),
                'section'            => 'ITC',
                'sgst'               => $sgst,
                'taxable_value'      => $taxable,
                'total'              => $total,
                'uom'                => (string)($p->uom ?? ''),
            ];

            $items_norm[] = array_merge(array_fill_keys($COLUMNS, null), array_intersect_key($row, array_flip($COLUMNS)));
        }

        // Bulk insert (keys consistent; JSON already encoded)
        if (!empty($items_norm)) {
            \App\Models\GstReturnItem::query()->insert($items_norm);
        }

        // Recompute aggregates and update parent
        $sums = $gstReturn->items()->selectRaw("
            COALESCE(SUM(taxable_value),0) as taxable_value,
            COALESCE(SUM(cgst),0) as cgst,
            COALESCE(SUM(sgst),0) as sgst,
            COALESCE(SUM(igst),0) as igst,
            COALESCE(SUM(cess),0) as cess
        ")->first();

        $totalOutTax = $gstReturn->items()->where('section', 'B2B')
            ->selectRaw("COALESCE(SUM(cgst+sgst+igst+cess),0) as tax")->value('tax') ?? 0;

        $totalITC = $gstReturn->items()->where('section', 'ITC')
            ->selectRaw("COALESCE(SUM(cgst+sgst+igst+cess),0) as itc")->value('itc') ?? 0;

        $gstReturn->update([
            'taxable_value' => (float)($sums->taxable_value ?? 0),
            'cgst'          => (float)($sums->cgst ?? 0),
            'sgst'          => (float)($sums->sgst ?? 0),
            'igst'          => (float)($sums->igst ?? 0),
            'cess'          => (float)($sums->cess ?? 0),
            'total_tax'     => (float)$totalOutTax,
            'itc_eligible'  => (float)$totalITC,
            'net_payable'   => max($totalOutTax - $totalITC, 0),
            'status'        => 'prepared',
            'prepared_on'   => now(),
        ]);

        \DB::commit();

        return redirect()
            ->route('gst.returns.show', $gstReturn)
            ->with('ok', 'GST return prepared successfully.');
    } catch (\Throwable $e) {
        \DB::rollBack();
        report($e);
        return back()->withErrors(['server' => $e->getMessage()]);
    }
}

/**
 * Safe numeric cast (accepts strings like "1,234.50").
 */
private static function n($v, $default = 0.0): float
{
    if (is_null($v) || $v === '') return (float)$default;
    if (is_string($v)) $v = str_replace([',', ' '], '', $v);
    return is_numeric($v) ? (float)$v : (float)$default;
}


    public function edit(GstReturn $gstReturn)
    {
        $this->authorizeView($gstReturn);
        return view('gst.returns.edit', compact('gstReturn'));
    }

    public function update(Request $r, GstReturn $gstReturn)
    {
        $this->authorizeView($gstReturn);
        $data = $r->validate([
            'meta'   => 'nullable|array',
            'status' => 'nullable|string|in:draft,prepared,filed,rejected,cancelled',
        ]);
        $gstReturn->update($data);

        return back()->with('ok', 'GST return updated.');
    }

    public function destroy(GstReturn $gstReturn)
    {
        $this->authorizeView($gstReturn);
        abort_unless($gstReturn->status === 'draft', 403);
        $gstReturn->delete();

        return back()->with('ok', 'Prepared successfully from transactions.');
    }

    public function exportJson(GstReturn $gstReturn)
    {
        $this->authorizeView($gstReturn);
        $payload = [
            'header' => $gstReturn->only([
                'id','type','period_from','period_to','status','taxable_value','cgst','sgst','igst','cess','total_tax','itc_eligible','net_payable'
            ]),
            'items'  => $gstReturn->items()->get()->toArray(),
        ];
        return response()->json($payload);
    }

    public function fileReturn(GstReturn $gstReturn, Request $r)
    {
        $this->authorizeView($gstReturn);
        abort_if($gstReturn->status !== 'prepared', 422, 'Prepare before filing.');
        // Optionally: call GSTN APIs here; for now mark as filed.
        $gstReturn->update(['status' => 'filed', 'filed_on' => now()]);
        return back()->with('ok', 'Return marked as FILED.');
    }

    public function uploadAudit(GstReturn $gstReturn, Request $r)
    {
        $this->authorizeView($gstReturn);
        $r->validate(['file' => 'required|file|max:20480', 'remarks' => 'nullable|string']);
        $path = $r->file('file')->store("gst/{$gstReturn->id}", 'public');

        GstAuditFile::create([
            'gst_return_id' => $gstReturn->id,
            'filename' => $r->file('file')->getClientOriginalName(),
            'path' => $path,
            'disk' => 'public',
            'remarks' => $r->remarks,
        ]);

        return back()->with('ok', 'Audit file uploaded.');
    }

    public function cmpDashboard(Request $r)
    {
        // Small composition summary view
        $period_from = $r->get('period_from', now()->startOfQuarter()->toDateString());
        $period_to   = $r->get('period_to', now()->endOfQuarter()->toDateString());

        $rec = GstCompositionTurnover::firstOrCreate([
            'user_id' => auth()->id(),
            'period_from' => $period_from,
            'period_to' => $period_to,
        ], [
            'total_turnover' => 0, 'tax_rate' => 1.0, 'tax_amount' => 0, 'status' => 'draft'
        ]);

        return view('gst.composition.dashboard', compact('rec'));
    }

    public function cmpUpdate(Request $r, GstCompositionTurnover $rec)
    {
        abort_unless($rec->user_id === auth()->id(), 403);

        $data = $r->validate([
            'total_turnover' => 'required|numeric|min:0',
            'tax_rate'       => 'required|numeric|min:0',
        ]);
        $data['tax_amount'] = round($data['total_turnover'] * $data['tax_rate'] / 100, 2);
        $rec->update($data);

        return back()->with('ok', 'Composition turnover updated.');
    }

    private function authorizeView(GstReturn $gstReturn): void
    {
        abort_unless($gstReturn->user_id === auth()->id(), 403);
    }
}
