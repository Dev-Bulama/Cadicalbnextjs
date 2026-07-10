<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_id')->constrained('crm_connections')->cascadeOnDelete();
            $table->string('sync_type'); // instant|scheduled|manual
            $table->string('entity'); // contact|account|deal|lead
            $table->string('direction'); // push|pull
            $table->string('status'); // running|success|partial|failed
            $table->integer('records_total')->default(0);
            $table->integer('records_synced')->default(0);
            $table->integer('records_failed')->default(0);
            $table->text('error_summary')->nullable();
            $table->integer('duration_ms')->nullable();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_sync_logs');
    }
};
