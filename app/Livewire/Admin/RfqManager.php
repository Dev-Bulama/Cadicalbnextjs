<?php

namespace App\Livewire\Admin;

use App\Models\Rfq;
use Livewire\Component;

class RfqManager extends Component
{
    public string $statusFilter = '';

    public ?int $activeId = null;

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
        Rfq::whereKey($id)->update(['status' => $status]);
        $this->dispatch('cart-toast', message: 'RFQ marked '.strtolower($status));
    }

    public function render()
    {
        $rfqs = Rfq::withCount('bids')
            ->when($this->statusFilter !== '', fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->get();

        $active = $this->activeId ? Rfq::with('bids.supplier')->find($this->activeId) : null;

        $stats = [
            'total' => Rfq::count(),
            'open' => Rfq::where('status', Rfq::STATUS_OPEN)->count(),
            'awarded' => Rfq::where('status', Rfq::STATUS_AWARDED)->count(),
            'closed' => Rfq::where('status', Rfq::STATUS_CLOSED)->count(),
        ];

        return view('livewire.admin.rfq-manager', compact('rfqs', 'active', 'stats'));
    }
}
