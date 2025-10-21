<?php

namespace App\Support;

use App\Models\SalesInvoice;
use Carbon\Carbon;

class Cmp08SummaryService
{
    public static function forQuarter(string $quarterKey /* e.g. 2025Q3 */): array
    {
        [$start, $end] = self::quarterRange($quarterKey);

        $sales = SalesInvoice::query()
            ->whereBetween('invoice_date', [$start, $end])
            ->get();

        $taxable = $sales->sum('taxable_value');
        
        // Composition rate from profile (settings.composition_rate, %)
        $profile = settings('gst');
        $rate = (float)($profile['composition_rate'] ?? 1.0);
        $tax = round($taxable * ($rate / 100), 2);

        return [
            'quarter' => $quarterKey,
            'period_start' => $start->toDateString(),
            'period_end' => $end->toDateString(),
            'taxable' => round($taxable, 2), 
            'rate' => $rate, 
            'tax_payable' => $tax,
            'invoice_count' => $sales->count()
        ];
    }

    private static function quarterRange(string $q)
    {
        // '2025Q3' => Jul 1 to Sep 30 (adjust mapping)
        [$year, $qno] = [(int)substr($q, 0, 4), (int)substr($q, -1)];
        $map = [1 => [1, 3], 2 => [4, 6], 3 => [7, 9], 4 => [10, 12]];
        [$m1, $m2] = $map[$qno];
        $start = Carbon::create($year, $m1, 1)->startOfMonth();
        $end = Carbon::create($year, $m2, 1)->endOfMonth();
        return [$start, $end];
    }
}