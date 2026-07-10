<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_id')->constrained('crm_connections')->cascadeOnDelete();
            $table->string('event');
            $table->json('payload');
            $table->string('status'); // received|processed|failed
            $table->text('error_message')->nullable();
            $table->timestamp('received_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_webhook_logs');
    }
};
