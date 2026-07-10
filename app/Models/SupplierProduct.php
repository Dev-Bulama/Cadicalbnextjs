<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'supplier_id', 'name', 'description', 'category', 'brand', 'model', 'sku',
    'unit_price', 'bulk_price', 'min_order_qty', 'stock', 'images', 'specs',
    'certifications', 'manual_url', 'datasheet_url', 'has_installation',
    'has_warranty', 'warranty_months', 'has_maintenance', 'is_active', 'is_approved',
])]
class SupplierProduct extends Model
{
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'bulk_price' => 'decimal:2',
            'images' => 'array',
            'specs' => 'array',
            'certifications' => 'array',
            'has_installation' => 'boolean',
            'has_warranty' => 'boolean',
            'has_maintenance' => 'boolean',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
