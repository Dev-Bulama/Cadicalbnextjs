<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_code')->unique();
            $table->foreignId('booking_id')->unique()->constrained('service_bookings')->cascadeOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('technician_profiles')->nullOnDelete();
            $table->string('status')->default('ASSIGNED'); // JobStatus enum
            $table->text('diagnostic_notes')->nullable();
            $table->text('work_done')->nullable();
            $table->json('parts_used')->nullable();
            $table->json('parts_requested')->nullable();
            $table->string('report_url')->nullable();
            $table->json('completion_images')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('estimated_duration')->nullable();
            $table->decimal('labor_cost', 14, 2)->nullable();
            $table->decimal('parts_cost', 14, 2)->nullable();
            $table->decimal('total_cost', 14, 2)->nullable();
            $table->string('invoice_url')->nullable();
            $table->timestamp('follow_up_date')->nullable();
            $table->text('follow_up_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_jobs');
    }
};
