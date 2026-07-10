<?php

namespace App\Livewire\Admin;

use App\Events\OrderTrackingUpdated;
use App\Models\Order;
use Livewire\Component;

class TrackingManager extends Component
{
    public string $search = '';

    public ?int $activeId = null;

    public function open(int $id): void
    {
        $this->activeId = $id;
    }

    public function closeDrawer(): void
    {
        $this->activeId = null;
    }

    public function addEvent(int $orderId, string $status, string $message, string $location = ''): void
    {
        $event = Order::findOrFail($orderId)->trackingEvents()->create([
            'status' => $status,
            'message' => $message,
            'location' => $location !== '' ? $location : null,
        ]);
        OrderTrackingUpdated::fire($event);
        $this->dispatch('cart-toast', message: 'Tracking event added');
    }

    public function render()
    {
        $orders = Order::when($this->search !== '', function ($q) {
            $q->where('tracking_code', 'like', '%'.$this->search.'%')
                ->orWhere('tracking_number', 'like', '%'.$this->search.'%');
        })
            ->latest()
            ->limit(50)
            ->get();

        $active = $this->activeId ? Order::with(['trackingEvents' => fn ($q) => $q->latest()])->find($this->activeId) : null;

        return view('livewire.admin.tracking-manager', compact('orders', 'active'));
    }
}
