<?php

namespace App\Livewire\Admin;

use App\Models\Referral;
use Livewire\Component;
use Livewire\WithPagination;

class ReferralsManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $urgencyFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $referrals = Referral::when($this->search !== '', function ($q) {
            $q->where('client_facility_name', 'like', '%'.$this->search.'%')
                ->orWhere('referrer_full_name', 'like', '%'.$this->search.'%')
                ->orWhere('ref_id', 'like', '%'.$this->search.'%');
        })
            ->when($this->urgencyFilter !== '', fn ($q) => $q->where('urgency_level', $this->urgencyFilter))
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Referral::count(),
            'urgent' => Referral::where('urgency_level', 'URGENT')->count(),
        ];

        return view('livewire.admin.referrals-manager', compact('referrals', 'stats'));
    }
}
