<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_id')->constrained('crm_connections')->cascadeOnDelete();
            $table->string('entity');
            $table->string('operation'); // create|update|delete
            $table->json('payload');
            $table->text('error_message');
            $table->integer('retry_count')->default(0);
            $table->integer('max_retries')->default(3);
            $table->string('status')->default('pending'); // pending|retrying|resolved|abandoned
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_failed_jobs');
    }
};
