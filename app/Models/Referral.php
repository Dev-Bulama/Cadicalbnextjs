<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'ref_id', 'referrer_full_name', 'referrer_designation', 'referrer_facility',
    'referrer_facility_type', 'referrer_phone', 'referrer_email', 'referrer_state',
    'referrer_lga', 'referrer_address', 'client_facility_name', 'client_type',
    'client_contact_person', 'client_phone', 'client_email', 'client_state',
    'client_lga', 'client_address', 'reason_for_request', 'supply_category',
    'specific_tests', 'urgency_level', 'quantity', 'delivery_method',
    'additional_notes', 'affiliate_id', 'referred_via', 'payment_preference',
    'estimated_value', 'consent',
])]
class Referral extends Model
{
    protected function casts(): array
    {
        return [
            'supply_category' => 'array',
            'specific_tests' => 'array',
            'consent' => 'boolean',
        ];
    }
}
