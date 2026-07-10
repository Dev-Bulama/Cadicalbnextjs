<?php

namespace App\Livewire\Admin;

use App\Models\Institution;
use Livewire\Component;
use Livewire\WithPagination;

class InstitutionsManager extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $institutions = Institution::withCount(['rfqs', 'bulkOrders', 'maintenanceSchedules'])
            ->when($this->search !== '', function ($q) {
                $q->where('inst_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('state', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Institution::count(),
            'hospitals' => Institution::where('inst_type', 'HOSPITAL')->count(),
            'clinics' => Institution::where('inst_type', 'CLINIC')->count(),
        ];

        return view('livewire.admin.institutions-manager', compact('institutions', 'stats'));
    }
}
