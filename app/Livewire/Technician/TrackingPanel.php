<?php

namespace App\Livewire\Technician;

use App\Models\ServiceJob;
use App\Models\TechnicianProfile;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TrackingPanel extends Component
{
    #[Locked]
    public int $technicianId;

    public bool $shareLocation;

    public function mount(): void
    {
        $profile = TechnicianProfile::where('user_id', auth()->id())->firstOrFail();
        $this->technicianId = $profile->id;
        $this->shareLocation = $profile->share_location;
    }

    public function toggleShareLocation(): void
    {
        $profile = TechnicianProfile::findOrFail($this->technicianId);
        $profile->update(['share_location' => ! $profile->share_location]);
        $this->shareLocation = $profile->share_location;
        $this->dispatch('cart-toast', message: $this->shareLocation ? 'Location sharing enabled' : 'Location sharing disabled');
    }

    public function render()
    {
        $activeJob = ServiceJob::with('booking')
            ->where('technician_id', $this->technicianId)
            ->whereIn('status', [ServiceJob::STATUS_EN_ROUTE, ServiceJob::STATUS_ON_SITE, ServiceJob::STATUS_IN_PROGRESS])
            ->latest()
            ->first();

        return view('livewire.technician.tracking-panel', compact('activeJob'));
    }
}
