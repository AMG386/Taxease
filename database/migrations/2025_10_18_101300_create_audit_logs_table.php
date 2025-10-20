<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index(); // actor (nullable)
                $table->string('action');                                   // created|updated|deleted
                $table->string('model_type');                               // \App\Models\Invoice etc.
                $table->unsignedBigInteger('model_id')->nullable();         // primary key of model
                // use json if your MySQL >= 5.7 / MariaDB 10.2; otherwise use longText
                $table->json('changes')->nullable();
                $table->string('ip')->nullable();
                $table->text('ua')->nullable();                             // user agent
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
