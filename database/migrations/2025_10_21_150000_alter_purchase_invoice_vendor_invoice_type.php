<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->string('vendor_type', 16)->change();   // e.g., 'registered'
            $table->string('invoice_type', 16)->change();  // e.g., 'b2b'
        });
    }
    
    public function down(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            // Revert back to original lengths if needed
            $table->string('vendor_type', 10)->change();
            $table->string('invoice_type', 10)->change();
        });
    }
};