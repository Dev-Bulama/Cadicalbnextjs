<?php

namespace App\Livewire;

use App\Models\Referral;
use Illuminate\Support\Str;
use Livewire\Component;

class ReferralForm extends Component
{
    public const CATEGORIES = [
        ['icon' => '🩸', 'name' => 'Blood Grouping', 'desc' => 'ABO, Rh, Cross-match, Coombs'],
        ['icon' => '🧫', 'name' => 'Chemistry Kits', 'desc' => 'Liver, Renal, Lipid panels'],
        ['icon' => '🔬', 'name' => 'Rapid Tests', 'desc' => 'Malaria, HIV, HBsAg, Widal'],
        ['icon' => '💉', 'name' => 'ELISA Kits', 'desc' => 'HCV, HBsAg, HIV, Syphilis'],
        ['icon' => '🩺', 'name' => 'Medical Consumables', 'desc' => 'Gloves, syringes, lancets, tubes'],
        ['icon' => '📦', 'name' => 'Other Supplies', 'desc' => 'Specify in notes below'],
    ];

    public const TESTS = [
        'ABO + Rh Blood Grouping', 'Full Cross-matching', 'Direct Coombs Test (DAT)', 'Indirect Coombs Test (IAT)',
        'Malaria RDT (P.f/P.v)', 'HIV Rapid Test', 'HBsAg Rapid Test', 'Widal Agglutination',
        'Liver Function Tests (LFT)', 'Kidney Function Tests (KFT)', 'Lipid Profile', 'Blood Glucose (RBS/FBS)',
        'Full Blood Count (FBC)', 'Urinalysis', 'Pregnancy Test (hCG)', 'CRP / ASOT / RF',
    ];

    public const STATES = [
        'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno', 'Cross River', 'Delta',
        'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT (Abuja)', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina',
        'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers',
        'Sokoto', 'Taraba', 'Yobe', 'Zamfara',
    ];

    public int $currentStep = 0;

    public string $refId = '';

    // Step 1 — referrer
    public string $referrerFullName = '';

    public string $referrerDesignation = '';

    public string $referrerFacility = '';

    public string $referrerFacilityType = '';

    public string $referrerPhone = '';

    public string $referrerEmail = '';

    public string $referrerState = 'Kaduna';

    public string $referrerLGA = '';

    public string $referrerAddress = '';

    // Step 2 — client
    public string $clientFacilityName = '';

    public string $clientType = '';

    public string $clientContactPerson = '';

    public string $clientPhone = '';

    public string $clientEmail = '';

    public string $clientState = 'Kaduna';

    public string $clientLGA = '';

    public string $clientAddress = '';

    public string $reasonForRequest = '';

    // Step 3 — supply
    public array $supplyCategory = [];

    public array $specificTests = [];

    public string $urgencyLevel = '';

    public string $quantity = '';

    public string $deliveryMethod = '';

    public string $additionalNotes = '';

    // Step 4 — affiliate
    public string $affiliateId = '';

    public string $referredVia = '';

    public string $paymentPreference = '';

    public string $estimatedValue = '';

    public bool $consent = false;

    public bool $submitted = false;

    public function mount(): void
    {
        $this->refId = $this->generateId();
        $this->affiliateId = $this->generateId();
    }

    private function generateId(): string
    {
        return 'CAD-'.strtoupper(Str::random(8));
    }

    public function toggleCategory(string $name): void
    {
        $this->supplyCategory = in_array($name, $this->supplyCategory, true)
            ? array_values(array_diff($this->supplyCategory, [$name]))
            : [...$this->supplyCategory, $name];
    }

    public function toggleTest(string $name): void
    {
        $this->specificTests = in_array($name, $this->specificTests, true)
            ? array_values(array_diff($this->specificTests, [$name]))
            : [...$this->specificTests, $name];
    }

    public function next(): void
    {
        $error = match ($this->currentStep) {
            0 => (! $this->referrerFullName || ! $this->referrerDesignation || ! $this->referrerFacility || ! $this->referrerPhone)
                ? 'Please complete all required fields in this section.' : null,
            1 => (! $this->clientFacilityName || ! $this->clientType || ! $this->clientPhone || ! $this->reasonForRequest)
                ? 'Please complete all required fields in this section.' : null,
            2 => (empty($this->supplyCategory) || ! $this->urgencyLevel)
                ? 'Please select a supply category and urgency level.' : null,
            default => null,
        };

        if ($error) {
            $this->dispatch('cart-toast', message: $error);

            return;
        }

        $this->currentStep = min($this->currentStep + 1, 3);
    }

    public function prev(): void
    {
        $this->currentStep = max($this->currentStep - 1, 0);
    }

    public function submit(): void
    {
        if (! $this->consent) {
            $this->dispatch('cart-toast', message: 'Please confirm the consent declaration before submitting.');

            return;
        }

        Referral::create([
            'ref_id' => $this->refId,
            'referrer_full_name' => $this->referrerFullName,
            'referrer_designation' => $this->referrerDesignation,
            'referrer_facility' => $this->referrerFacility,
            'referrer_facility_type' => $this->referrerFacilityType,
            'referrer_phone' => $this->referrerPhone,
            'referrer_email' => $this->referrerEmail ?: null,
            'referrer_state' => $this->referrerState,
            'referrer_lga' => $this->referrerLGA ?: null,
            'referrer_address' => $this->referrerAddress ?: null,
            'client_facility_name' => $this->clientFacilityName,
            'client_type' => $this->clientType,
            'client_contact_person' => $this->clientContactPerson ?: null,
            'client_phone' => $this->clientPhone,
            'client_email' => $this->clientEmail ?: null,
            'client_state' => $this->clientState,
            'client_lga' => $this->clientLGA ?: null,
            'client_address' => $this->clientAddress ?: null,
            'reason_for_request' => $this->reasonForRequest,
            'supply_category' => $this->supplyCategory,
            'specific_tests' => $this->specificTests,
            'urgency_level' => $this->urgencyLevel,
            'quantity' => $this->quantity ?: null,
            'delivery_method' => $this->deliveryMethod ?: null,
            'additional_notes' => $this->additionalNotes ?: null,
            'affiliate_id' => $this->affiliateId ?: null,
            'referred_via' => $this->referredVia ?: null,
            'payment_preference' => $this->paymentPreference ?: null,
            'estimated_value' => $this->estimatedValue ?: null,
            'consent' => true,
        ]);

        $this->submitted = true;
    }

    public function resetForm(): void
    {
        $this->reset([
            'referrerFullName', 'referrerDesignation', 'referrerFacility', 'referrerFacilityType', 'referrerPhone',
            'referrerEmail', 'referrerLGA', 'referrerAddress', 'clientFacilityName', 'clientType', 'clientContactPerson',
            'clientPhone', 'clientEmail', 'clientLGA', 'clientAddress', 'reasonForRequest', 'supplyCategory',
            'specificTests', 'urgencyLevel', 'quantity', 'deliveryMethod', 'additionalNotes', 'referredVia',
            'paymentPreference', 'estimatedValue', 'consent', 'currentStep', 'submitted',
        ]);
        $this->referrerState = 'Kaduna';
        $this->clientState = 'Kaduna';
        $this->refId = $this->generateId();
        $this->affiliateId = $this->generateId();
    }

    public function render()
    {
        return view('livewire.referral-form');
    }
}
