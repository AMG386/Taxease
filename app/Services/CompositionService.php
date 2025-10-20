<?php
namespace App\Services;

use App\Models\GstProfile;
use App\Models\Sale; // your sales table model
use Carbon\Carbon;

class CompositionService
{
    public function quarterRange(string $fy, string $quarter): array
    {
        // FY format '2025-26'. Q1: Apr-Jun, Q2: Jul-Sep, Q3: Oct-Dec, Q4: Jan-Mar
        [$startYear] = explode('-', $fy);
        $y = (int)$startYear;
        $map = [
            'Q1' => [[$y,4,1], [$y,6,30]],
            'Q2' => [[$y,7,1], [$y,9,30]],
            'Q3' => [[$y,10,1],[$y,12,31]],
            'Q4' => [[$y+1,1,1],[$y+1,3,31]],
        ];
        [$s,$e] = $map[$quarter];
        return [Carbon::create(...$s)->startOfDay(), Carbon::create(...$e)->endOfDay()];
    }

    public function computeQuarter(string $fy, string $quarter, int $userId): array
    {
        $profile = GstProfile::where('user_id', $userId)->first();
        if (!$profile || $profile->gst_type !== 'composition') {
            return ['error' => 'Composition profile not set'];
        }
        [$from,$to] = $this->quarterRange($fy, $quarter);

        $turnover = (float) \App\Models\Sale::where('user_id',$userId)
            ->whereBetween('date', [$from, $to])
            ->sum('taxable_value');

        $rate = (float) ($profile->composition_rate ?? 1.0); // %
        $tax  = round($turnover * $rate / 100, 2);

        return [
            'fy'        => $fy,
            'quarter'   => $quarter,
            'gstin'     => $profile->gstin,
            'business_type' => $profile->business_type,
            'rate'      => $rate,
            'turnover'  => round($turnover, 2),
            'tax'       => $tax,
        ];
    }

    // Minimal skeleton for GSTR-4 JSON (for prefill/export; not official schema)
    public function toGstr4Json(array $q): array
    {
        return [
            'gstin' => $q['gstin'],
            'fy' => $q['fy'],
            'quarter' => $q['quarter'],
            'summary' => [
                'turnover' => $q['turnover'],
                'composition_rate' => $q['rate'],
                'tax_payable' => $q['tax'],
            ],
        ];
    }
}
