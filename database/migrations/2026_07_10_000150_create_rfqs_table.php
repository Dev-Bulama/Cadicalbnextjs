<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfqs', function (Blueprint $table) {
            $table->id();
            $table->string('rfq_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('organization')->nullable();
            $table->string('title');
            $table->text('description');
            $table->json('category')->nullable();
            $table->text('specifications')->nullable();
            $table->integer('quantity');
            $table->decimal('target_budget', 14, 2)->nullable();
            $table->string('currency')->default('NGN');
            $table->timestamp('delivery_date')->nullable();
            $table->string('delivery_address')->nullable();
            $table->json('attachments')->nullable();
            $table->string('status')->default('OPEN'); // OPEN|CLOSED|AWARDED|CANCELLED
            $table->timestamp('closing_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfqs');
    }
};
