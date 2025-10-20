<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->string('supplier_name')->nullable();
            $table->string('supplier_gstin')->nullable();
            $table->string('hsn')->nullable();
            $table->integer('qty')->default(0);
            $table->string('uom', 20)->nullable(); // Unit of measure
            $table->decimal('taxable_value', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('cgst_amount', 15, 2)->nullable();
            $table->decimal('sgst_amount', 15, 2)->nullable();
            $table->decimal('igst_amount', 15, 2)->nullable();
            $table->string('place_of_supply')->nullable();
            $table->string('origin_state')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_invoices');
    }
};
