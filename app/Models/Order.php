<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id', 'guest_name', 'guest_email', 'guest_phone', 'total_amount', 'status', 'payment_id', 'payment_method',
    'shipping_address', 'notes', 'tracking_code', 'tracking_number', 'carrier',
])]
class Order extends Model
{
    public const STATUS_PENDING = 'PENDING';

    public const STATUS_PAID = 'PAID';

    public const STATUS_PROCESSING = 'PROCESSING';

    public const STATUS_SHIPPED = 'SHIPPED';

    public const STATUS_DELIVERED = 'DELIVERED';

    public const STATUS_CANCELLED = 'CANCELLED';

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(TrackingEvent::class);
    }

    public function customerName(): string
    {
        return $this->user->name ?? $this->guest_name ?? 'Guest';
    }

    public function customerEmail(): ?string
    {
        return $this->user->email ?? $this->guest_email;
    }

    public static function generateTrackingCode(): string
    {
        $random = strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
        $timestamp = substr((string) now()->timestamp, -4);

        return "TRK-{$random}-{$timestamp}";
    }
}
