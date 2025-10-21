<?php

namespace App\Support;

use App\Models\SalesInvoice;
use Carbon\Carbon;

class Gstr1SummaryService
{
    public static function forPeriod(string $periodYm): array
    {
        [$start, $end] = self::range($periodYm);

        $rows = SalesInvoice::query()
            ->whereBetween('invoice_date', [$start, $end])
            ->get();

        $out = [
            'b2b' => [],       // per-GSTIN summary
            'b2c_large' => [], // inter-state, invoice value > 2.5L
            'b2c_small' => [], // others
            'export' => [],    // zero-rated (export)
            'sez' => [],       // zero-rated (SEZ)
            'exempt' => [],    // nil/exempt
            'rcm' => [],       // supplies attracting reverse charge
            'totals' => ['taxable'=>0,'cgst'=>0,'sgst'=>0,'igst'=>0,'cess'=>0,'invoice_count'=>0],
        ];

        foreach ($rows as $r) {
            $val = (float)$r->taxable_value;
            $cgst = (float)$r->cgst_amount;
            $sgst = (float)$r->sgst_amount;
            $igst = (float)$r->igst_amount;
            $invTotal = (float)$r->total_invoice_value;

            $out['totals']['taxable'] += $val;
            $out['totals']['cgst'] += $cgst;
            $out['totals']['sgst'] += $sgst;
            $out['totals']['igst'] += $igst;
            $out['totals']['invoice_count']++;

            $type = strtoupper($r->invoice_type);

            if ($type === 'B2B') {
                $key = $r->customer_gstin ?: 'UNKNOWN';
                if (!isset($out['b2b'][$key])) {
                    $out['b2b'][$key] = ['taxable'=>0,'cgst'=>0,'sgst'=>0,'igst'=>0,'count'=>0];
                }
                $out['b2b'][$key]['taxable'] += $val;
                $out['b2b'][$key]['cgst'] += $cgst;
                $out['b2b'][$key]['sgst'] += $sgst;
                $out['b2b'][$key]['igst'] += $igst;
                $out['b2b'][$key]['count']++;

            } elseif ($type === 'B2C') {
                // B2C large rule: inter-state AND invoice total > 250000
                $isInter = ($r->supply_type === 'inter');
                if ($isInter && $invTotal > 250000) {
                    $pos = strtoupper($r->place_of_supply);
                    if (!isset($out['b2c_large'][$pos])) $out['b2c_large'][$pos] = ['taxable'=>0,'igst'=>0,'count'=>0];
                    $out['b2c_large'][$pos]['taxable'] += $val;
                    $out['b2c_large'][$pos]['igst'] += $igst;
                    $out['b2c_large'][$pos]['count']++;
                } else {
                    $pos = strtoupper($r->place_of_supply);
                    if (!isset($out['b2c_small'][$pos])) $out['b2c_small'][$pos] = ['taxable'=>0,'cgst'=>0,'sgst'=>0,'igst'=>0,'count'=>0];
                    $out['b2c_small'][$pos]['taxable'] += $val;
                    $out['b2c_small'][$pos]['cgst'] += $cgst;
                    $out['b2c_small'][$pos]['sgst'] += $sgst;
                    $out['b2c_small'][$pos]['igst'] += $igst;
                    $out['b2c_small'][$pos]['count']++;
                }

            } elseif (in_array($type, ['IMP','EXPORT'])) {
                $out['export'][] = ['invoice_no'=>$r->invoice_no,'date'=>$r->invoice_date,'taxable'=>$val,'igst'=>$igst];

            } elseif ($type === 'SEZ') {
                $out['sez'][] = ['invoice_no'=>$r->invoice_no,'date'=>$r->invoice_date,'taxable'=>$val,'igst'=>$igst];

            } elseif ($type === 'EXEMPT' || $type === 'EXEMPTED') {
                $out['exempt'][] = ['invoice_no'=>$r->invoice_no,'date'=>$r->invoice_date,'taxable'=>$val];

            }

            if ((bool)$r->reverse_charge) {
                $out['rcm'][] = ['invoice_no'=>$r->invoice_no,'date'=>$r->invoice_date,'taxable'=>$val];
            }
        }
        
        // Round totals
        foreach (['totals'] as $k) {
            foreach ($out[$k] as $kk => $vv) {
                if (is_numeric($vv)) {
                    $out[$k][$kk] = round($vv, 2);
                }
            }
        }

        return $out;
    }

    private static function range(string $ym): array
    {
        $start = Carbon::parse("$ym-01")->startOfMonth();
        $end = (clone $start)->endOfMonth();
        return [$start, $end];
    }
}