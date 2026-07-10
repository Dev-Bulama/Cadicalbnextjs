<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['booking_id', 'status', 'message', 'notes', 'location', 'updated_by', 'updated_by_role'])]
class ServiceStatusEvent extends Model
{
    public const UPDATED_AT = null;

    public function booking(): BelongsTo
    {
        return $this->belongsTo(ServiceBooking::class, 'booking_id');
    }
}
