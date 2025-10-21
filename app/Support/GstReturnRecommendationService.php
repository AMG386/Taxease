<?php

namespace App\Support;

use App\Models\GstProfile;

class GstReturnRecommendationService
{
    /**
     * Get recommended returns for a user based on their GST profile
     */
    public static function getRecommendedReturns(?int $userId = null): array
    {
        $userId = $userId ?? auth()->id();
        $profile = GstProfile::where('user_id', $userId)->first();

        if (!$profile) {
            return [
                'error' => 'No GST profile found. Please complete GST settings first.',
                'returns' => []
            ];
        }

        $gstType = $profile->gst_type ?? 'regular';
        $frequency = $profile->filing_frequency ?? 'monthly';

        if ($gstType === 'composition') {
            return [
                'taxpayer_type' => 'composition',
                'returns' => [
                    'cmp08' => [
                        'name' => 'CMP-08',
                        'description' => 'Quarterly Composition Scheme Return',
                        'frequency' => 'quarterly',
                        'endpoint' => '/gst/returns/cmp08',
                        'due_dates' => self::getQuarterlyDueDates()
                    ],
                    'gstr4' => [
                        'name' => 'GSTR-4',
                        'description' => 'Annual Composition Scheme Return',
                        'frequency' => 'annual',
                        'endpoint' => null, // Not implemented yet
                        'due_date' => 'April 30th'
                    ]
                ]
            ];
        }

        // Regular taxpayers
        $returns = [
            'gstr3b' => [
                'name' => 'GSTR-3B',
                'description' => 'Monthly Self Assessment Return',
                'frequency' => 'monthly',
                'endpoint' => '/gst/returns/gstr3b',
                'due_date' => '20th of following month'
            ]
        ];

        if ($frequency === 'qrmp') {
            $returns['gstr1'] = [
                'name' => 'GSTR-1',
                'description' => 'Quarterly Outward Supplies Return (QRMP)',
                'frequency' => 'quarterly',
                'endpoint' => '/gst/returns/gstr1',
                'due_dates' => self::getQuarterlyDueDates()
            ];
        } else {
            $returns['gstr1'] = [
                'name' => 'GSTR-1',
                'description' => 'Monthly Outward Supplies Return',
                'frequency' => 'monthly',
                'endpoint' => '/gst/returns/gstr1',
                'due_date' => '11th of following month'
            ];
        }

        return [
            'taxpayer_type' => 'regular',
            'filing_frequency' => $frequency,
            'returns' => $returns
        ];
    }

    /**
     * Check if user should file composition returns
     */
    public static function isCompositionTaxpayer(?int $userId = null): bool
    {
        $userId = $userId ?? auth()->id();
        $profile = GstProfile::where('user_id', $userId)->first();
        
        return $profile && $profile->gst_type === 'composition';
    }

    /**
     * Get quarterly due dates for current financial year
     */
    private static function getQuarterlyDueDates(): array
    {
        return [
            'Q1' => 'July 18th (Apr-Jun)',
            'Q2' => 'October 18th (Jul-Sep)', 
            'Q3' => 'January 18th (Oct-Dec)',
            'Q4' => 'April 18th (Jan-Mar)'
        ];
    }

    /**
     * Get current quarter for composition returns
     */
    public static function getCurrentQuarter(): string
    {
        $month = now()->month;
        $year = now()->year;
        
        // Financial year quarters
        if ($month >= 4 && $month <= 6) return $year . 'Q1';
        if ($month >= 7 && $month <= 9) return $year . 'Q2';
        if ($month >= 10 && $month <= 12) return $year . 'Q3';
        
        // Jan-Mar is Q4 of previous financial year
        return ($year - 1) . 'Q4';
    }
}