<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('alt_phone')->nullable();
            $table->string('website')->nullable();
            $table->json('category')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('country')->default('Nigeria');
            $table->string('cac_number')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('nafdac_number')->nullable();
            $table->unsignedInteger('year_established')->nullable();
            $table->string('status')->default('PENDING'); // PENDING|APPROVED|REJECTED|SUSPENDED
            $table->boolean('is_active')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->string('verified_by')->nullable();
            $table->integer('total_products')->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->decimal('delivery_score', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
