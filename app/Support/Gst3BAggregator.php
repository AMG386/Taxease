<?php

// App/Support/Gst3BAggregator.php (optional)
namespace App\Support;

use App\Models\PurchaseInvoice;

class Gst3BAggregator
{
    /** Sum amounts per 3B bucket for a given month (YYYY-MM) */
    public static function forMonth(string $ym): array
    {
        $start = \Carbon\Carbon::parse("$ym-01")->startOfMonth();
        $end   = (clone $start)->endOfMonth();

        $rows = PurchaseInvoice::query()
            ->whereBetween('invoice_date', [$start, $end])
            ->get();

        $out = [];
        foreach ($rows as $p) {
            $b = Gst3BClassifier::classify($p);
            $code = $b['code'];
            if (!isset($out[$code])) {
                $out[$code] = [
                    'label' => $b['label'],
                    'taxable' => 0, 'cgst' => 0, 'sgst' => 0, 'igst' => 0, 'total_tax' => 0
                ];
            }
            $out[$code]['taxable']   += (float)$p->taxable_value;
            $out[$code]['cgst']      += (float)$p->cgst_amount;
            $out[$code]['sgst']      += (float)$p->sgst_amount;
            $out[$code]['igst']      += (float)$p->igst_amount;
            $out[$code]['total_tax'] += (float)$p->tax_amount;
        }
        // round nicely
        foreach ($out as &$v) {
            foreach (['taxable','cgst','sgst','igst','total_tax'] as $k) {
                $v[$k] = round($v[$k], 2);
            }
        }
        return $out;
    }
}