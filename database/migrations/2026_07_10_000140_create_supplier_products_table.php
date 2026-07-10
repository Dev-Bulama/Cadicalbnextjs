<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description');
            $table->string('category');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('unit_price', 14, 2);
            $table->decimal('bulk_price', 14, 2)->nullable();
            $table->integer('min_order_qty')->default(1);
            $table->integer('stock')->default(0);
            $table->json('images')->nullable();
            $table->json('specs')->nullable();
            $table->json('certifications')->nullable();
            $table->string('manual_url')->nullable();
            $table->string('datasheet_url')->nullable();
            $table->boolean('has_installation')->default(false);
            $table->boolean('has_warranty')->default(false);
            $table->unsignedInteger('warranty_months')->nullable();
            $table->boolean('has_maintenance')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_products');
    }
};
