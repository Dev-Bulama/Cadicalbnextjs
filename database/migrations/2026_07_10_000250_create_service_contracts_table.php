<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->string('plan'); // BASIC|PREMIUM|ENTERPRISE
            $table->string('status')->default('ACTIVE'); // ACTIVE|EXPIRED|CANCELLED|SUSPENDED
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->timestamp('renewal_date')->nullable();
            $table->integer('response_hours')->default(24);
            $table->integer('resolution_hours')->default(72);
            $table->integer('included_visits')->default(4);
            $table->integer('used_visits')->default(0);
            $table->boolean('emergency_support')->default(false);
            $table->string('dedicated_tech_id')->nullable();
            $table->decimal('monthly_fee', 14, 2)->nullable();
            $table->decimal('annual_fee', 14, 2)->nullable();
            $table->string('currency')->default('NGN');
            $table->json('equipment_list')->nullable();
            $table->text('terms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_contracts');
    }
};
