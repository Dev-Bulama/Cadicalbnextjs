<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('ref_id')->unique();
            $table->string('referrer_full_name');
            $table->string('referrer_designation');
            $table->string('referrer_facility');
            $table->string('referrer_facility_type');
            $table->string('referrer_phone');
            $table->string('referrer_email')->nullable();
            $table->string('referrer_state');
            $table->string('referrer_lga')->nullable();
            $table->string('referrer_address')->nullable();
            $table->string('client_facility_name');
            $table->string('client_type');
            $table->string('client_contact_person')->nullable();
            $table->string('client_phone');
            $table->string('client_email')->nullable();
            $table->string('client_state');
            $table->string('client_lga')->nullable();
            $table->string('client_address')->nullable();
            $table->text('reason_for_request');
            $table->json('supply_category')->nullable();
            $table->json('specific_tests')->nullable();
            $table->string('urgency_level');
            $table->string('quantity')->nullable();
            $table->string('delivery_method')->nullable();
            $table->text('additional_notes')->nullable();
            $table->string('affiliate_id')->nullable();
            $table->string('referred_via')->nullable();
            $table->string('payment_preference')->nullable();
            $table->string('estimated_value')->nullable();
            $table->boolean('consent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
