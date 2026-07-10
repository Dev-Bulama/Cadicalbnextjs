<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'booking_code', 'user_id', 'equipment_name', 'equipment_model', 'equipment_serial',
    'equipment_brand', 'service_type', 'urgency', 'issue_description', 'severity',
    'equipment_condition', 'site_address', 'site_city', 'site_state', 'site_contact',
    'site_phone', 'dynamic_fields', 'preferred_date', 'preferred_time_slot',
    'alternate_date', 'images', 'documents', 'estimated_cost', 'final_cost',
    'payment_status', 'payment_id', 'status', 'assigned_tech_id', 'notes', 'admin_notes',
])]
class ServiceBooking extends Model
{
    // ServiceType
    public const TYPE_INSTALLATION = 'INSTALLATION';

    public const TYPE_PREVENTIVE_MAINTENANCE = 'PREVENTIVE_MAINTENANCE';

    public const TYPE_REPAIR = 'REPAIR';

    public const TYPE_EMERGENCY_REPAIR = 'EMERGENCY_REPAIR';

    public const TYPE_INSPECTION = 'INSPECTION';

    public const TYPE_CALIBRATION = 'CALIBRATION';

    public const TYPE_UPGRADE = 'UPGRADE';

    public const TYPE_RELOCATION = 'RELOCATION';

    public const TYPE_CONSULTATION = 'CONSULTATION';

    public const TYPE_WARRANTY_SERVICE = 'WARRANTY_SERVICE';

    // ServiceStatus pipeline
    public const STATUS_BOOKED = 'BOOKED';

    public const STATUS_PENDING_APPROVAL = 'PENDING_APPROVAL';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_TECHNICIAN_ASSIGNED = 'TECHNICIAN_ASSIGNED';

    public const STATUS_TECHNICIAN_ACCEPTED = 'TECHNICIAN_ACCEPTED';

    public const STATUS_TECHNICIAN_EN_ROUTE = 'TECHNICIAN_EN_ROUTE';

    public const STATUS_INSPECTION_STARTED = 'INSPECTION_STARTED';

    public const STATUS_REPAIR_ONGOING = 'REPAIR_ONGOING';

    public const STATUS_WAITING_FOR_PARTS = 'WAITING_FOR_PARTS';

    public const STATUS_TESTING = 'TESTING';

    public const STATUS_COMPLETED = 'COMPLETED';

    public const STATUS_INVOICE_GENERATED = 'INVOICE_GENERATED';

    public const STATUS_FOLLOW_UP_SCHEDULED = 'FOLLOW_UP_SCHEDULED';

    public const STATUS_CANCELLED = 'CANCELLED';

    protected function casts(): array
    {
        return [
            'dynamic_fields' => 'array',
            'images' => 'array',
            'documents' => 'array',
            'estimated_cost' => 'decimal:2',
            'final_cost' => 'decimal:2',
            'preferred_date' => 'datetime',
            'alternate_date' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTech(): BelongsTo
    {
        return $this->belongsTo(TechnicianProfile::class, 'assigned_tech_id');
    }

    public function statusEvents(): HasMany
    {
        return $this->hasMany(ServiceStatusEvent::class, 'booking_id');
    }

    public function serviceJob(): HasOne
    {
        return $this->hasOne(ServiceJob::class, 'booking_id');
    }

    public function equipmentRecord(): HasOne
    {
        return $this->hasOne(EquipmentRecord::class, 'booking_id');
    }
}
