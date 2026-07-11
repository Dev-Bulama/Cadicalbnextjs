<?php

namespace App\Livewire\Admin;

use App\Models\TechnicianProfile;
use Livewire\Component;

class TechniciansManager extends Component
{
    public string $search = '';

    public string $statusFilter = '';

    public function updateStatus(int $id, string $status): void
    {
        TechnicianProfile::whereKey($id)->update(['status' => $status]);
        $this->dispatch('cart-toast', message: 'Technician status updated');
    }

    public function render()
    {
        $technicians = TechnicianProfile::withCount('serviceJobs')
            ->when($this->search !== '', function ($q) {
                $q->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%')
                    ->orWhere('phone', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter !== '', fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->get();

        $stats = [
            'total' => TechnicianProfile::count(),
            'active' => TechnicianProfile::where('status', TechnicianProfile::STATUS_ACTIVE)->count(),
            'onJob' => TechnicianProfile::where('is_on_job', true)->count(),
        ];

        return view('livewire.admin.technicians-manager', compact('technicians', 'stats'));
    }
}
