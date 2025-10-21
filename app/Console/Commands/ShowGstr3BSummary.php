<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Support\Gst3BClassifier;
use Carbon\Carbon;

class ShowGstr3BSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gstr3b:summary 
                           {--month= : Month in YYYY-MM format (default: current month)}
                           {--from= : Start date (YYYY-MM-DD format)}
                           {--to= : End date (YYYY-MM-DD format)}
                           {--eligible-only : Show only eligible ITC buckets}
                           {--ineligible-only : Show only ineligible ITC buckets}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate GSTR-3B Table 4 summary for Input Tax Credit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📊 GSTR-3B Table 4 (Input Tax Credit) Summary');
        $this->newLine();

        // Determine date range
        if ($this->option('month')) {
            $month = Carbon::createFromFormat('Y-m', $this->option('month'));
            $fromDate = $month->startOfMonth();
            $toDate = $month->copy()->endOfMonth();
            $period = $month->format('F Y');
        } elseif ($this->option('from') && $this->option('to')) {
            $fromDate = Carbon::parse($this->option('from'));
            $toDate = Carbon::parse($this->option('to'));
            $period = $fromDate->format('d M Y') . ' to ' . $toDate->format('d M Y');
        } else {
            // Default to current month
            $fromDate = Carbon::now()->startOfMonth();
            $toDate = Carbon::now()->endOfMonth();
            $period = Carbon::now()->format('F Y') . ' (Current Month)';
        }

        $this->info("📅 Period: {$period}");
        $this->info("📅 From: {$fromDate->format('d-m-Y')} To: {$toDate->format('d-m-Y')}");
        $this->newLine();

        // Generate summary
        $summary = Gst3BClassifier::generateTable4Summary($fromDate, $toDate);

        // Filter based on options
        if ($this->option('eligible-only')) {
            $summary = array_filter($summary, fn($bucket) => $bucket['eligible']);
            $this->info('🟢 Showing Eligible ITC only (Table 4A)');
        } elseif ($this->option('ineligible-only')) {
            $summary = array_filter($summary, fn($bucket) => !$bucket['eligible']);
            $this->info('🔴 Showing Ineligible ITC only (Table 4D)');
        }

        if (empty($summary)) {
            $this->warn('⚠️  No data found for the specified period and filters.');
            return;
        }

        // Calculate totals
        $grandTotals = [
            'count' => 0,
            'taxable_value' => 0,
            'cgst' => 0,
            'sgst' => 0,
            'igst' => 0,
            'total_tax' => 0,
        ];

        foreach ($summary as $bucket) {
            $grandTotals['count'] += $bucket['totals']['count'];
            $grandTotals['taxable_value'] += $bucket['totals']['taxable_value'];
            $grandTotals['cgst'] += $bucket['totals']['cgst'];
            $grandTotals['sgst'] += $bucket['totals']['sgst'];
            $grandTotals['igst'] += $bucket['totals']['igst'];
            $grandTotals['total_tax'] += $bucket['totals']['total_tax'];
        }

        // Format data for table
        $tableData = [];
        foreach ($summary as $bucket) {
            $totals = $bucket['totals'];
            $tableData[] = [
                $bucket['code'],
                $bucket['eligible'] ? '✅' : '❌',
                $totals['count'],
                number_format($totals['taxable_value'], 2),
                number_format($totals['cgst'], 2),
                number_format($totals['sgst'], 2),
                number_format($totals['igst'], 2),
                number_format($totals['total_tax'], 2),
            ];
        }

        // Add totals row
        if (count($tableData) > 1) {
            $tableData[] = [
                'TOTAL',
                '—',
                $grandTotals['count'],
                number_format($grandTotals['taxable_value'], 2),
                number_format($grandTotals['cgst'], 2),
                number_format($grandTotals['sgst'], 2),
                number_format($grandTotals['igst'], 2),
                number_format($grandTotals['total_tax'], 2),
            ];
        }

        // Display table
        $this->table([
            'Bucket',
            'Eligible',
            'Count',
            'Taxable Value',
            'CGST',
            'SGST', 
            'IGST',
            'Total Tax'
        ], $tableData);

        // Show bucket descriptions
        $this->newLine();
        $this->info('📋 Bucket Descriptions:');
        foreach ($summary as $bucket) {
            if ($bucket['totals']['count'] > 0) {
                $status = $bucket['eligible'] ? '✅' : '❌';
                $this->line("  {$bucket['code']} {$status} {$bucket['label']}");
            }
        }

        // Show eligible vs ineligible summary
        if (!$this->option('eligible-only') && !$this->option('ineligible-only')) {
            $this->newLine();
            $eligibleTotal = array_sum(array_column(
                array_filter($summary, fn($b) => $b['eligible']), 
                'totals'
            ));
            $ineligibleTotal = array_sum(array_column(
                array_filter($summary, fn($b) => !$b['eligible']), 
                'totals'
            ));

            $eligibleTax = collect($summary)
                ->where('eligible', true)
                ->sum('totals.total_tax');
            
            $ineligibleTax = collect($summary)
                ->where('eligible', false)
                ->sum('totals.total_tax');

            $this->info('💰 Summary:');
            $this->line("  Eligible ITC (4A):   ₹" . number_format($eligibleTax, 2));
            $this->line("  Ineligible ITC (4D): ₹" . number_format($ineligibleTax, 2));
            $this->line("  Total ITC Claimed:   ₹" . number_format($eligibleTax, 2));
        }
    }
}
