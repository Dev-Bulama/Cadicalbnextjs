<?php

namespace App\Livewire;

use App\Models\ServiceBooking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ServiceBookingWizard extends Component
{
    use WithFileUploads;

    public const SERVICE_TYPES = [
        ['value' => 'INSTALLATION', 'label' => 'Installation', 'desc' => 'New equipment setup', 'icon' => '🔧'],
        ['value' => 'PREVENTIVE_MAINTENANCE', 'label' => 'Preventive Maintenance', 'desc' => 'Scheduled upkeep', 'icon' => '🛡️'],
        ['value' => 'REPAIR', 'label' => 'Equipment Repair', 'desc' => 'Fix malfunctions', 'icon' => '🔩'],
        ['value' => 'EMERGENCY_REPAIR', 'label' => 'Emergency Repair', 'desc' => 'Urgent breakdown', 'icon' => '🚨'],
        ['value' => 'INSPECTION', 'label' => 'Inspection', 'desc' => 'Safety & compliance check', 'icon' => '🔍'],
        ['value' => 'CALIBRATION', 'label' => 'Calibration', 'desc' => 'Precision adjustment', 'icon' => '⚙️'],
        ['value' => 'UPGRADE', 'label' => 'Equipment Upgrade', 'desc' => 'Performance enhancement', 'icon' => '⬆️'],
        ['value' => 'RELOCATION', 'label' => 'Relocation', 'desc' => 'Move equipment safely', 'icon' => '📦'],
        ['value' => 'CONSULTATION', 'label' => 'Technical Consultation', 'desc' => 'Expert advice', 'icon' => '💬'],
        ['value' => 'WARRANTY_SERVICE', 'label' => 'Warranty Service', 'desc' => 'Under-warranty repair', 'icon' => '✅'],
    ];

    public const URGENCY_OPTS = [
        ['value' => 'ROUTINE', 'label' => 'Routine', 'desc' => 'Within 2 weeks', 'color' => 'text-slate-500'],
        ['value' => 'NORMAL', 'label' => 'Normal', 'desc' => 'Within 3–5 days', 'color' => 'text-blue-600'],
        ['value' => 'URGENT', 'label' => 'Urgent', 'desc' => 'Within 24–48 hours', 'color' => 'text-amber-600'],
        ['value' => 'EMERGENCY', 'label' => 'Emergency', 'desc' => 'Same day response', 'color' => 'text-red-600'],
    ];

    public const STEPS = [
        ['label' => 'Equipment', 'icon' => 'package'],
        ['label' => 'Service Type', 'icon' => 'wrench'],
        ['label' => 'Describe Issue', 'icon' => 'clipboard-list'],
        ['label' => 'Upload Media', 'icon' => 'camera'],
        ['label' => 'Schedule', 'icon' => 'calendar'],
        ['label' => 'Location', 'icon' => 'map-pin'],
        ['label' => 'Review', 'icon' => 'check-circle'],
    ];

    public int $step = 0;

    public bool $submitted = false;

    public string $bookingCode = '';

    public bool $loading = false;

    public string $equipmentName = '';

    public string $equipmentModel = '';

    public string $equipmentSerial = '';

    public string $equipmentBrand = '';

    public string $serviceType = '';

    public string $urgency = 'NORMAL';

    public string $issueDescription = '';

    public string $severity = '';

    public string $equipmentCondition = '';

    public string $siteReadiness = '';

    public string $electricalReq = '';

    public string $siteAddress = '';

    public string $siteCity = '';

    public string $siteState = '';

    public string $siteContact = '';

    public string $sitePhone = '';

    public string $preferredDate = '';

    public string $preferredTimeSlot = '';

    public string $alternateDate = '';

    public string $notes = '';

    /** @var array<\Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $photos = [];

    public function getIsInstallationProperty(): bool
    {
        return $this->serviceType === 'INSTALLATION';
    }

    public function getIsRepairProperty(): bool
    {
        return in_array($this->serviceType, ['REPAIR', 'EMERGENCY_REPAIR'], true);
    }

    public function removePhoto(int $index): void
    {
        unset($this->photos[$index]);
        $this->photos = array_values($this->photos);
    }

    public function next(): void
    {
        $error = match ($this->step) {
            0 => $this->equipmentName === '' ? 'Equipment name is required' : null,
            1 => $this->serviceType === '' ? 'Select a service type' : null,
            2 => $this->issueDescription === '' ? 'Describe the issue' : null,
            5 => (! $this->siteAddress || ! $this->siteCity || ! $this->siteState) ? 'Site address is required' : null,
            default => null,
        };

        if ($error) {
            $this->dispatch('cart-toast', message: $error);

            return;
        }

        $this->step = min($this->step + 1, count(self::STEPS) - 1);
    }

    public function back(): void
    {
        $this->step = max($this->step - 1, 0);
    }

    public function submit(): void
    {
        $this->loading = true;

        $dynamicFields = null;
        if ($this->isInstallation) {
            $dynamicFields = ['siteReadiness' => $this->siteReadiness, 'electricalReq' => $this->electricalReq];
        } elseif ($this->isRepair) {
            $dynamicFields = ['faultDescription' => $this->notes, 'severity' => $this->severity];
        }

        $imagePaths = collect($this->photos)->map(function ($photo) {
            $path = $photo->store('service-booking-photos', 'public');

            return Storage::url($path);
        })->all();

        $booking = ServiceBooking::create([
            'booking_code' => 'SB-'.strtoupper(Str::random(10)),
            'user_id' => auth()->id(),
            'equipment_name' => $this->equipmentName,
            'equipment_model' => $this->equipmentModel ?: null,
            'equipment_serial' => $this->equipmentSerial ?: null,
            'equipment_brand' => $this->equipmentBrand ?: null,
            'service_type' => $this->serviceType,
            'urgency' => $this->urgency,
            'issue_description' => $this->issueDescription,
            'severity' => $this->severity ?: null,
            'equipment_condition' => $this->equipmentCondition ?: null,
            'site_address' => $this->siteAddress,
            'site_city' => $this->siteCity,
            'site_state' => $this->siteState,
            'site_contact' => $this->siteContact ?: null,
            'site_phone' => $this->sitePhone ?: null,
            'dynamic_fields' => $dynamicFields,
            'preferred_date' => $this->preferredDate ?: null,
            'preferred_time_slot' => $this->preferredTimeSlot ?: null,
            'alternate_date' => $this->alternateDate ?: null,
            'images' => $imagePaths,
            'notes' => $this->notes ?: null,
            'status' => ServiceBooking::STATUS_BOOKED,
        ]);

        $booking->statusEvents()->create([
            'status' => ServiceBooking::STATUS_BOOKED,
            'message' => 'Service request submitted online',
        ]);

        $this->bookingCode = $booking->booking_code;
        $this->submitted = true;
        $this->loading = false;
    }

    public function getServiceTypeLabelProperty(): string
    {
        foreach (self::SERVICE_TYPES as $s) {
            if ($s['value'] === $this->serviceType) {
                return $s['label'];
            }
        }

        return $this->serviceType;
    }

    public function getUrgencyLabelProperty(): string
    {
        foreach (self::URGENCY_OPTS as $u) {
            if ($u['value'] === $this->urgency) {
                return $u['label'];
            }
        }

        return $this->urgency;
    }

    public function render()
    {
        return view('livewire.service-booking-wizard');
    }
}
