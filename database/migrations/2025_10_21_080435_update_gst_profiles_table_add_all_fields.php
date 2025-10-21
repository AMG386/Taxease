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
        Schema::table('gst_profiles', function (Blueprint $table) {
            // Firm details
            $table->string('firm_name')->nullable()->after('user_id');
            $table->string('trade_name')->nullable()->after('firm_name');
            $table->text('address_line1')->nullable()->after('trade_name');
            $table->text('address_line2')->nullable()->after('address_line1');
            $table->string('pincode', 6)->nullable()->after('address_line2');
            $table->string('state')->nullable()->after('pincode');
            $table->string('city')->nullable()->after('state');
            
            // Additional GST fields
            $table->string('filing_frequency')->nullable()->after('business_type');
            $table->decimal('default_gst_rate', 5, 2)->nullable()->after('filing_frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gst_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'firm_name',
                'trade_name', 
                'address_line1',
                'address_line2',
                'pincode',
                'state',
                'city',
                'filing_frequency',
                'default_gst_rate'
            ]);
        });
    }
};
