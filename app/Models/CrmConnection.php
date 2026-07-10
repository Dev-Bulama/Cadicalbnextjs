<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'provider', 'is_active', 'is_connected', 'client_id', 'client_secret', 'redirect_uri',
    'access_token', 'refresh_token', 'token_expires_at', 'organization_id', 'api_domain',
    'sync_enabled', 'sync_interval', 'last_sync_at', 'next_sync_at', 'health_score', 'last_error',
])]
#[Hidden(['client_secret', 'access_token', 'refresh_token'])]
class CrmConnection extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_connected' => 'boolean',
            'sync_enabled' => 'boolean',
            'token_expires_at' => 'datetime',
            'last_sync_at' => 'datetime',
            'next_sync_at' => 'datetime',
        ];
    }

    public function fieldMappings(): HasMany
    {
        return $this->hasMany(CrmFieldMapping::class, 'connection_id');
    }

    public function syncLogs(): HasMany
    {
        return $this->hasMany(CrmSyncLog::class, 'connection_id');
    }

    public function automationRules(): HasMany
    {
        return $this->hasMany(CrmAutomationRule::class, 'connection_id');
    }

    public function webhookLogs(): HasMany
    {
        return $this->hasMany(CrmWebhookLog::class, 'connection_id');
    }

    public function failedJobs(): HasMany
    {
        return $this->hasMany(CrmFailedJob::class, 'connection_id');
    }
}
