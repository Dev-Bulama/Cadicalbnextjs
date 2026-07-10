<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['connection_id', 'entity', 'operation', 'payload', 'error_message', 'retry_count', 'max_retries', 'status'])]
class CrmFailedJob extends Model
{
    protected function casts(): array
    {
        return ['payload' => 'array'];
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(CrmConnection::class, 'connection_id');
    }
}
