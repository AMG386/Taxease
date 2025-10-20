<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('invoices', function (Blueprint $table) {
            // States / POS
            $table->string('place_of_supply')->nullable()->after('gstin');
            $table->string('customer_state')->nullable()->after('place_of_supply');
            $table->string('supplier_state')->nullable()->after('customer_state');

            // GSTR-1 related identities
            $table->string('doc_type')->nullable()->after('supplier_state'); // INV/CRN/DBN
            $table->string('counterparty_gstin')->nullable()->after('doc_type');
            $table->string('counterparty_name')->nullable()->after('counterparty_gstin');

            // Flags
            $table->boolean('is_export')->default(false)->after('counterparty_name');
            $table->boolean('with_lut')->default(false)->after('is_export');
            $table->boolean('is_rcm')->default(false)->after('with_lut');
        });
    }

    public function down(): void {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'place_of_supply','customer_state','supplier_state',
                'doc_type','counterparty_gstin','counterparty_name',
                'is_export','with_lut','is_rcm'
            ]);
        });
    }
};
