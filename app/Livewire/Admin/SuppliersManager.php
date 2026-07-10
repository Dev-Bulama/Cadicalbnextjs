<?php

namespace App\Livewire\Admin;

use App\Models\Supplier;
use Livewire\Component;

class SuppliersManager extends Component
{
    public string $search = '';

    public string $statusFilter = '';

    public function updateStatus(int $id, string $status): void
    {
        Supplier::whereKey($id)->update([
            'status' => $status,
            'is_active' => $status === Supplier::STATUS_APPROVED,
            'verified_at' => $status === Supplier::STATUS_APPROVED ? now() : null,
            'verified_by' => $status === Supplier::STATUS_APPROVED ? auth()->user()->email : null,
        ]);

        $this->dispatch('cart-toast', message: 'Supplier '.strtolower($status));
    }

    public function render()
    {
        $suppliers = Supplier::withCount(['products', 'rfqBids', 'documents'])
            ->when($this->search !== '', function ($q) {
                $q->where('company_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter !== '', fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->get();

        $stats = [
            'total' => Supplier::count(),
            'pending' => Supplier::where('status', Supplier::STATUS_PENDING)->count(),
            'approved' => Supplier::where('status', Supplier::STATUS_APPROVED)->count(),
            'rejected' => Supplier::where('status', Supplier::STATUS_REJECTED)->count(),
        ];

        return view('livewire.admin.suppliers-manager', compact('suppliers', 'stats'));
    }
}
