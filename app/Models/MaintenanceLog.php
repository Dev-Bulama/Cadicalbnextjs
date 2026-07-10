<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['schedule_id', 'completed_at', 'technician_id', 'notes', 'report_url', 'parts_used', 'cost'])]
class MaintenanceLog extends Model
{
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'parts_used' => 'array',
            'cost' => 'decimal:2',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class, 'schedule_id');
    }
}
