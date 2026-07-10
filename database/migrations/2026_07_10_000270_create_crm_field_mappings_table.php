<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_field_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('connection_id')->constrained('crm_connections')->cascadeOnDelete();
            $table->string('entity'); // contact|account|deal|lead|ticket
            $table->string('cadical_field');
            $table->string('crm_field');
            $table->string('direction')->default('both'); // tocrm|fromcrm|both
            $table->boolean('is_required')->default(false);
            $table->string('transform_fn')->nullable();
            $table->timestamps();
            $table->unique(['connection_id', 'entity', 'cadical_field'], 'crm_field_mapping_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_field_mappings');
    }
};
