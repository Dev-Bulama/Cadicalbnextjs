<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'contract_code', 'user_id', 'institution_id', 'plan', 'status', 'start_date', 'end_date',
    'renewal_date', 'response_hours', 'resolution_hours', 'included_visits', 'used_visits',
    'emergency_support', 'dedicated_tech_id', 'monthly_fee', 'annual_fee', 'currency',
    'equipment_list', 'terms',
])]
class ServiceContract extends Model
{
    public const PLAN_BASIC = 'BASIC';

    public const PLAN_PREMIUM = 'PREMIUM';

    public const PLAN_ENTERPRISE = 'ENTERPRISE';

    public const STATUS_ACTIVE = 'ACTIVE';

    public const STATUS_EXPIRED = 'EXPIRED';

    public const STATUS_CANCELLED = 'CANCELLED';

    public const STATUS_SUSPENDED = 'SUSPENDED';

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'renewal_date' => 'datetime',
            'emergency_support' => 'boolean',
            'monthly_fee' => 'decimal:2',
            'annual_fee' => 'decimal:2',
            'equipment_list' => 'array',
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
}
