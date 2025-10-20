<?php

namespace App\Services;

use App\Models\Invoice;

class GstService
{
    public function monthlySummary(int $userId, string $period): array
    {
        $base = Invoice::with('items')->where('user_id',$userId)->period($period);

        $sales = (clone $base)->type('sales')->get();
        $purch = (clone $base)->type('purchase')->get();

        $salesTax = $sales->sum(fn($i)=> (float)$i->cgst + (float)$i->sgst + (float)$i->igst);
        $itc      = $purch->sum(fn($i)=> (float)$i->cgst + (float)$i->sgst + (float)$i->igst);
        $payable  = max(0, $salesTax - $itc);

        // Split buckets
        $sales_std_taxable = 0; $sales_zero = 0; $sales_nil_exempt = 0;
        $sales_cgst_std = 0; $sales_sgst_std = 0; $sales_igst_std = 0;

        foreach ($sales as $inv) {
            foreach ($inv->items as $it) {
                if ($it->is_non_gst || $it->is_nil || $it->is_exempt) {
                    $sales_nil_exempt += (float)$it->taxable_value;
                } else {
                    // treat as standard rated or zero rated (simple assumption here)
                    if (($inv->is_export ?? false) || ($it->igst_rate == 0 && $it->cgst_rate == 0 && $it->sgst_rate == 0)) {
                        $sales_zero += (float)$it->taxable_value;
                    } else {
                        $sales_std_taxable += (float)$it->taxable_value;
                        $sales_cgst_std += (float)$it->cgst;
                        $sales_sgst_std += (float)$it->sgst;
                        $sales_igst_std += (float)$it->igst;
                    }
                }
            }
        }

        $rcm_inward_tax = $purch->where('is_rcm', true)->sum(fn($i)=> (float)$i->cgst + (float)$i->sgst + (float)$i->igst);

        return [
            'period' => $period,
            'sales_count' => $sales->count(),
            'purchase_count' => $purch->count(),
            'sales_tax' => round($salesTax,2),
            'itc' => round($itc,2),
            'payable' => round($payable,2),
            'rcm_inward_tax' => round($rcm_inward_tax,2),
            'buckets' => [
                'sales' => [
                    'taxable' => round($sales_std_taxable,2),
                    'zero_rated' => round($sales_zero,2),
                    'nil_exempt' => round($sales_nil_exempt,2),
                    'cgst_std' => round($sales_cgst_std,2),
                    'sgst_std' => round($sales_sgst_std,2),
                    'igst_std' => round($sales_igst_std,2),
                ],
                'purchase' => [
                    'cgst' => round($purch->sum('cgst'),2),
                    'sgst' => round($purch->sum('sgst'),2),
                    'igst' => round($purch->sum('igst'),2),
                    'taxable' => round($purch->sum('taxable_amount'),2),
                ],
            ],
        ];
    }

    public function gstr3bPayload(array $summary): array
    {
        return [
            'return_period' => $summary['period'],
            'table_3_1' => [
                'a_outward_taxable' => [
                    'taxable_value' => $summary['buckets']['sales']['taxable'] ?? 0,
                    'cgst' => $summary['buckets']['sales']['cgst_std'] ?? 0,
                    'sgst' => $summary['buckets']['sales']['sgst_std'] ?? 0,
                    'igst' => $summary['buckets']['sales']['igst_std'] ?? 0,
                ],
                'b_zero_rated' => $summary['buckets']['sales']['zero_rated'] ?? 0,
                'c_nil_exempt' => $summary['buckets']['sales']['nil_exempt'] ?? 0,
                'd_rcm_inward' => $summary['rcm_inward_tax'] ?? 0,
            ],
            'table_4' => [
                'itc_available' => [
                    'inward' => $summary['itc'] ?? 0,
                ],
                'itc_reversed' => [
                    'rule_42_43' => 0,
                    'others' => 0,
                ],
            ],
            'net_payable' => $summary['payable'],
        ];
    }
}
