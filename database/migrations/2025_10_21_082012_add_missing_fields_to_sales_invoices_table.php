<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            // Invoice classification fields
            $table->enum('invoice_type', ['b2b', 'b2c', 'export', 'sez', 'exempted'])->default('b2b')->after('origin_state');
            $table->enum('supply_type', ['intra', 'inter'])->default('intra')->after('invoice_type');
            $table->enum('reverse_charge', ['yes', 'no'])->default('no')->after('supply_type');
            
            // Unit pricing fields
            $table->decimal('unit_price', 15, 2)->nullable()->after('uom');
            $table->enum('tax_inclusive', ['yes', 'no'])->default('no')->after('unit_price');
            
            // Auto-calculated tax breakdown fields
            $table->decimal('cgst_rate', 5, 2)->default(0)->after('tax_rate');
            $table->decimal('sgst_rate', 5, 2)->default(0)->after('cgst_rate');
            $table->decimal('igst_rate', 5, 2)->default(0)->after('sgst_rate');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('igst_rate');
            
            // Final calculation fields
            $table->decimal('round_off', 15, 2)->default(0)->after('igst_amount');
            $table->decimal('total_invoice_value', 15, 2)->default(0)->after('round_off');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_type',
                'supply_type', 
                'reverse_charge',
                'unit_price',
                'tax_inclusive',
                'cgst_rate',
                'sgst_rate',
                'igst_rate',
                'tax_amount',
                'round_off',
                'total_invoice_value'
            ]);
        });
    }
};
