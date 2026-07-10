<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['connection_id', 'event', 'payload', 'status', 'error_message'])]
class CrmWebhookLog extends Model
{
    public const CREATED_AT = 'received_at';

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return ['payload' => 'array'];
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(CrmConnection::class, 'connection_id');
    }
}
