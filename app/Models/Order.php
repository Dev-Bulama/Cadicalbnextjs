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

    /**
     * Finalizes a pending Flutterwave order as paid. Guarded by the current
     * status so this is safe to call twice — the webhook and the customer's
     * browser redirect back from Flutterwave's hosted checkout can both
     * arrive and race each other; whichever gets here first wins, the
     * second call is a no-op.
     */
    public function markFlutterwavePaid(): void
    {
        if ($this->status !== self::STATUS_PENDING) {
            return;
        }

        $this->status = self::STATUS_PAID;
        $this->save();

        $this->trackingEvents()->create([
            'status' => 'PAID',
            'message' => 'Payment received and order confirmed',
        ]);
    }

    /** @see markFlutterwavePaid() — same idempotency guard, failure path. */
    public function markFlutterwaveFailed(): void
    {
        if ($this->status !== self::STATUS_PENDING) {
            return;
        }

        $this->status = self::STATUS_CANCELLED;
        $this->save();

        $this->trackingEvents()->create([
            'status' => 'CANCELLED',
            'message' => 'Payment failed or was not completed',
        ]);
    }
}
