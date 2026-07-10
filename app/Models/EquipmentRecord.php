<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'passport_code', 'user_id', 'institution_id', 'booking_id', 'equipment_name', 'brand',
    'model', 'serial_number', 'purchase_date', 'warranty_expiry', 'purchase_price',
    'condition', 'last_inspected_at', 'next_maintenance_at', 'service_history', 'notes',
])]
class EquipmentRecord extends Model
{
    protected function casts(): array
    {
        return [
            'purchase_date' => 'datetime',
            'warranty_expiry' => 'datetime',
            'last_inspected_at' => 'datetime',
            'next_maintenance_at' => 'datetime',
            'purchase_price' => 'decimal:2',
            'service_history' => 'array',
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

    public function booking(): BelongsTo
    {
        return $this->belongsTo(ServiceBooking::class, 'booking_id');
    }
}
