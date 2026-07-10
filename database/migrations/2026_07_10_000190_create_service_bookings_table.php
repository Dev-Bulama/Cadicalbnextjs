<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('equipment_name');
            $table->string('equipment_model')->nullable();
            $table->string('equipment_serial')->nullable();
            $table->string('equipment_brand')->nullable();
            $table->string('service_type'); // ServiceType enum
            $table->string('urgency')->default('NORMAL'); // ROUTINE|NORMAL|URGENT|EMERGENCY
            $table->text('issue_description');
            $table->string('severity')->nullable();
            $table->string('equipment_condition')->nullable();
            $table->string('site_address');
            $table->string('site_city');
            $table->string('site_state');
            $table->string('site_contact')->nullable();
            $table->string('site_phone')->nullable();
            $table->json('dynamic_fields')->nullable();
            $table->timestamp('preferred_date')->nullable();
            $table->string('preferred_time_slot')->nullable();
            $table->timestamp('alternate_date')->nullable();
            $table->json('images')->nullable();
            $table->json('documents')->nullable();
            $table->decimal('estimated_cost', 14, 2)->nullable();
            $table->decimal('final_cost', 14, 2)->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_id')->nullable();
            $table->string('status')->default('BOOKED'); // ServiceStatus enum
            $table->foreignId('assigned_tech_id')->nullable()->constrained('technician_profiles')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_bookings');
    }
};
