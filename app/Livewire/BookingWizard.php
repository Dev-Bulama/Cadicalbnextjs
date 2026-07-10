<?php

namespace App\Livewire;

use App\Models\Booking;
use Illuminate\Support\Str;
use Livewire\Component;

class BookingWizard extends Component
{
    public int $step = 1;

    // Step 1 — Service
    public ?string $service = null;

    public string $equipmentType = '';

    public string $issueType = '';

    public ?string $urgency = null;

    public string $consultType = '';

    public ?string $format = null;

    public string $notes = '';

    // Step 2 — Details
    public ?string $callerType = null;

    public string $firstName = '';

    public string $lastName = '';

    public string $orgName = '';

    public string $role = '';

    public string $phone = '';

    public string $email = '';

    public string $location = '';

    // Step 3 — Date/Time
    public ?string $bookingType = null;

    public string $prefDate = '';

    public ?string $selectedSlot = null;

    public string $callbackDate = '';

    public string $callWindow = '';

    public string $callbackPhone = '';

    // Result
    public ?string $bookingRef = null;

    public bool $isSubmitting = false;

    public function selectService(string $service): void
    {
        $this->service = $service;
        $this->urgency = null;
        $this->format = null;
    }

    public function next(): void
    {
        $error = match ($this->step) {
            1 => $this->validateStep1(),
            2 => $this->validateStep2(),
            3 => $this->validateStep3(),
            default => null,
        };

        if ($error) {
            $this->dispatch('cart-toast', message: $error);

            return;
        }

        $this->step = min($this->step + 1, 4);
    }

    public function back(): void
    {
        $this->step = max($this->step - 1, 1);
    }

    private function validateStep1(): ?string
    {
        return $this->service ? null : 'Please select a service type.';
    }

    private function validateStep2(): ?string
    {
        if (! $this->callerType) {
            return 'Please select whether you are booking as an individual or institution.';
        }
        if (! trim($this->firstName) || ! trim($this->lastName)) {
            return 'Please enter your first and last name.';
        }
        if (! trim($this->phone) || ! trim($this->email)) {
            return 'Please enter your phone number and email address.';
        }
        if (! trim($this->location)) {
            return 'Please enter your location or address.';
        }
        if ($this->callerType === 'institution' && ! trim($this->orgName)) {
            return 'Please enter your organisation name.';
        }

        return null;
    }

    private function validateStep3(): ?string
    {
        if (! $this->bookingType) {
            return 'Please choose a booking method.';
        }
        if ($this->bookingType === 'slot') {
            if (! $this->prefDate) {
                return 'Please select a preferred date.';
            }
            if (! $this->selectedSlot) {
                return 'Please select a time slot.';
            }
        }
        if ($this->bookingType === 'callback') {
            if (! $this->callbackDate) {
                return 'Please select a callback date.';
            }
            if (! $this->callWindow) {
                return 'Please select a callback time window.';
            }
            if (! trim($this->callbackPhone)) {
                return 'Please enter a phone number for the callback.';
            }
        }

        return null;
    }

    public function submit(): void
    {
        $this->isSubmitting = true;

        $booking = Booking::create([
            'service' => $this->service,
            'equipment_type' => $this->equipmentType ?: null,
            'issue_type' => $this->issueType ?: null,
            'urgency' => $this->urgency,
            'consult_type' => $this->consultType ?: null,
            'format' => $this->format,
            'caller_type' => $this->callerType,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'org_name' => $this->orgName ?: null,
            'role' => $this->role ?: null,
            'phone' => $this->phone,
            'email' => $this->email,
            'location' => $this->location,
            'booking_type' => $this->bookingType,
            'pref_date' => $this->prefDate ?: null,
            'selected_slot' => $this->selectedSlot,
            'callback_date' => $this->callbackDate ?: null,
            'call_window' => $this->callWindow ?: null,
            'callback_phone' => $this->callbackPhone ?: null,
            'notes' => $this->notes ?: null,
            'ref' => 'CAD-'.strtoupper(Str::random(6)),
        ]);

        $this->bookingRef = $booking->ref;
        $this->isSubmitting = false;
    }

    public function getSummaryProperty(): array
    {
        $svcMap = [
            'maintenance' => '🔧 Equipment Maintenance & Repair',
            'consultation' => '💬 Supply Consultation',
        ];

        $type = $this->service === 'maintenance' ? ($this->issueType ?: '—') : ($this->consultType ?: '—');
        $name = trim(collect([$this->firstName, $this->lastName])->filter()->implode(' ').($this->orgName ? " — {$this->orgName}" : '')) ?: '—';
        $contact = collect([$this->phone, $this->email])->filter()->implode(' · ') ?: '—';

        $timing = '—';
        if ($this->bookingType === 'slot') {
            $timing = collect([$this->prefDate, $this->selectedSlot])->filter()->implode(' at ') ?: 'Date selected';
        } elseif ($this->bookingType === 'callback') {
            $parts = collect([$this->callbackDate, $this->callWindow])->filter();
            $timing = $parts->isNotEmpty() ? 'Callback: '.$parts->implode(', ') : 'Callback requested';
        }

        return [
            'service' => $this->service ? ($svcMap[$this->service] ?? $this->service) : '—',
            'type' => $type,
            'name' => $name,
            'contact' => $contact,
            'location' => $this->location ?: '—',
            'timing' => $timing,
            'notes' => $this->notes ?: 'None',
        ];
    }

    public function render()
    {
        return view('livewire.booking-wizard');
    }
}
