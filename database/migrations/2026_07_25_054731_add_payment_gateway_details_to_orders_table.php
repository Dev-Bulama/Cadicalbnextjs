<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Purely additive — `orders.status` remains the single source of truth for
 * order lifecycle (PENDING/PAID/PROCESSING/...), read throughout the admin
 * console, customer order history, and tracking pages. These columns just
 * capture richer payment-gateway detail alongside it (previously only
 * `payment_id`/`payment_method` were stored), rather than splitting into
 * separate payment_status/order_status fields, which would have meant
 * touching every existing status check across the app for no functional
 * gain — the two are set together in Order::markFlutterwavePaid() anyway.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable()->after('status');
            $table->string('flutterwave_transaction_id')->nullable()->after('payment_id');
            $table->string('payment_reference')->nullable()->after('flutterwave_transaction_id');
            $table->json('gateway_response')->nullable()->after('payment_method');
            $table->json('payment_metadata')->nullable()->after('gateway_response');
            $table->timestamp('processed_at')->nullable()->after('payment_metadata');

            $table->index('flutterwave_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['flutterwave_transaction_id']);
            $table->dropColumn([
                'paid_at', 'flutterwave_transaction_id', 'payment_reference',
                'gateway_response', 'payment_metadata', 'processed_at',
            ]);
        });
    }
};
