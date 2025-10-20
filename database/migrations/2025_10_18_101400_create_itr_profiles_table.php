<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('itr_profiles')) {
            Schema::create('itr_profiles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('pan')->nullable();          // Permanent Account Number
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('mobile')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('pincode')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('account_no')->nullable();
                $table->string('ifsc')->nullable();
                $table->string('status')->default('active'); // active/inactive
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('itr_profiles');
    }
};
