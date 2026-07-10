<?php

namespace App\Events;

use App\Models\ServiceJob;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class ServiceJobStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable;

    public function __construct(public ServiceJob $job) {}

    /**
     * Broadcast is best-effort: Pusher is an optional real-time enhancement,
     * so a missing/unreachable connection must never break the request that
     * triggered it (mirrors the original app's own try/catch around trigger()).
     */
    public static function fire(ServiceJob $job): void
    {
        if (! config('services.pusher.key')) {
            return;
        }

        try {
            static::dispatch($job);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function broadcastOn(): Channel
    {
        return new Channel('booking-'.$this->job->booking_id);
    }

    public function broadcastAs(): string
    {
        return 'status-update';
    }

    public function broadcastWith(): array
    {
        return [
            'status' => $this->job->status,
            'updated_at' => now()->toIso8601String(),
        ];
    }
}
