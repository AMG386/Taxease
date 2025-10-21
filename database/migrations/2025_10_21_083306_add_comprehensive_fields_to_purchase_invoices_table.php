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
        Schema::table('purchase_invoices', function (Blueprint $table) {
            // Modify existing fields to match new schema
            $table->string('invoice_no', 50)->change();
            $table->string('hsn', 20)->nullable()->change();
            $table->string('supplier_name', 190)->nullable()->change();
            $table->string('supplier_gstin', 15)->nullable()->change();
            $table->unsignedInteger('qty')->default(1)->change();
            $table->string('place_of_supply', 100)->nullable()->change();
            $table->string('origin_state', 100)->nullable()->change();

            // Add new classification fields
            $table->enum('vendor_type', ['registered','unregistered','sez','import'])->default('registered')->after('supplier_gstin');
            $table->enum('invoice_type', ['b2b','import','sez','exempted'])->default('b2b')->after('vendor_type');
            $table->boolean('reverse_charge')->default(false)->after('invoice_type');

            // Add supply type
            $table->enum('supply_type', ['intra','inter'])->default('intra')->after('origin_state');

            // Add import fields
            $table->string('boe_no', 50)->nullable()->after('supply_type');
            $table->date('boe_date')->nullable()->after('boe_no');

            // Add pricing fields
            $table->decimal('unit_price', 15, 2)->nullable()->after('uom');
            $table->boolean('tax_inclusive')->default(false)->after('unit_price');

            // Add rate breakdown fields
            $table->decimal('cgst_rate', 5, 2)->default(0)->after('tax_rate');
            $table->decimal('sgst_rate', 5, 2)->default(0)->after('cgst_rate');
            $table->decimal('igst_rate', 5, 2)->default(0)->after('sgst_rate');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('igst_rate');

            // Modify existing amount fields to have defaults
            $table->decimal('cgst_amount', 15, 2)->default(0)->change();
            $table->decimal('sgst_amount', 15, 2)->default(0)->change();
            $table->decimal('igst_amount', 15, 2)->default(0)->change();

            // Add final calculation fields
            $table->decimal('round_off', 10, 2)->default(0)->after('igst_amount');
            $table->decimal('total_invoice_value', 15, 2)->default(0)->after('round_off');

            // Add ITC fields
            $table->enum('itc_eligibility', ['eligible','ineligible','blocked'])->default('eligible')->after('total_invoice_value');
            $table->enum('itc_type', ['inputs','capital_goods','input_services'])->nullable()->after('itc_eligibility');
            $table->date('itc_avail_month')->nullable()->after('itc_type');
            $table->string('itc_reason', 255)->nullable()->after('itc_avail_month');

            // Add computed bucket fields for 3B reporting
            $table->string('itc_bucket_code', 10)->nullable()->after('itc_reason');
            $table->string('itc_bucket_label', 80)->nullable()->after('itc_bucket_code');

            // Add indexes
            $table->index(['invoice_date']);
            $table->index(['invoice_no']);
            $table->index(['supplier_gstin']);
            $table->index(['vendor_type', 'invoice_type']);
            $table->index(['itc_bucket_code', 'itc_avail_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn([
                'vendor_type',
                'invoice_type', 
                'reverse_charge',
                'supply_type',
                'boe_no',
                'boe_date',
                'unit_price',
                'tax_inclusive',
                'cgst_rate',
                'sgst_rate',
                'igst_rate',
                'tax_amount',
                'round_off',
                'total_invoice_value',
                'itc_eligibility',
                'itc_type',
                'itc_avail_month',
                'itc_reason',
                'itc_bucket_code',
                'itc_bucket_label'
            ]);

            // Drop indexes
            $table->dropIndex(['invoice_date']);
            $table->dropIndex(['invoice_no']); 
            $table->dropIndex(['supplier_gstin']);
            $table->dropIndex(['vendor_type', 'invoice_type']);
            $table->dropIndex(['itc_bucket_code', 'itc_avail_month']);
        });
    }
};
