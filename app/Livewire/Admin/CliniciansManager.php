<?php

namespace App\Livewire\Admin;

use App\Models\Clinician;
use Livewire\Component;

class CliniciansManager extends Component
{
    public string $search = '';

    public string $filter = 'all'; // all | verified | unverified

    public function verify(int $id): void
    {
        Clinician::whereKey($id)->update(['verified' => true]);
        $this->dispatch('cart-toast', message: 'Clinician verified');
    }

    public function reject(int $id): void
    {
        Clinician::findOrFail($id)->delete();
        $this->dispatch('cart-toast', message: 'Clinician application rejected');
    }

    public function render()
    {
        $clinicians = Clinician::with('user')
            ->when($this->search !== '', function ($q) {
                $q->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%')
                    ->orWhere('specialization', 'like', '%'.$this->search.'%');
            })
            ->when($this->filter === 'verified', fn ($q) => $q->where('verified', true))
            ->when($this->filter === 'unverified', fn ($q) => $q->where('verified', false))
            ->latest()
            ->get();

        return view('livewire.admin.clinicians-manager', compact('clinicians'));
    }
}
