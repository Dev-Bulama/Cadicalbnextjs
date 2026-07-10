<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'connection_id', 'sync_type', 'entity', 'direction', 'status', 'records_total',
    'records_synced', 'records_failed', 'error_summary', 'duration_ms', 'completed_at',
])]
class CrmSyncLog extends Model
{
    public const CREATED_AT = 'started_at';

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return ['completed_at' => 'datetime'];
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(CrmConnection::class, 'connection_id');
    }
}
