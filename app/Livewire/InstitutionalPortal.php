<?php

namespace App\Livewire;

use App\Models\Document;
use App\Models\Institution;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class InstitutionalPortal extends Component
{
    use WithFileUploads;

    public const STATES = [
        'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno', 'Cross River', 'Delta',
        'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT - Abuja', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina',
        'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers',
        'Sokoto', 'Taraba', 'Yobe', 'Zamfara',
    ];

    public const INST_TYPES = [
        'General Hospital', 'Teaching Hospital', 'Specialist Hospital', 'Clinic', 'Community Health Centre',
        'Pharmacy', 'Laboratory', 'Diagnostic Centre', 'Rehabilitation Centre', 'Research Institution',
        'NGO / Health Organisation', 'Other',
    ];

    public const SERVICES = [
        ['id' => 'equipment', 'icon' => '🩺', 'name' => 'Medical Equipment', 'desc' => 'Surgical & diagnostic devices'],
        ['id' => 'consumables', 'icon' => '🧤', 'name' => 'Consumables', 'desc' => 'PPE, syringes & disposables'],
        ['id' => 'pharmaceuticals', 'icon' => '💊', 'name' => 'Pharmaceuticals', 'desc' => 'WHO-approved drugs'],
        ['id' => 'specialist', 'icon' => '🔧', 'name' => 'Specialist Services', 'desc' => 'Repair, maintenance & calibration'],
        ['id' => 'consultations', 'icon' => '💬', 'name' => 'Consultations', 'desc' => 'Supply chain advisory & planning'],
        ['id' => 'reagents', 'icon' => '🧪', 'name' => 'Reagents & Chemicals', 'desc' => 'Lab reagents & assay kits'],
        ['id' => 'surgical', 'icon' => '🏥', 'name' => 'Surgical Equipment', 'desc' => 'Computer-assisted surgical systems'],
        ['id' => 'educational', 'icon' => '📚', 'name' => 'Educational Materials', 'desc' => 'Medical training resources'],
    ];

    public const SPECIALIST_OPTS = [
        'Medical Equipment Repair & Servicing', 'Preventive Maintenance Contracts', 'Equipment Calibration & Certification',
        'Spare Parts Sourcing & Replacement', 'On-site Technical Support', 'Equipment Refurbishment',
        'Warranty & After-sales Management', 'Cold Chain Equipment Servicing',
    ];

    public const CONSULT_OPTS = [
        'Supply Chain Assessment & Optimisation', 'Healthcare Facility Equipping Advisory',
        'Product Selection & Formulary Planning', 'Demand Forecasting & Stock Management',
        'Vendor & Supplier Evaluation', 'Procurement Planning & Budgeting', 'Regulatory & Import Compliance Advisory',
    ];

    public const REAGENT_OPTS = [
        'Haematology Reagents', 'Biochemistry Reagents', 'Microbiology Reagents',
        'Immunology / Serology Kits', 'Histopathology Chemicals', 'PCR / Molecular Kits',
    ];

    public const EDU_OPTS = [
        'Textbooks & Journals', 'Clinical Reference Guides', 'Training Manuals',
        'Anatomical Models', 'Simulation Equipment', 'Digital / e-Learning Resources',
    ];

    public const VOLUMES = [
        'Under ₦500k/month', '₦500k – ₦2M/month', '₦2M – ₦5M/month', '₦5M – ₦20M/month', 'Above ₦20M/month',
    ];

    public string $screen = 'welcome'; // welcome | form | confirm

    public int $step = 0;

    public string $refNo = '';

    // Step 0
    public string $instName = '';

    public string $instType = '';

    public string $cac = '';

    public string $year = '';

    public string $staff = '';

    public string $beds = '';

    // Step 1
    public string $state = '';

    public string $lga = '';

    public string $address = '';

    public string $contactName = '';

    public string $designation = '';

    public string $phone = '';

    public string $altPhone = '';

    public string $email = '';

    // Step 2
    public array $services = [];

    public array $specialistOpts = [];

    public array $consultOpts = [];

    public array $reagentOpts = [];

    public array $eduOpts = [];

    public string $volume = '';

    public string $notes = '';

    // Step 3
    public string $nafdac = '';

    public string $pcn = '';

    #[Validate('nullable|file|mimes:pdf,jpg,jpeg,png|max:5120')]
    public $cacDoc = null;

    #[Validate('nullable|file|mimes:pdf,jpg,jpeg,png|max:5120')]
    public $nafdacDoc = null;

    #[Validate('nullable|file|mimes:pdf,jpg,jpeg,png|max:5120')]
    public $otherDoc = null;

    public bool $confirmDocs = false;

    // Step 4
    public string $accountEmail = '';

    public string $password = '';

    public string $confirmPw = '';

    public bool $agreeTerms = false;

    public bool $agreePrivacy = false;

    public bool $newsletter = false;

    public function start(): void
    {
        $this->screen = 'form';
    }

    public function toggle(string $field, string $value): void
    {
        $this->$field = in_array($value, $this->$field, true)
            ? array_values(array_diff($this->$field, [$value]))
            : [...$this->$field, $value];
    }

    public function hasService(string $id): bool
    {
        return in_array($id, $this->services, true);
    }

    public function next(): void
    {
        $this->step = min($this->step + 1, 4);
    }

    public function back(): void
    {
        $this->step = max($this->step - 1, 0);
    }

    public function getCanSubmitProperty(): bool
    {
        return $this->agreeTerms && $this->agreePrivacy && $this->accountEmail !== '' && $this->password !== '';
    }

    public function submit(): void
    {
        if (! $this->canSubmit) {
            $this->dispatch('cart-toast', message: 'Please complete the account setup and agree to the terms.');

            return;
        }

        if ($this->password !== $this->confirmPw) {
            $this->dispatch('cart-toast', message: 'Passwords do not match.');

            return;
        }

        $institution = Institution::create([
            'inst_name' => $this->instName,
            'inst_type' => $this->instType,
            'cac' => $this->cac,
            'year_established' => $this->year ?: null,
            'staff_count' => $this->staff ?: null,
            'bed_capacity' => $this->beds ?: null,
            'state' => $this->state,
            'lga' => $this->lga,
            'address' => $this->address,
            'contact_name' => $this->contactName,
            'designation' => $this->designation,
            'phone' => $this->phone,
            'alt_phone' => $this->altPhone ?: null,
            'email' => $this->email,
            'services' => $this->services,
            'specialist_opts' => $this->specialistOpts,
            'consult_opts' => $this->consultOpts,
            'reagent_opts' => $this->reagentOpts,
            'edu_opts' => $this->eduOpts,
            'volume' => $this->volume ?: null,
            'notes' => $this->notes ?: null,
            'nafdac' => $this->nafdac ?: null,
            'pcn' => $this->pcn ?: null,
            'confirm_docs' => $this->confirmDocs,
            'account_email' => $this->accountEmail,
            'password_hash' => Hash::make($this->password),
        ]);

        foreach ([
            'cacDoc' => 'CAC',
            'nafdacDoc' => 'NAFDAC',
            'otherDoc' => 'OTHER',
        ] as $prop => $type) {
            if ($this->$prop) {
                $path = $this->$prop->store('institution-documents', 'public');
                Document::create([
                    'institution_id' => $institution->id,
                    'name' => $this->$prop->getClientOriginalName(),
                    'type' => $type,
                    'url' => \Illuminate\Support\Facades\Storage::url($path),
                ]);
            }
        }

        $this->refNo = 'CAD-INST-'.strtoupper(Str::random(6));
        $this->screen = 'confirm';
    }

    public function render()
    {
        return view('livewire.institutional-portal');
    }
}
