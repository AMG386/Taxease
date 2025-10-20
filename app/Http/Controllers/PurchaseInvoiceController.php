<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use Illuminate\Http\Request;

class PurchaseInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $q = PurchaseInvoice::query()
            ->when($request->filled('from'), fn($x) => $x->whereDate('invoice_date', '>=', $request->date('from')))
            ->when($request->filled('to'),   fn($x) => $x->whereDate('invoice_date', '<=', $request->date('to')))
            ->orderByDesc('invoice_date');

        $invoices = $q->paginate(15)->withQueryString();

        return view('purchase_invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('purchase_invoices.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_no'      => 'required|string|max:50|unique:purchase_invoices,invoice_no',
            'invoice_date'    => 'required|date',
            'supplier_name'   => 'nullable|string|max:200',
            'supplier_gstin'  => 'nullable|string|max:20',
            'hsn'             => 'nullable|string|max:20',
            'qty'             => 'required|integer|min:1',
            'uom'             => 'nullable|string|max:20',
            'taxable_value'   => 'required|numeric|min:0',
            'tax_rate'        => 'required|numeric|min:0',
            'place_of_supply' => 'nullable|string|max:100',
            'origin_state'    => 'required|string|max:100',
        ]);

        // GST breakup:
        [$cgst, $sgst, $igst] = $this->computeGst(
            $data['taxable_value'],
            $data['tax_rate'],
            $data['origin_state'] ?? null,
            $data['place_of_supply'] ?? null
        );

        $data['cgst_amount'] = $cgst;
        $data['sgst_amount'] = $sgst;
        $data['igst_amount'] = $igst;

        PurchaseInvoice::create($data);

        return redirect()->route('purchases.invoices.index')->with('status', 'Purchase bill added.');
    }

    private function computeGst($taxable, $rate, $origin, $pos): array
    {
        $tax = round($taxable * ($rate / 100), 2);
        if ($origin && $pos && strcasecmp(trim($origin), trim($pos)) !== 0) {
            // Inter-state → IGST
            return [0, 0, $tax];
        }
        // Intra-state → CGST + SGST split
        $half = round($tax / 2, 2);
        return [$half, $half, 0];
    }
}
