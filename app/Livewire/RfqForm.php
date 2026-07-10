<?php

namespace App\Livewire;

use App\Models\Rfq;
use Illuminate\Support\Str;
use Livewire\Component;

class RfqForm extends Component
{
    public const CATEGORIES = [
        'Diagnostic Equipment', 'Surgical Equipment', 'Patient Monitoring',
        'Rehabilitation Equipment', 'Emergency Equipment', 'Pharmaceuticals',
        'Consumables', 'Laboratory Equipment', 'Imaging Equipment', 'Other',
    ];

    public string $contactName = '';

    public string $contactEmail = '';

    public string $contactPhone = '';

    public string $organization = '';

    public string $title = '';

    public string $description = '';

    public string $specifications = '';

    public string $quantity = '';

    public string $targetBudget = '';

    public string $currency = 'NGN';

    public string $deliveryDate = '';

    public string $deliveryAddress = '';

    public string $closingDate = '';

    public array $category = [];

    public bool $submitted = false;

    public ?string $rfqCode = null;

    public function toggleCategory(string $cat): void
    {
        if (in_array($cat, $this->category, true)) {
            $this->category = array_values(array_diff($this->category, [$cat]));
        } else {
            $this->category[] = $cat;
        }
    }

    public function submit(): void
    {
        $this->validate([
            'contactName' => 'required|string|max:255',
            'contactEmail' => 'required|email',
            'contactPhone' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        if (empty($this->category)) {
            $this->dispatch('cart-toast', message: 'Please select at least one category');

            return;
        }

        $rfq = Rfq::create([
            'rfq_code' => 'RFQ-'.strtoupper(Str::random(10)),
            'user_id' => auth()->id(),
            'contact_name' => $this->contactName,
            'contact_email' => $this->contactEmail,
            'contact_phone' => $this->contactPhone,
            'organization' => $this->organization ?: null,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'specifications' => $this->specifications ?: null,
            'quantity' => $this->quantity,
            'target_budget' => $this->targetBudget ?: null,
            'currency' => $this->currency,
            'delivery_date' => $this->deliveryDate ?: null,
            'delivery_address' => $this->deliveryAddress ?: null,
            'closing_date' => $this->closingDate ?: null,
            'status' => Rfq::STATUS_OPEN,
        ]);

        $this->rfqCode = $rfq->rfq_code;
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.rfq-form');
    }
}
