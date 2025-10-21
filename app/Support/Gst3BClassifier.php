<?php

// app/Support/Gst3BClassifier.php
namespace App\Support;

use App\Models\PurchaseInvoice;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class Gst3BClassifier
{
    /**
     * Available GSTR-3B bucket codes and their descriptions.
     */
    public const BUCKET_CODES = [
        '4A1' => 'ITC Available — Import of goods',
        '4A2' => 'ITC Available — Import of services',
        '4A3' => 'ITC Available — Inward supplies liable to RCM',
        '4A4' => 'ITC Available — Inward supplies from ISD',
        '4A5' => 'ITC Available — All other ITC',
        '4D1' => 'Ineligible ITC — Sec 17(5)',
        '4D2' => 'Ineligible ITC — Others',
    ];

    /**
     * Classify a purchase into a GSTR-3B Table-4 bucket.
     *
     * Returns:
     * [
     *   'code'   => '4A5'|'4A3'|'4A1'|'4D1'|'4D2',
     *   'label'  => string,
     *   'eligible' => bool,         // whether ITC to be added in 4(A)
     *   'amounts' => [
     *      'taxable_value' => float,
     *      'cgst' => float, 'sgst' => float, 'igst' => float, 'total_tax' => float
     *   ],
     * ]
     */
    public static function classify(PurchaseInvoice $p): array
    {
        $taxable = (float)$p->taxable_value;
        $cgst = (float)$p->cgst_amount;
        $sgst = (float)$p->sgst_amount;
        $igst = (float)$p->igst_amount;
        $totalTax = (float)$p->tax_amount;

        // Ineligible buckets first (override)
        if ($p->itc_eligibility === 'blocked') {
            return [
                'code' => '4D1',
                'label' => 'Ineligible ITC — Sec 17(5)',
                'eligible' => false,
                'amounts' => compact('taxable','cgst','sgst','igst','totalTax'),
            ];
        }
        if ($p->itc_eligibility === 'ineligible') {
            return [
                'code' => '4D2',
                'label' => 'Ineligible ITC — Others',
                'eligible' => false,
                'amounts' => compact('taxable','cgst','sgst','igst','totalTax'),
            ];
        }

        // Eligible ITC: decide sub-bucket
        // Import of goods (using your purchase bill + BoE) → 4(A)(1)
        if ($p->invoice_type === 'import' || $p->vendor_type === 'import') {
            return [
                'code' => '4A1',
                'label' => 'ITC Available — Import of goods',
                'eligible' => true,
                'amounts' => compact('taxable','cgst','sgst','igst','totalTax'),
            ];
        }

        // Inward supplies liable to RCM (other than imports) → 4(A)(3)
        if ($p->reverse_charge) {
            return [
                'code' => '4A3',
                'label' => 'ITC Available — Inward supplies liable to RCM',
                'eligible' => true,
                'amounts' => compact('taxable','cgst','sgst','igst','totalTax'),
            ];
        }

        // Default eligible bucket → All other ITC 4(A)(5)
        return [
            'code' => '4A5',
            'label' => 'ITC Available — All other ITC',
            'eligible' => true,
            'amounts' => compact('taxable','cgst','sgst','igst','totalTax'),
        ];
    }

    /**
     * Classify multiple purchase invoices and group by bucket codes.
     *
     * @param Collection|array $invoices
     * @return array
     */
    public static function classifyBulk($invoices): array
    {
        $grouped = [];

        foreach ($invoices as $invoice) {
            $classification = self::classify($invoice);
            $code = $classification['code'];

            if (!isset($grouped[$code])) {
                $grouped[$code] = [
                    'code' => $code,
                    'label' => $classification['label'],
                    'eligible' => $classification['eligible'],
                    'invoices' => [],
                    'totals' => [
                        'count' => 0,
                        'taxable_value' => 0,
                        'cgst' => 0,
                        'sgst' => 0,
                        'igst' => 0,
                        'total_tax' => 0,
                    ],
                ];
            }

            $grouped[$code]['invoices'][] = $invoice;
            $grouped[$code]['totals']['count']++;
            $grouped[$code]['totals']['taxable_value'] += $classification['amounts']['taxable'];
            $grouped[$code]['totals']['cgst'] += $classification['amounts']['cgst'];
            $grouped[$code]['totals']['sgst'] += $classification['amounts']['sgst'];
            $grouped[$code]['totals']['igst'] += $classification['amounts']['igst'];
            $grouped[$code]['totals']['total_tax'] += $classification['amounts']['totalTax'];
        }

        return $grouped;
    }

    /**
     * Generate GSTR-3B Table 4 summary for a given period.
     *
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return array
     */
    public static function generateTable4Summary(Carbon $fromDate, Carbon $toDate): array
    {
        $invoices = PurchaseInvoice::whereBetween('invoice_date', [$fromDate, $toDate])
            ->get();

        $grouped = self::classifyBulk($invoices);

        // Initialize all possible buckets with zero values
        $summary = [];
        foreach (self::BUCKET_CODES as $code => $label) {
            $summary[$code] = [
                'code' => $code,
                'label' => $label,
                'eligible' => in_array($code, ['4A1', '4A2', '4A3', '4A4', '4A5']),
                'invoices' => [],
                'totals' => [
                    'count' => 0,
                    'taxable_value' => 0,
                    'cgst' => 0,
                    'sgst' => 0,
                    'igst' => 0,
                    'total_tax' => 0,
                ],
            ];
        }

        // Merge actual data with the initialized structure
        foreach ($grouped as $code => $data) {
            $summary[$code] = $data;
        }

        return $summary;
    }

    /**
     * Get eligible ITC summary (4A sections only).
     *
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return array
     */
    public static function getEligibleItcSummary(Carbon $fromDate, Carbon $toDate): array
    {
        $fullSummary = self::generateTable4Summary($fromDate, $toDate);
        
        return array_filter($fullSummary, function($bucket) {
            return $bucket['eligible'];
        });
    }

    /**
     * Get ineligible ITC summary (4D sections only).
     *
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return array
     */
    public static function getIneligibleItcSummary(Carbon $fromDate, Carbon $toDate): array
    {
        $fullSummary = self::generateTable4Summary($fromDate, $toDate);
        
        return array_filter($fullSummary, function($bucket) {
            return !$bucket['eligible'];
        });
    }

    /**
     * Update bucket classification for existing invoices.
     * Useful for data migration or when classification logic changes.
     *
     * @param Collection|null $invoices
     * @return int Number of invoices updated
     */
    public static function updateBucketClassification($invoices = null): int
    {
        if ($invoices === null) {
            $invoices = PurchaseInvoice::all();
        }

        $updated = 0;
        foreach ($invoices as $invoice) {
            $classification = self::classify($invoice);
            
            if ($invoice->itc_bucket_code !== $classification['code'] || 
                $invoice->itc_bucket_label !== $classification['label']) {
                
                $invoice->update([
                    'itc_bucket_code' => $classification['code'],
                    'itc_bucket_label' => $classification['label'],
                ]);
                $updated++;
            }
        }

        return $updated;
    }
}