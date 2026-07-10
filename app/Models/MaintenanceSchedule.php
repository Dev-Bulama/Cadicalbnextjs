<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'schedule_code', 'user_id', 'institution_id', 'technician_id', 'equipment_name',
    'equipment_model', 'equipment_serial', 'service_type', 'frequency', 'site_address',
    'site_state', 'next_due_date', 'last_completed_at', 'is_active', 'reminder_days_before',
    'auto_assign', 'notes', 'contract_id',
])]
class MaintenanceSchedule extends Model
{
    public const FREQUENCY_WEEKLY = 'WEEKLY';

    public const FREQUENCY_MONTHLY = 'MONTHLY';

    public const FREQUENCY_QUARTERLY = 'QUARTERLY';

    public const FREQUENCY_BIANNUAL = 'BIANNUAL';

    public const FREQUENCY_ANNUAL = 'ANNUAL';

    protected function casts(): array
    {
        return [
            'next_due_date' => 'datetime',
            'last_completed_at' => 'datetime',
            'is_active' => 'boolean',
            'auto_assign' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(TechnicianProfile::class, 'technician_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'schedule_id');
    }

    public function isOverdue(): bool
    {
        return $this->is_active && $this->next_due_date?->isPast();
    }
}
