<?php

namespace App\Livewire;

use App\Models\Supplier;
use Livewire\Component;

class SupplierRegisterWizard extends Component
{
    public const STATES = [
        'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno', 'Cross River', 'Delta',
        'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT (Abuja)', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina',
        'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers',
        'Sokoto', 'Taraba', 'Yobe', 'Zamfara',
    ];

    public const CATEGORIES = [
        'Medical Equipment', 'Surgical Equipment', 'Diagnostics', 'Pharmaceuticals',
        'Consumables', 'Laboratory Equipment', 'Patient Monitoring', 'Rehabilitation',
        'Emergency Equipment', 'Imaging Equipment', 'Dental Equipment', 'Other',
    ];

    public int $step = 0;

    public bool $submitted = false;

    public string $companyName = '';

    public string $contactName = '';

    public string $email = '';

    public string $phone = '';

    public string $altPhone = '';

    public string $website = '';

    public string $address = '';

    public string $city = '';

    public string $state = '';

    public string $description = '';

    public array $category = [];

    public string $cacNumber = '';

    public string $taxId = '';

    public string $nafdacNumber = '';

    public string $yearEstablished = '';

    public function toggleCategory(string $cat): void
    {
        $this->category = in_array($cat, $this->category, true)
            ? array_values(array_diff($this->category, [$cat]))
            : [...$this->category, $cat];
    }

    public function next(): void
    {
        if ($this->step === 0) {
            if (! $this->companyName || ! $this->contactName || ! $this->email || ! $this->phone || ! $this->state || ! $this->city || ! $this->address) {
                $this->dispatch('cart-toast', message: 'Please fill in all required fields');

                return;
            }
            if (empty($this->category)) {
                $this->dispatch('cart-toast', message: 'Select at least one supply category');

                return;
            }
        }

        $this->step = min($this->step + 1, 2);
    }

    public function back(): void
    {
        $this->step = max($this->step - 1, 0);
    }

    public function submit(): void
    {
        if (Supplier::where('email', $this->email)->exists()) {
            $this->dispatch('cart-toast', message: 'A supplier with this email already exists');

            return;
        }

        Supplier::create([
            'company_name' => $this->companyName,
            'contact_name' => $this->contactName,
            'email' => $this->email,
            'phone' => $this->phone,
            'alt_phone' => $this->altPhone ?: null,
            'website' => $this->website ?: null,
            'category' => $this->category,
            'description' => $this->description ?: null,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => 'Nigeria',
            'cac_number' => $this->cacNumber ?: null,
            'tax_id' => $this->taxId ?: null,
            'nafdac_number' => $this->nafdacNumber ?: null,
            'year_established' => $this->yearEstablished ?: null,
            'status' => Supplier::STATUS_PENDING,
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.supplier-register-wizard');
    }
}
