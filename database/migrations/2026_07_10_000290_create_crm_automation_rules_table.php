<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_automation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_id')->constrained('crm_connections')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('trigger_event'); // order_completed|rfq_submitted|booking_created|user_inactive
            $table->json('trigger_config')->nullable();
            $table->string('action_type'); // create_deal|create_lead|create_contact|create_ticket|update_stage
            $table->json('action_config')->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->integer('run_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_automation_rules');
    }
};
