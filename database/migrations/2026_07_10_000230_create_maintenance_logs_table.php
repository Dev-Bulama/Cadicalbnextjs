<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('maintenance_schedules')->cascadeOnDelete();
            $table->timestamp('completed_at');
            $table->string('technician_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('report_url')->nullable();
            $table->json('parts_used')->nullable();
            $table->decimal('cost', 14, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
