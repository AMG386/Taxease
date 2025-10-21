<?php

namespace App\Support;

use App\Models\SalesInvoice;
use App\Models\PurchaseInvoice;
use Carbon\Carbon;

class Gstr3BSummaryService
{
    public static function forPeriod(string $periodYm): array
    {
        [$start, $end] = self::range($periodYm);

        // SALES → Outward supplies
        $sales = SalesInvoice::query()
            ->whereBetween('invoice_date', [$start, $end])
            ->get();

        $s31a = ['taxable'=>0,'igst'=>0,'cgst'=>0,'sgst'=>0,'cess'=>0];
        $s31b = ['taxable'=>0,'igst'=>0]; // zero-rated (exports/SEZ) generally IGST or LUT 0
        $s31c = ['nil_exempt'=>0]; // nil/exempt
        $s31e = ['non_gst'=>0]; // optional

        foreach ($sales as $s) {
            $type = strtoupper($s->invoice_type);
            $tv = (float)$s->taxable_value; 
            $cgst = (float)$s->cgst_amount; 
            $sgst = (float)$s->sgst_amount; 
            $igst = (float)$s->igst_amount;

            if (in_array($type, ['IMP','EXPORT','SEZ'])) {
                $s31b['taxable'] += $tv; 
                $s31b['igst'] += $igst;
            } elseif (in_array($type, ['EXEMPT','EXEMPTED'])) {
                $s31c['nil_exempt'] += $tv;
            } else {
                // B2B/B2C regular taxable
                $s31a['taxable'] += $tv; 
                $s31a['igst'] += $igst; 
                $s31a['cgst'] += $cgst; 
                $s31a['sgst'] += $sgst;
            }
        }

        // PURCHASES → ITC & RCM
        $purchases = PurchaseInvoice::query()
            ->whereBetween('invoice_date', [$start, $end])
            ->get();

        $rcmInward = ['taxable'=>0,'igst'=>0,'cgst'=>0,'sgst'=>0]; // 3.1(d)
        $itc = [
            '4A1_imports' => ['taxable'=>0,'igst'=>0],
            '4A3_rcm'     => ['taxable'=>0,'igst'=>0,'cgst'=>0,'sgst'=>0],
            '4A5_other'   => ['taxable'=>0,'igst'=>0,'cgst'=>0,'sgst'=>0],
            '4D1_blocked' => ['taxable'=>0,'igst'=>0,'cgst'=>0,'sgst'=>0],
            '4D2_inelig'  => ['taxable'=>0,'igst'=>0,'cgst'=>0,'sgst'=>0],
        ];

        foreach ($purchases as $p) {
            $tv = (float)$p->taxable_value; 
            $cg = (float)$p->cgst_amount; 
            $sg = (float)$p->sgst_amount; 
            $ig = (float)$p->igst_amount;

            // 3.1(d) inward supplies liable to RCM (you pay tax)
            if ((bool)$p->reverse_charge) {
                $rcmInward['taxable'] += $tv;
                $rcmInward['igst'] += $ig; 
                $rcmInward['cgst'] += $cg; 
                $rcmInward['sgst'] += $sg;
            }

            // ITC buckets (use your classifier if stored; else infer)
            $code = $p->itc_bucket_code;
            if (!$code) {
                $bucket = \App\Support\Gst3BClassifier::classify($p);
                $code = $bucket['code'];
            }
            
            switch ($code) {
                case '4A1': 
                    $itc['4A1_imports']['taxable'] += $tv; 
                    $itc['4A1_imports']['igst'] += $ig; 
                    break;
                case '4A3': 
                    $itc['4A3_rcm']['taxable'] += $tv; 
                    $itc['4A3_rcm']['igst'] += $ig; 
                    $itc['4A3_rcm']['cgst'] += $cg; 
                    $itc['4A3_rcm']['sgst'] += $sg; 
                    break;
                case '4D1': 
                    $itc['4D1_blocked']['taxable'] += $tv; 
                    $itc['4D1_blocked']['igst'] += $ig; 
                    $itc['4D1_blocked']['cgst'] += $cg; 
                    $itc['4D1_blocked']['sgst'] += $sg; 
                    break;
                case '4D2': 
                    $itc['4D2_inelig']['taxable'] += $tv; 
                    $itc['4D2_inelig']['igst'] += $ig; 
                    $itc['4D2_inelig']['cgst'] += $cg; 
                    $itc['4D2_inelig']['sgst'] += $sg; 
                    break;
                default:    
                    $itc['4A5_other']['taxable'] += $tv; 
                    $itc['4A5_other']['igst'] += $ig; 
                    $itc['4A5_other']['cgst'] += $cg; 
                    $itc['4A5_other']['sgst'] += $sg; 
                    break;
            }
        }

        // Round all values
        $roundAll = function (&$arr) use (&$roundAll) {
            foreach ($arr as $k => $v) {
                if (is_array($v)) {
                    $roundAll($arr[$k]); 
                } else {
                    $arr[$k] = round($v, 2);
                }
            }
        };
        
        $roundAll($s31a); 
        $roundAll($s31b); 
        $roundAll($s31c); 
        $roundAll($s31e);
        $roundAll($rcmInward); 
        $roundAll($itc);

        return compact('s31a','s31b','s31c','s31e','rcmInward','itc');
    }

    private static function range(string $ym): array
    {
        $start = Carbon::parse("$ym-01")->startOfMonth();
        $end = (clone $start)->endOfMonth();
        return [$start, $end];
    }
}