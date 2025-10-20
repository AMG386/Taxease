<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Master: each return filing instance (e.g., 2025-07 GSTR-3B)
        Schema::create('gst_returns', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();

            // gstr1, gstr3b, gstr4, gstr9, gstr9a, gstr9c, cmp08
            $t->string('type', 20)->index();

            // FY period handling (supports monthly/quarterly/annual)
            $t->date('period_from')->index();
            $t->date('period_to')->index();

            // draft, prepared, filed, rejected, cancelled
            $t->string('status', 20)->default('draft')->index();

            // Derived totals
            $t->decimal('taxable_value', 15, 2)->default(0);
            $t->decimal('cgst', 15, 2)->default(0);
            $t->decimal('sgst', 15, 2)->default(0);
            $t->decimal('igst', 15, 2)->default(0);
            $t->decimal('cess', 15, 2)->default(0);
            $t->decimal('total_tax', 15, 2)->default(0);
            $t->decimal('itc_eligible', 15, 2)->default(0);
            $t->decimal('net_payable', 15, 2)->default(0);

            // meta
            $t->json('meta')->nullable(); // frequency, notes, version, portal refs
            $t->timestamp('prepared_on')->nullable();
            $t->timestamp('filed_on')->nullable();
            $t->timestamps();
        });

        // Line items (normalized across forms)
        Schema::create('gst_return_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('gst_return_id')->constrained('gst_returns')->cascadeOnDelete();
            $t->string('section', 50)->nullable(); // e.g., B2B, B2C, RCM, 3.1(a), etc.
            $t->string('invoice_no', 50)->nullable();
            $t->date('invoice_date')->nullable();
            $t->string('counterparty_gstin', 15)->nullable();
            $t->string('party_name', 200)->nullable();
            $t->string('hsn', 20)->nullable();
            $t->decimal('qty', 15, 4)->default(0);
            $t->string('uom', 20)->nullable();

            $t->decimal('taxable_value', 15, 2)->default(0);
            $t->decimal('cgst', 15, 2)->default(0);
            $t->decimal('sgst', 15, 2)->default(0);
            $t->decimal('igst', 15, 2)->default(0);
            $t->decimal('cess', 15, 2)->default(0);
            $t->decimal('total', 15, 2)->default(0);

            // purchase-specific (ITC buckets etc.)
            $t->json('itc_breakup')->nullable(); // inputs, capital goods, services, blocked, etc.
            $t->json('raw')->nullable();         // preserved raw from your tx tables
            $t->timestamps();

            $t->index(['section', 'hsn']);
        });

        // Composition turnover (CMP-08 / GSTR-4 helper)
        Schema::create('gst_composition_turnovers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->date('period_from')->index();
            $t->date('period_to')->index();
            $t->decimal('total_turnover', 15, 2)->default(0);
            $t->decimal('tax_rate', 6, 3)->default(1.000); // % of turnover
            $t->decimal('tax_amount', 15, 2)->default(0);
            $t->string('status', 20)->default('draft')->index(); // draft/prepared/locked
            $t->json('meta')->nullable();
            $t->timestamps();
        });

        // Audit & attachments (GSTR-9C, working papers)
        Schema::create('gst_audit_files', function (Blueprint $t) {
            $t->id();
            $t->foreignId('gst_return_id')->constrained('gst_returns')->cascadeOnDelete();
            $t->string('filename');
            $t->string('disk')->default('public');
            $t->string('path');
            $t->text('remarks')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gst_audit_files');
        Schema::dropIfExists('gst_composition_turnovers');
        Schema::dropIfExists('gst_return_items');
        Schema::dropIfExists('gst_returns');
    }
};
