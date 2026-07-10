<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount', 14, 2);
            $table->string('status')->default('PENDING'); // PENDING|PAID|PROCESSING|SHIPPED|DELIVERED|CANCELLED
            $table->string('payment_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('shipping_address')->nullable();
            $table->text('notes')->nullable();
            $table->string('tracking_code')->unique();
            $table->string('tracking_number')->nullable();
            $table->string('carrier')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
