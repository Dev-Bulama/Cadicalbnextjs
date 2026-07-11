<?php

namespace App\Livewire\Clinician;

use App\Models\Clinician;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ProfileManager extends Component
{
    #[Locked]
    public int $clinicianId;

    public bool $isEditing = false;

    public string $first_name = '';

    public string $last_name = '';

    public string $specialization = '';

    public int $years_of_experience = 0;

    public string $bio = '';

    public bool $is_available = true;

    public function mount(): void
    {
        $clinician = Clinician::where('user_id', auth()->id())->firstOrFail();
        $this->clinicianId = $clinician->id;
        $this->fillFrom($clinician);
    }

    private function fillFrom(Clinician $clinician): void
    {
        $this->first_name = $clinician->first_name;
        $this->last_name = $clinician->last_name;
        $this->specialization = $clinician->specialization;
        $this->years_of_experience = $clinician->years_of_experience;
        $this->bio = (string) $clinician->bio;
        $this->is_available = $clinician->is_available;
    }

    public function save(): void
    {
        $data = $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'bio' => 'nullable|string',
            'is_available' => 'boolean',
        ]);

        Clinician::whereKey($this->clinicianId)->update($data);
        $this->isEditing = false;
        $this->dispatch('cart-toast', message: 'Profile updated');
    }

    public function render()
    {
        $clinician = Clinician::with('user')->findOrFail($this->clinicianId);

        return view('livewire.clinician.profile-manager', compact('clinician'));
    }
}
