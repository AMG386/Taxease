<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gst_return_audits', function (Blueprint $t) {
            $t->id();
            $t->foreignId('gst_return_id')->constrained('gst_returns')->cascadeOnDelete();
            $t->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $t->string('file_path', 255);
            $t->string('original_name', 255);
            $t->unsignedBigInteger('size')->default(0);
            $t->string('mime', 100)->nullable();
            $t->string('remarks', 255)->nullable();
            $t->timestamps();

            $t->index(['gst_return_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gst_return_audits');
    }
};
