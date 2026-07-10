<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_status_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('service_bookings')->cascadeOnDelete();
            $table->string('status');
            $table->string('message');
            $table->text('notes')->nullable();
            $table->string('location')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('updated_by_role')->nullable(); // admin|technician|system
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_status_events');
    }
};
