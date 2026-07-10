<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'connection_id', 'name', 'description', 'is_active', 'trigger_event', 'trigger_config',
    'action_type', 'action_config', 'last_run_at', 'run_count',
])]
class CrmAutomationRule extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'trigger_config' => 'array',
            'action_config' => 'array',
            'last_run_at' => 'datetime',
        ];
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(CrmConnection::class, 'connection_id');
    }
}
