<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'rfq_code', 'user_id', 'institution_id', 'contact_name', 'contact_email', 'contact_phone',
    'organization', 'title', 'description', 'category', 'specifications', 'quantity',
    'target_budget', 'currency', 'delivery_date', 'delivery_address', 'attachments',
    'status', 'closing_date',
])]
class Rfq extends Model
{
    public const STATUS_OPEN = 'OPEN';

    public const STATUS_CLOSED = 'CLOSED';

    public const STATUS_AWARDED = 'AWARDED';

    public const STATUS_CANCELLED = 'CANCELLED';

    protected function casts(): array
    {
        return [
            'category' => 'array',
            'attachments' => 'array',
            'target_budget' => 'decimal:2',
            'delivery_date' => 'datetime',
            'closing_date' => 'datetime',
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

    public function bids(): HasMany
    {
        return $this->hasMany(RfqBid::class);
    }
}
