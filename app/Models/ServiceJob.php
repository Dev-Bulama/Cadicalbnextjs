<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'job_code', 'booking_id', 'technician_id', 'status', 'diagnostic_notes', 'work_done',
    'parts_used', 'parts_requested', 'report_url', 'completion_images', 'scheduled_at',
    'started_at', 'completed_at', 'estimated_duration', 'labor_cost', 'parts_cost',
    'total_cost', 'invoice_url', 'follow_up_date', 'follow_up_notes',
])]
class ServiceJob extends Model
{
    public const STATUS_ASSIGNED = 'ASSIGNED';

    public const STATUS_ACCEPTED = 'ACCEPTED';

    public const STATUS_REJECTED = 'REJECTED';

    public const STATUS_EN_ROUTE = 'EN_ROUTE';

    public const STATUS_ON_SITE = 'ON_SITE';

    public const STATUS_IN_PROGRESS = 'IN_PROGRESS';

    public const STATUS_WAITING_PARTS = 'WAITING_PARTS';

    public const STATUS_COMPLETED = 'COMPLETED';

    public const STATUS_CANCELLED = 'CANCELLED';

    protected function casts(): array
    {
        return [
            'parts_used' => 'array',
            'parts_requested' => 'array',
            'completion_images' => 'array',
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'follow_up_date' => 'datetime',
            'labor_cost' => 'decimal:2',
            'parts_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(ServiceBooking::class, 'booking_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(TechnicianProfile::class, 'technician_id');
    }
}
