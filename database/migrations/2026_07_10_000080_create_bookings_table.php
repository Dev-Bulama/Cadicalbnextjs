<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->string('equipment_type')->nullable();
            $table->string('issue_type')->nullable();
            $table->string('urgency')->nullable();
            $table->string('consult_type')->nullable();
            $table->string('format')->nullable();
            $table->string('caller_type');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('org_name')->nullable();
            $table->string('role')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('location');
            $table->string('booking_type');
            $table->string('pref_date')->nullable();
            $table->string('selected_slot')->nullable();
            $table->string('callback_date')->nullable();
            $table->string('call_window')->nullable();
            $table->string('callback_phone')->nullable();
            $table->text('notes')->nullable();
            $table->string('ref')->unique();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
