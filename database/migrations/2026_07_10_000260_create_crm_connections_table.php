<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_connections', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // zoho | hubspot | salesforce | freshsales | custom
            $table->boolean('is_active')->default(false);
            $table->boolean('is_connected')->default(false);
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('redirect_uri')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->string('organization_id')->nullable();
            $table->string('api_domain')->nullable();
            $table->boolean('sync_enabled')->default(false);
            $table->string('sync_interval')->default('hourly'); // 5min|15min|hourly|daily
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamp('next_sync_at')->nullable();
            $table->integer('health_score')->default(100);
            $table->text('last_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_connections');
    }
};
