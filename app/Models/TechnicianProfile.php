<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id', 'first_name', 'last_name', 'phone', 'profile_image', 'specializations',
    'certifications', 'years_of_experience', 'state', 'city', 'base_address',
    'is_available', 'is_on_job', 'current_location', 'share_location', 'status',
    'rating', 'total_jobs', 'completed_jobs',
])]
class TechnicianProfile extends Model
{
    public const STATUS_ACTIVE = 'ACTIVE';

    public const STATUS_INACTIVE = 'INACTIVE';

    public const STATUS_ON_LEAVE = 'ON_LEAVE';

    public const STATUS_SUSPENDED = 'SUSPENDED';

    protected function casts(): array
    {
        return [
            'specializations' => 'array',
            'certifications' => 'array',
            'is_available' => 'boolean',
            'is_on_job' => 'boolean',
            'share_location' => 'boolean',
            'rating' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceBookings(): HasMany
    {
        return $this->hasMany(ServiceBooking::class, 'assigned_tech_id');
    }

    public function serviceJobs(): HasMany
    {
        return $this->hasMany(ServiceJob::class, 'technician_id');
    }

    public function maintenanceAssignments(): HasMany
    {
        return $this->hasMany(MaintenanceSchedule::class, 'technician_id');
    }
}
