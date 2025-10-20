<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('gst_filings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('filing_type', ['GSTR1','GSTR3B','GSTR9'])->default('GSTR3B');
            $table->string('period'); // YYYY-MM
            $table->json('payload')->nullable();
            $table->string('status')->default('draft'); // draft|filed|failed
            $table->timestamp('filed_at')->nullable();
            $table->decimal('total_payable', 12,2)->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('gst_filings'); }
};
