<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Support\Gst3BClassifier;
use App\Models\PurchaseInvoice;

class UpdateItcBuckets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'itc:update-buckets 
                           {--from= : Start date (YYYY-MM-DD format)}
                           {--to= : End date (YYYY-MM-DD format)}
                           {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update ITC bucket classifications for purchase invoices based on current logic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Updating ITC bucket classifications...');

        // Build query based on date filters
        $query = PurchaseInvoice::query();
        
        if ($this->option('from')) {
            $query->whereDate('invoice_date', '>=', $this->option('from'));
            $this->info("📅 Filtering from: " . $this->option('from'));
        }
        
        if ($this->option('to')) {
            $query->whereDate('invoice_date', '<=', $this->option('to'));
            $this->info("📅 Filtering to: " . $this->option('to'));
        }

        $invoices = $query->get();
        $totalInvoices = $invoices->count();

        if ($totalInvoices === 0) {
            $this->warn('⚠️  No purchase invoices found matching the criteria.');
            return;
        }

        $this->info("📋 Found {$totalInvoices} purchase invoices to process.");
        
        // Track changes
        $changes = [];
        $updated = 0;

        // Progress bar
        $progressBar = $this->output->createProgressBar($totalInvoices);
        $progressBar->start();

        foreach ($invoices as $invoice) {
            $classification = Gst3BClassifier::classify($invoice);
            
            $needsUpdate = $invoice->itc_bucket_code !== $classification['code'] || 
                          $invoice->itc_bucket_label !== $classification['label'];
            
            if ($needsUpdate) {
                $oldCode = $invoice->itc_bucket_code;
                $newCode = $classification['code'];
                
                if (!isset($changes[$oldCode . ' → ' . $newCode])) {
                    $changes[$oldCode . ' → ' . $newCode] = 0;
                }
                $changes[$oldCode . ' → ' . $newCode]++;

                if (!$this->option('dry-run')) {
                    $invoice->update([
                        'itc_bucket_code' => $classification['code'],
                        'itc_bucket_label' => $classification['label'],
                    ]);
                }
                $updated++;
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Show results
        if ($updated > 0) {
            if ($this->option('dry-run')) {
                $this->warn("🔍 DRY RUN: {$updated} invoices would be updated:");
            } else {
                $this->info("✅ Successfully updated {$updated} invoices:");
            }

            // Show change breakdown
            $this->table(
                ['Change', 'Count'],
                collect($changes)->map(function ($count, $change) {
                    return [$change, $count];
                })->toArray()
            );
        } else {
            $this->info('✨ All invoices already have correct bucket classifications!');
        }

        // Show summary by bucket
        $this->newLine();
        $this->info('📊 Current distribution by bucket:');
        
        $summary = $invoices->groupBy('itc_bucket_code')->map(function ($group, $code) {
            return [
                'code' => $code ?: 'NULL',
                'label' => $group->first()->itc_bucket_label ?: 'No label',
                'count' => $group->count(),
                'total_value' => $group->sum('taxable_value'),
            ];
        });

        $this->table(
            ['Bucket Code', 'Label', 'Count', 'Total Value'],
            $summary->values()->toArray()
        );

        if ($this->option('dry-run')) {
            $this->newLine();
            $this->comment('💡 Run without --dry-run to apply the changes.');
        }
    }
}
