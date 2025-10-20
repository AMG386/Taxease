<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->string('hsn_sac')->nullable();
            $table->decimal('qty', 12, 3)->default(1);
            $table->decimal('rate', 12, 2)->default(0);
            $table->decimal('taxable_value', 12, 2)->default(0);
            $table->decimal('gst_rate', 5, 2)->default(0); // optional single rate for autosplit
            $table->decimal('cgst_rate', 5, 2)->default(0);
            $table->decimal('sgst_rate', 5, 2)->default(0);
            $table->decimal('igst_rate', 5, 2)->default(0);
            $table->decimal('cgst', 12, 2)->default(0);
            $table->decimal('sgst', 12, 2)->default(0);
            $table->decimal('igst', 12, 2)->default(0);
            $table->boolean('is_nil')->default(false);
            $table->boolean('is_exempt')->default(false);
            $table->boolean('is_non_gst')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('invoice_items'); }
};
