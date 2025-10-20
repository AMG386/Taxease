<?php

namespace App\Services;

class Gst3BMapper
{
    public function map(array $summary): array
    {
        return [
            'period' => $summary['period'],
            'table_3_1' => [
                'a_taxable_value' => $summary['buckets']['sales']['taxable'] ?? 0,
                'a_tax' => [
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
