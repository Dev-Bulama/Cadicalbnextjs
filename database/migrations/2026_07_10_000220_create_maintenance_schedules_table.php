<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('technician_profiles')->nullOnDelete();
            $table->string('equipment_name');
            $table->string('equipment_model')->nullable();
            $table->string('equipment_serial')->nullable();
            $table->string('service_type'); // ServiceType enum
            $table->string('frequency'); // WEEKLY|MONTHLY|QUARTERLY|BIANNUAL|ANNUAL
            $table->string('site_address');
            $table->string('site_state');
            $table->timestamp('next_due_date');
            $table->timestamp('last_completed_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('reminder_days_before')->default(7);
            $table->boolean('auto_assign')->default(false);
            $table->text('notes')->nullable();
            $table->string('contract_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
