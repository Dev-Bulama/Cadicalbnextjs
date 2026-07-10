<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('inst_name');
            $table->string('inst_type');
            $table->string('cac');
            $table->unsignedInteger('year_established')->nullable();
            $table->unsignedInteger('staff_count')->nullable();
            $table->unsignedInteger('bed_capacity')->nullable();
            $table->string('state');
            $table->string('lga');
            $table->string('address');
            $table->string('contact_name');
            $table->string('designation');
            $table->string('phone');
            $table->string('alt_phone')->nullable();
            $table->string('email');
            $table->json('services')->nullable();
            $table->json('specialist_opts')->nullable();
            $table->json('consult_opts')->nullable();
            $table->json('reagent_opts')->nullable();
            $table->json('edu_opts')->nullable();
            $table->string('volume')->nullable();
            $table->text('notes')->nullable();
            $table->string('nafdac')->nullable();
            $table->string('pcn')->nullable();
            $table->boolean('confirm_docs')->default(false);
            $table->string('account_email')->unique();
            $table->string('password_hash');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
