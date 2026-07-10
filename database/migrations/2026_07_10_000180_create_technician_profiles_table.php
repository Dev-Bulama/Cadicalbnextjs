<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technician_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('profile_image')->nullable();
            $table->json('specializations')->nullable();
            $table->json('certifications')->nullable();
            $table->unsignedInteger('years_of_experience')->default(0);
            $table->string('state');
            $table->string('city');
            $table->string('base_address')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_on_job')->default(false);
            $table->string('current_location')->nullable();
            $table->boolean('share_location')->default(false);
            $table->string('status')->default('ACTIVE'); // ACTIVE|INACTIVE|ON_LEAVE|SUSPENDED
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_jobs')->default(0);
            $table->integer('completed_jobs')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technician_profiles');
    }
};
