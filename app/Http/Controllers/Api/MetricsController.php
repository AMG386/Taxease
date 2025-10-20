<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GstReturn;
use App\Models\GstReturnItem;
use App\Models\Income;
use App\Models\Expense;
use App\Services\ItrService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetricsController extends Controller
{
    /**
     * KPI tiles — computed from GST Return Items (not raw invoices).
     */
    public function metrics(Request $req)
    {
        $userId = auth()->id() ?? 1;

        // Current month window
        $from = now()->startOfMonth()->toDateString();
        $to   = now()->endOfMonth()->toDateString();

        // Join items with returns to filter by user_id
        $base = DB::table('gst_return_items as i')
            ->join('gst_returns as r', 'r.id', '=', 'i.gst_return_id')
            ->where('r.user_id', $userId)
            ->whereDate('i.invoice_date', '>=', $from)
            ->whereDate('i.invoice_date', '<=', $to);

        // Outward tax (use B2B; extend with more sections if needed)
        $salesTax = (clone $base)
            ->whereIn('i.section', ['B2B']) // add: 'B2C', 'EXP' as applicable
            ->sum(DB::raw('COALESCE(i.cgst,0)+COALESCE(i.sgst,0)+COALESCE(i.igst,0)+COALESCE(i.cess,0)'));

        // ITC (input tax credit)
        $itc = (clone $base)
            ->where('i.section', 'ITC')
            ->sum(DB::raw('COALESCE(i.cgst,0)+COALESCE(i.sgst,0)+COALESCE(i.igst,0)+COALESCE(i.cess,0)'));

        $payable = max(0, (float)$salesTax - (float)$itc);

        return response()->json([
            'gstPayable'        => round($payable, 2),
            'itcAvailable'      => round((float)$itc, 2),
            'salesTaxCollected' => round((float)$salesTax, 2),
            'itrProgress'       => 0, // keep or wire to your ITR workflow
            'nextDue'           => 'GSTR-3B by 20 ' . now()->addMonth()->format('M Y'),
        ]);
    }

    /**
     * GST Summary chart — last 7 months from GST Return Items.
     */
    public function gstSummary(Request $req)
    {
        $userId = auth()->id() ?? 1;

        $months = collect(range(6, 0))->map(fn($i) => now()->subMonths($i));
        $categories = $months->map(fn($d) => $d->format('M'))->values();

        $payables = [];
        $itcs     = [];

        foreach ($months as $d) {
            $from = $d->copy()->startOfMonth()->toDateString();
            $to   = $d->copy()->endOfMonth()->toDateString();

            $base = DB::table('gst_return_items as i')
                ->join('gst_returns as r', 'r.id', '=', 'i.gst_return_id')
                ->where('r.user_id', $userId)
                ->whereDate('i.invoice_date', '>=', $from)
                ->whereDate('i.invoice_date', '<=', $to);

            $salesTax = (clone $base)
                ->whereIn('i.section', ['B2B'])
                ->sum(DB::raw('COALESCE(i.cgst,0)+COALESCE(i.sgst,0)+COALESCE(i.igst,0)+COALESCE(i.cess,0)'));

            $itc = (clone $base)
                ->where('i.section', 'ITC')
                ->sum(DB::raw('COALESCE(i.cgst,0)+COALESCE(i.sgst,0)+COALESCE(i.igst,0)+COALESCE(i.cess,0)'));

            $payables[] = max(0, (float)$salesTax - (float)$itc);
            $itcs[]     = (float)$itc;
        }

        return response()->json([
            'categories' => $categories,
            'series' => [
                ['name' => 'GST Payable', 'data' => $payables],
                ['name' => 'ITC Used',    'data' => $itcs],
            ],
        ]);
    }

    /**
     * “Recent Invoices” pane — from GST Return Items (not raw invoices).
     * B2B shown as "sales", ITC as "purchase".
     */
    public function recentInvoices(Request $req)
    {
        $userId = auth()->id() ?? 1;

        $rows = DB::table('gst_return_items as i')
            ->join('gst_returns as r', 'r.id', '=', 'i.gst_return_id')
            ->where('r.user_id', $userId)
            ->orderByDesc('i.invoice_date')
            ->limit(10)
            ->get([
                'i.invoice_no',
                'i.invoice_date as date',
                'i.section',
                'i.total as total_amount',
                'i.counterparty_gstin as gstin',
            ])
            ->map(function ($row) {
                $type = ($row->section === 'ITC') ? 'purchase' : 'sales';
                return (object)[
                    'invoice_no'   => $row->invoice_no,
                    'date'         => $row->date,
                    'type'         => $type,
                    'total_amount' => (float)$row->total_amount,
                    'gstin'        => $row->gstin,
                ];
            })
            ->values();

        return response()->json(['data' => $rows]);
    }

    /**
     * ITR summary — unchanged (from your service)
     */
    public function itrSummary(Request $req, ItrService $svc)
    {
        $fy = $req->get('fy') ?: $this->currentFy();
        $userId = auth()->id() ?? 1;

        $pl = $svc->computePL($userId, $fy);
        return response()->json([
            'fy'            => $fy,
            'income_total'  => $pl['income_total'],
            'expense_total' => $pl['expense_total'],
            'profit'        => $pl['profit'],
        ]);
    }

    /**
     * Recent incomes & expenses — unchanged (dashboard side cards)
     */
    public function recentIncomeExpenses(Request $req)
    {
        $userId = auth()->id() ?? 1;
        $limit  = (int)($req->get('limit') ?: 5);

        $incomes = Income::where('user_id', $userId)
            ->orderByDesc('date')->limit($limit)
            ->get(['id','date','head','sub_head','ref_no','amount']);

        $expenses = Expense::where('user_id', $userId)
            ->orderByDesc('date')->limit($limit)
            ->get(['id','date', DB::raw('category as head'),'ref_no','amount','itc_claimed']);

        return response()->json([
            'incomes'  => $incomes,
            'expenses' => $expenses,
        ]);
    }

    private function currentFy(): string
    {
        // India FY: Apr 1 – Mar 31
        $today = Carbon::today();
        $fyStartYear = $today->month >= 4 ? $today->year : $today->year - 1;
        return $fyStartYear . '-' . substr($fyStartYear + 1, 2, 2);
    }
}
