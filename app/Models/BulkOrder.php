<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'bulk_code', 'user_id', 'institution_id', 'supplier_id', 'contact_name', 'contact_email',
    'contact_phone', 'organization', 'items', 'total_amount', 'currency', 'discount_percent',
    'final_amount', 'delivery_address', 'delivery_date', 'payment_terms', 'contract_url',
    'status', 'notes',
])]
class BulkOrder extends Model
{
    public const STATUS_DRAFT = 'DRAFT';

    public const STATUS_SUBMITTED = 'SUBMITTED';

    public const STATUS_NEGOTIATING = 'NEGOTIATING';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_PROCESSING = 'PROCESSING';

    public const STATUS_SHIPPED = 'SHIPPED';

    public const STATUS_DELIVERED = 'DELIVERED';

    public const STATUS_CANCELLED = 'CANCELLED';

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'total_amount' => 'decimal:2',
            'discount_percent' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'delivery_date' => 'datetime',
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

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
