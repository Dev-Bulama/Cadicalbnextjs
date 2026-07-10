<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public ?int $activeId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function open(int $id): void
    {
        $this->activeId = $id;
    }

    public function closeDrawer(): void
    {
        $this->activeId = null;
    }

    public function updateStatus(int $id, string $status): void
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $status]);
        $order->trackingEvents()->create([
            'status' => $status,
            'message' => 'Status updated to '.$status.' by admin',
        ]);
        $this->dispatch('cart-toast', message: 'Order status updated');
    }

    public function render()
    {
        $orders = Order::with('user')
            ->when($this->search !== '', function ($q) {
                $q->where('tracking_code', 'like', '%'.$this->search.'%')
                    ->orWhereHas('user', fn ($u) => $u->where('email', 'like', '%'.$this->search.'%'));
            })
            ->when($this->statusFilter !== '', fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        $active = $this->activeId ? Order::with(['user', 'orderItems.product', 'trackingEvents'])->find($this->activeId) : null;

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', Order::STATUS_PENDING)->count(),
            'processing' => Order::where('status', Order::STATUS_PROCESSING)->count(),
            'delivered' => Order::where('status', Order::STATUS_DELIVERED)->count(),
        ];

        return view('livewire.admin.orders-manager', compact('orders', 'active', 'stats'));
    }
}
