<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id', 'guest_name', 'guest_email', 'guest_phone', 'total_amount', 'status', 'paid_at', 'payment_id',
    'payment_method', 'flutterwave_transaction_id', 'payment_reference', 'gateway_response', 'payment_metadata',
    'processed_at', 'shipping_address', 'notes', 'tracking_code', 'tracking_number', 'carrier',
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
            'paid_at' => 'datetime',
            'processed_at' => 'datetime',
            'gateway_response' => 'array',
            'payment_metadata' => 'array',
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
     * Cross-checks a FlutterwaveService::verifyTransaction() result against
     * this order's own recorded total before it's trusted enough to mark
     * paid — the whole point of re-verifying via Flutterwave's API instead
     * of trusting the webhook/redirect payload is defeated if a mismatched
     * amount is accepted anyway.
     */
    public function verifiedAmountMatches(array $result): bool
    {
        if (! isset($result['amount'])) {
            return false;
        }

        $verifiedAmount = round((float) $result['amount'], 2);
        $orderAmount = round((float) $this->total_amount, 2);

        if (abs($verifiedAmount - $orderAmount) > 0.01) {
            return false;
        }

        $verifiedCurrency = strtoupper((string) ($result['currency'] ?? 'NGN'));

        return $verifiedCurrency === 'NGN';
    }

    /**
     * True once this order has already recorded the given Flutterwave
     * charge id as paid — used by the webhook/callback to short-circuit a
     * duplicate delivery with an explicit "already processed" response,
     * distinct from (and checked before) the status-based idempotency guard
     * in markFlutterwavePaid() below.
     */
    public function hasProcessedFlutterwaveTransaction(string $transactionId): bool
    {
        return $this->status === self::STATUS_PAID && $this->flutterwave_transaction_id === $transactionId;
    }

    /**
     * Finalizes a pending Flutterwave order as paid, recording the verified
     * transaction detail (never the raw webhook payload alone — $details is
     * expected to come from FlutterwaveService::verifyTransaction()).
     * Guarded by the current status so this is safe to call twice — the
     * webhook and the customer's browser redirect back from Flutterwave's
     * hosted checkout can both arrive and race each other; whichever gets
     * here first wins, the second call is a no-op.
     *
     * @param  array{id?: string, reference?: string, amount?: float|string|null, currency?: string|null, customer_email?: string|null, raw?: array|null}  $details
     */
    public function markFlutterwavePaid(array $details = []): void
    {
        if ($this->status !== self::STATUS_PENDING) {
            return;
        }

        $this->status = self::STATUS_PAID;
        $this->paid_at = now();
        $this->processed_at = now();
        $this->flutterwave_transaction_id = $details['id'] ?? $this->payment_id;
        $this->payment_reference = $details['reference'] ?? $this->tracking_code;
        $this->gateway_response = $details['raw'] ?? null;
        $this->payment_metadata = array_filter([
            'amount' => $details['amount'] ?? null,
            'currency' => $details['currency'] ?? null,
            'customer_email' => $details['customer_email'] ?? null,
        ], fn ($v) => $v !== null);
        $this->save();

        $this->trackingEvents()->create([
            'status' => 'PAID',
            'message' => 'Payment received and order confirmed',
        ]);
    }

    /**
     * @see markFlutterwavePaid() — same idempotency guard, failure path.
     *
     * @param  array{id?: string, reference?: string, raw?: array|null}  $details
     */
    public function markFlutterwaveFailed(array $details = []): void
    {
        if ($this->status !== self::STATUS_PENDING) {
            return;
        }

        $this->status = self::STATUS_CANCELLED;
        $this->processed_at = now();
        if (! empty($details['id'])) {
            $this->flutterwave_transaction_id = $details['id'];
        }
        if (! empty($details['raw'])) {
            $this->gateway_response = $details['raw'];
        }
        $this->save();

        $this->trackingEvents()->create([
            'status' => 'CANCELLED',
            'message' => 'Payment failed or was not completed',
        ]);
    }
}
