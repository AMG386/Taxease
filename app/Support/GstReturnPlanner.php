<?php

namespace App\Support;

class GstReturnPlanner
{
    public static function plan(string $periodYm, array $profile): array
    {
        // $profile from your gst settings row
        $gstType = $profile['gst_type']; // 'regular' | 'composition'
        $freq    = $profile['filing_frequency'] ?? 'monthly'; // 'monthly'|'qrmp'|'cmp_quarterly'|'cmp_annual'

        if ($gstType === 'composition') {
            // Composition taxpayers:
            // - CMP-08 quarterly
            // - GSTR-4 annual
            return [
                'period'   => $periodYm,
                'returns'  => [
                    'cmp08' => in_array($freq, ['cmp_quarterly','composition','qrmp']) ? 'quarterly' : 'quarterly',
                    'gstr4' => 'annual',
                ],
            ];
        }

        // Regular taxpayers:
        // - Monthly: GSTR-1 + GSTR-3B monthly
        // - QRMP: GSTR-3B monthly payment + GSTR-1 quarterly (IFF optional, not enforced here)
        if ($freq === 'qrmp') {
            return [
                'period' => $periodYm,
                'returns' => [
                    'gstr3b' => 'monthly',
                    'gstr1'  => 'quarterly' // optionally IFF monthly for B2B; add when needed
                ],
            ];
        }

        return [
            'period' => $periodYm,
            'returns' => [
                'gstr3b' => 'monthly',
                'gstr1'  => 'monthly',
            ],
        ];
    }
}