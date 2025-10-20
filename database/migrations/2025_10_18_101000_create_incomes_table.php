<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('incomes')) {
            Schema::create('incomes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->date('date');
                $table->string('head');       // business / rental / interest etc.
                $table->string('sub_head')->nullable();
                $table->string('ref_no')->nullable();
                $table->decimal('amount', 12, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
