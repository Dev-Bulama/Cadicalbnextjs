<?php

namespace App\Livewire\Technician;

use App\Models\TechnicianProfile;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ProfileManager extends Component
{
    #[Locked]
    public int $technicianId;

    public bool $isEditing = false;

    public string $first_name = '';

    public string $last_name = '';

    public string $phone = '';

    public string $city = '';

    public string $state = '';

    public string $base_address = '';

    public bool $is_available = true;

    public function mount(): void
    {
        $profile = TechnicianProfile::where('user_id', auth()->id())->firstOrFail();
        $this->technicianId = $profile->id;
        $this->fillFrom($profile);
    }

    private function fillFrom(TechnicianProfile $profile): void
    {
        $this->first_name = $profile->first_name;
        $this->last_name = $profile->last_name;
        $this->phone = $profile->phone;
        $this->city = $profile->city;
        $this->state = $profile->state;
        $this->base_address = (string) $profile->base_address;
        $this->is_available = $profile->is_available;
    }

    public function toggleAvailability(): void
    {
        $profile = TechnicianProfile::findOrFail($this->technicianId);
        $profile->update(['is_available' => ! $profile->is_available]);
        $this->is_available = $profile->is_available;
        $this->dispatch('cart-toast', message: $profile->is_available ? 'You are now available for jobs' : 'You are now unavailable');
    }

    public function save(): void
    {
        $data = $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'base_address' => 'nullable|string|max:255',
        ]);

        TechnicianProfile::whereKey($this->technicianId)->update($data);
        $this->isEditing = false;
        $this->dispatch('cart-toast', message: 'Profile updated');
    }

    public function render()
    {
        $profile = TechnicianProfile::findOrFail($this->technicianId);

        return view('livewire.technician.profile-manager', compact('profile'));
    }
}
