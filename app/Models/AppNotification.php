<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'type', 'title', 'message', 'action_url', 'is_read', 'read_at',
    'sent_email', 'sent_sms', 'sent_push',
])]
class AppNotification extends Model
{
    protected $table = 'app_notifications';

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'sent_email' => 'boolean',
            'sent_sms' => 'boolean',
            'sent_push' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
