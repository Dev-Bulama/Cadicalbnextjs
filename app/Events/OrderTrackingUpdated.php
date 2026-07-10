<?php

namespace App\Events;

use App\Models\TrackingEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class OrderTrackingUpdated implements ShouldBroadcastNow
{
    use Dispatchable;

    public function __construct(public TrackingEvent $event) {}

    /**
     * Broadcast is best-effort: Pusher is an optional real-time enhancement,
     * so a missing/unreachable connection must never break the request that
     * triggered it (mirrors the original app's own try/catch around trigger()).
     */
    public static function fire(TrackingEvent $event): void
    {
        if (! config('services.pusher.key')) {
            return;
        }

        try {
            static::dispatch($event);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function broadcastOn(): Channel
    {
        return new Channel('order-'.$this->event->order_id);
    }

    public function broadcastAs(): string
    {
        return 'tracking-update';
    }

    public function broadcastWith(): array
    {
        return [
            'status' => $this->event->status,
            'message' => $this->event->message,
            'location' => $this->event->location,
            'created_at' => $this->event->created_at->toIso8601String(),
        ];
    }
}
