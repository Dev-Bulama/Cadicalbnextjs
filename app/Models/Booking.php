<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'service', 'equipment_type', 'issue_type', 'urgency', 'consult_type', 'format',
    'caller_type', 'first_name', 'last_name', 'org_name', 'role', 'phone', 'email',
    'location', 'booking_type', 'pref_date', 'selected_slot', 'callback_date',
    'call_window', 'callback_phone', 'notes', 'ref',
])]
class Booking extends Model
{
    public const UPDATED_AT = null;
}
