<?php

namespace App\Livewire\Supplier;

use App\Models\Supplier;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ProfileManager extends Component
{
    #[Locked]
    public int $supplierId;

    public bool $isEditing = false;

    public string $company_name = '';

    public string $contact_name = '';

    public string $phone = '';

    public string $alt_phone = '';

    public string $website = '';

    public string $address = '';

    public string $city = '';

    public string $state = '';

    public string $description = '';

    public function mount(): void
    {
        $supplier = Supplier::where('user_id', auth()->id())->firstOrFail();
        $this->supplierId = $supplier->id;
        $this->fillFrom($supplier);
    }

    private function fillFrom(Supplier $supplier): void
    {
        $this->company_name = $supplier->company_name;
        $this->contact_name = $supplier->contact_name;
        $this->phone = $supplier->phone;
        $this->alt_phone = (string) $supplier->alt_phone;
        $this->website = (string) $supplier->website;
        $this->address = $supplier->address;
        $this->city = $supplier->city;
        $this->state = $supplier->state;
        $this->description = (string) $supplier->description;
    }

    public function save(): void
    {
        $data = $this->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'alt_phone' => 'nullable|string|max:30',
            'website' => 'nullable|url|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        Supplier::whereKey($this->supplierId)->update($data);
        $this->isEditing = false;
        $this->dispatch('cart-toast', message: 'Profile updated');
    }

    public function render()
    {
        $supplier = Supplier::findOrFail($this->supplierId);

        return view('livewire.supplier.profile-manager', compact('supplier'));
    }
}
