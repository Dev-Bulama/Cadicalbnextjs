<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_records', function (Blueprint $table) {
            $table->id();
            $table->string('passport_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->unique()->constrained('service_bookings')->nullOnDelete();
            $table->string('equipment_name');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->timestamp('purchase_date')->nullable();
            $table->timestamp('warranty_expiry')->nullable();
            $table->decimal('purchase_price', 14, 2)->nullable();
            $table->string('condition')->default('good'); // excellent|good|fair|poor
            $table->timestamp('last_inspected_at')->nullable();
            $table->timestamp('next_maintenance_at')->nullable();
            $table->json('service_history')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_records');
    }
};
