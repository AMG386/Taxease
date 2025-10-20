// database/migrations/2025_10_20_120000_create_gst_profiles_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('gst_profiles')) {
            Schema::create('gst_profiles', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('user_id')->index();
                $t->string('gstin')->nullable()->index();
                $t->enum('gst_type', ['regular','composition'])->default('regular');
                $t->string('business_type')->nullable();   // manufacturer|trader|restaurant|service
                $t->decimal('composition_rate', 5, 2)->nullable(); // % (e.g., 1.00, 5.00, 6.00)
                $t->json('meta')->nullable();              // place_of_business, trade_name, etc.
                $t->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('gst_profiles');
    }
};
