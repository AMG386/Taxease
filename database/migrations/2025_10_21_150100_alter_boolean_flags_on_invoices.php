<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->boolean('reverse_charge')->default(false)->change();
            $table->boolean('tax_inclusive')->default(false)->change();
        });
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->boolean('reverse_charge')->default(false)->change();
            $table->boolean('tax_inclusive')->default(false)->change();
        });
    }
    
    public function down(): void
    {
        // Revert back to original types if needed
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->string('reverse_charge', 10)->nullable()->change();
            $table->string('tax_inclusive', 10)->nullable()->change();
        });
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->string('reverse_charge', 10)->nullable()->change();
            $table->string('tax_inclusive', 10)->nullable()->change();
        });
    }
};