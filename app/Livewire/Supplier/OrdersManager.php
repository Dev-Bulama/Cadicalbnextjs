<?php

namespace App\Livewire\Supplier;

use App\Models\BulkOrder;
use App\Models\Supplier;
use Livewire\Attributes\Locked;
use Livewire\Component;

class OrdersManager extends Component
{
    #[Locked]
    public int $supplierId;

    public string $statusFilter = '';

    public function mount(): void
    {
        $this->supplierId = Supplier::where('user_id', auth()->id())->firstOrFail()->id;
    }

    public function render()
    {
        $orders = BulkOrder::where('supplier_id', $this->supplierId)
            ->when($this->statusFilter !== '', fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->get();

        return view('livewire.supplier.orders-manager', compact('orders'));
    }
}
