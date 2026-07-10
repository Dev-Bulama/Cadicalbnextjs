<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id', 'company_name', 'contact_name', 'email', 'phone', 'alt_phone', 'website',
    'category', 'description', 'logo', 'address', 'city', 'state', 'country',
    'cac_number', 'tax_id', 'nafdac_number', 'year_established', 'status', 'is_active',
    'verified_at', 'verified_by', 'total_products', 'total_orders', 'rating', 'delivery_score',
])]
class Supplier extends Model
{
    public const STATUS_PENDING = 'PENDING';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_REJECTED = 'REJECTED';

    public const STATUS_SUSPENDED = 'SUSPENDED';

    protected function casts(): array
    {
        return [
            'category' => 'array',
            'is_active' => 'boolean',
            'verified_at' => 'datetime',
            'rating' => 'decimal:2',
            'delivery_score' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(SupplierDocument::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(SupplierProduct::class);
    }

    public function rfqBids(): HasMany
    {
        return $this->hasMany(RfqBid::class);
    }

    public function bulkOrders(): HasMany
    {
        return $this->hasMany(BulkOrder::class);
    }
}
