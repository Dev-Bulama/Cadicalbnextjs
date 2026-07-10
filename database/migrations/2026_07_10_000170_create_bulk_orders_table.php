<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulk_orders', function (Blueprint $table) {
            $table->id();
            $table->string('bulk_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('institution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('organization')->nullable();
            $table->json('items');
            $table->decimal('total_amount', 14, 2);
            $table->string('currency')->default('NGN');
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('final_amount', 14, 2);
            $table->string('delivery_address')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('contract_url')->nullable();
            $table->string('status')->default('DRAFT'); // DRAFT|SUBMITTED|NEGOTIATING|APPROVED|PROCESSING|SHIPPED|DELIVERED|CANCELLED
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_orders');
    }
};
