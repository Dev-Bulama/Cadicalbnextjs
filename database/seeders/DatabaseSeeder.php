<?php

namespace Database\Seeders;

use App\Models\AppNotification;
use App\Models\AuditLog;
use App\Models\BulkOrder;
use App\Models\Clinician;
use App\Models\Institution;
use App\Models\MaintenanceSchedule;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Referral;
use App\Models\Rfq;
use App\Models\RfqBid;
use App\Models\Service;
use App\Models\ServiceBooking;
use App\Models\ServiceJob;
use App\Models\ServiceStatusEvent;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\TechnicianProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    private const PASSWORD = 'Cadical@2026';

    public function run(): void
    {
        $this->command->info('Seeding Cadical database...');

        $users = $this->seedUsers();
        $productCount = $this->seedProducts();
        $this->seedTechnicianProfile($users['technician']);
        $this->seedSupplier($users['supplier']);
        $this->seedVendorSupplier($users['vendor']);
        $institutionId = $this->seedInstitution($users['hospital']);
        $this->seedOrders($users['customer']);
        $this->seedMaintenanceSchedule($users['hospital'], $institutionId);
        $this->seedServiceBooking($users['customer'], $users['technician']);
        $this->seedRfq($users['hospital'], $institutionId);
        $this->seedReferral();
        $this->seedServices();
        $this->seedClinician($users['clinician']);
        $this->seedBulkOrder($users['hospital'], $institutionId);
        $this->seedSupplierProducts();
        $this->seedNotifications($users);
        $this->seedAuditLogs($users);
        $this->call(HomeSectionSeeder::class);

        $this->command->info("Products: {$productCount}");
        $this->command->info('Seed complete. Password for all demo accounts: '.self::PASSWORD);
    }

    /** @return array<string, int> role => user id */
    private function seedUsers(): array
    {
        $roster = [
            ['email' => 'superadmin@cadical.com', 'name' => 'Super Admin', 'role' => User::ROLE_SUPER_ADMIN],
            ['email' => 'admin@cadical.com', 'name' => 'Admin User', 'role' => User::ROLE_ADMIN],
            ['email' => 'supplier@cadical.com', 'name' => 'MedTech Supply', 'role' => User::ROLE_SUPPLIER],
            ['email' => 'vendor@cadical.com', 'name' => 'Vendor Partner', 'role' => User::ROLE_VENDOR],
            ['email' => 'technician@cadical.com', 'name' => 'Field Tech', 'role' => User::ROLE_TECHNICIAN],
            ['email' => 'customer@cadical.com', 'name' => 'Jane Hospital', 'role' => User::ROLE_CUSTOMER],
            ['email' => 'hospital@cadical.com', 'name' => 'Lagos General', 'role' => User::ROLE_HOSPITAL],
            ['email' => 'freeuser@cadical.com', 'name' => 'Free User', 'role' => User::ROLE_FREE_USER],
            ['email' => 'clinician@cadical.com', 'name' => 'Dr. Ifeoma Balogun', 'role' => User::ROLE_CLINICIAN],
        ];

        $cities = ['Lagos', 'Abuja', 'Port Harcourt', 'Kano', 'Ibadan'];
        $states = ['Lagos', 'FCT', 'Rivers', 'Kano', 'Oyo'];
        $hashed = Hash::make(self::PASSWORD);

        $ids = [];
        foreach ($roster as $u) {
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'role' => $u['role'],
                    'email_verified_at' => now(),
                    'password' => $hashed,
                    'phone' => '+234'.random_int(8000000000, 8999999999),
                    'city' => $cities[array_rand($cities)],
                    'state' => $states[array_rand($states)],
                    'country' => 'Nigeria',
                ]
            );
            $ids[$u['role']] = $user->id;
            $this->command->info("  User: {$u['email']} ({$u['role']})");
        }

        return $ids;
    }

    private function seedProducts(): int
    {
        $products = [
            // IMAGING (12)
            ['IMG-001', 'GE Voluson E10 Ultrasound', 'Imaging', 45000000, 3, 'High-end 4D ultrasound system for OB/GYN'],
            ['IMG-002', 'Siemens Luminos dRF Fluoroscopy', 'Imaging', 72000000, 2, 'Digital radiography and fluoroscopy system'],
            ['IMG-003', 'Philips Affiniti 70 Ultrasound', 'Imaging', 38000000, 4, 'Shared-service ultrasound for cardiology and radiology'],
            ['IMG-004', 'Canon Aquilion 64-Slice CT Scanner', 'Imaging', 180000000, 1, '64-slice CT scanner for diagnostic imaging'],
            ['IMG-005', 'Mindray DC-70 Pro Ultrasound', 'Imaging', 22000000, 5, 'Premium diagnostic ultrasound with AI assistance'],
            ['IMG-006', 'Samsung HM70A Portable Ultrasound', 'Imaging', 8500000, 8, 'Portable ultrasound for point-of-care use'],
            ['IMG-007', 'Agfa DR 100 X-Ray System', 'Imaging', 15000000, 4, 'Direct digital radiography system'],
            ['IMG-008', 'Carestream DRX-Evolution Plus', 'Imaging', 28000000, 2, 'Ceiling-suspended digital radiography system'],
            ['IMG-009', 'Fujifilm FDR D-EVO II Detector', 'Imaging', 9800000, 6, 'Flat panel detector for DR systems'],
            ['IMG-010', 'Esaote MyLab X8 Ultrasound', 'Imaging', 18500000, 3, 'Ultrasound for musculoskeletal and vascular imaging'],
            ['IMG-011', 'Mindray Z60 Portable Ultrasound', 'Imaging', 6200000, 10, 'Portable color Doppler ultrasound'],
            ['IMG-012', 'Shimadzu Flexavision Fluoroscopy', 'Imaging', 55000000, 1, 'Large-format fluoroscopy with flat panel detector'],
            // DIAGNOSTICS (12)
            ['DX-001', 'Roche Cobas 6000 Analyzer', 'Diagnostics', 35000000, 2, 'Fully automated clinical chemistry and immunoassay'],
            ['DX-002', 'Abbott Alinity i Immunoassay', 'Diagnostics', 28000000, 3, 'Random access immunoassay analyzer'],
            ['DX-003', 'Sysmex XN-1000 Hematology Analyzer', 'Diagnostics', 12000000, 5, '5-part differential hematology analyzer'],
            ['DX-004', 'BioMerieux Vitek 2 Microbiology', 'Diagnostics', 22000000, 2, 'Automated microbial identification and AST'],
            ['DX-005', 'Beckman Coulter AU480 Chemistry', 'Diagnostics', 18500000, 3, 'Mid-volume clinical chemistry analyzer'],
            ['DX-006', 'Horiba Yumizen H500 Hematology', 'Diagnostics', 8500000, 8, '3-part differential hematology analyzer'],
            ['DX-007', 'Mindray BC-6800 Hematology', 'Diagnostics', 9800000, 6, '5-part differential automated hematology system'],
            ['DX-008', 'Diasorin Liaison XL Immunoassay', 'Diagnostics', 24000000, 2, 'CLIA-based random-access immunoassay system'],
            ['DX-009', 'Stago STA-R Max Coagulation', 'Diagnostics', 16000000, 3, 'Automated coagulation analyzer'],
            ['DX-010', 'Roche Accu-Chek Inform II POCT', 'Diagnostics', 450000, 25, 'Hospital glucose meter system'],
            ['DX-011', 'Abaxis Piccolo Xpress Chemistry', 'Diagnostics', 2800000, 12, 'Point-of-care comprehensive metabolic panel'],
            ['DX-012', 'Biosite Triage MeterPro Cardiac', 'Diagnostics', 3500000, 8, 'POC cardiac marker analyzer (troponin, BNP)'],
            // ICU (10)
            ['ICU-001', 'Dräger Evita V300 Ventilator', 'ICU', 18000000, 5, 'Advanced ICU ventilator with neonatology modes'],
            ['ICU-002', 'Hamilton G5 ICU Ventilator', 'ICU', 22000000, 3, 'Adaptive support ventilation for critical care'],
            ['ICU-003', 'Philips IntelliVue MX800 Monitor', 'ICU', 8500000, 8, 'High-acuity patient monitor for ICU'],
            ['ICU-004', 'Edwards Vigileo Hemodynamic Monitor', 'ICU', 6200000, 5, 'Minimally invasive cardiac output monitoring'],
            ['ICU-005', 'Fresenius Kabi Agilia VP Infusion', 'ICU', 850000, 30, 'Volumetric infusion pump with TCI module'],
            ['ICU-006', 'Mindray WATO EX-65 Anesthesia', 'ICU', 14000000, 4, 'Anesthesia machine with advanced ventilation'],
            ['ICU-007', 'Zoll E Series Defibrillator', 'ICU', 3500000, 8, 'Biphasic defibrillator/monitor with CPR feedback'],
            ['ICU-008', 'Natus NicView NICU Camera', 'ICU', 1200000, 12, 'NICU bedside camera for remote viewing'],
            ['ICU-009', 'Medtronic INVOS Cerebral Oximeter', 'ICU', 4500000, 5, 'Noninvasive regional cerebral oxygen monitoring'],
            ['ICU-010', 'GE Giraffe Warmer Incubator', 'ICU', 7800000, 4, 'Neonatal warmer/incubator combination'],
            // SURGERY (10)
            ['SRG-001', 'Karl Storz Hopkins II Laparoscope', 'Surgery', 12000000, 6, '30° 10mm laparoscope with HD optics'],
            ['SRG-002', 'Olympus VISERA 4K UHD System', 'Surgery', 38000000, 2, '4K endoscopy system with fluorescence imaging'],
            ['SRG-003', 'Ethicon Harmonic Ace+ Shears', 'Surgery', 850000, 20, 'Ultrasonic vessel sealing and dissection'],
            ['SRG-004', 'Steris System 1E Sterilizer', 'Surgery', 4500000, 3, 'Low-temperature liquid sterilization system'],
            ['SRG-005', 'Medtronic O-arm Surgical Imaging', 'Surgery', 95000000, 1, 'Intraoperative 3D/2D imaging system'],
            ['SRG-006', 'Stryker 1688 4K Camera System', 'Surgery', 22000000, 3, 'AIM 4K camera with fluorescence capability'],
            ['SRG-007', 'Erbe VIO 300 D Electrosurgery', 'Surgery', 6800000, 5, 'Advanced HF electrosurgery system'],
            ['SRG-008', 'Getinge HS66 Steam Sterilizer', 'Surgery', 8500000, 3, 'Steam sterilizer for surgical instruments'],
            ['SRG-009', 'Zimmer Biomet PowerPro Drill System', 'Surgery', 2200000, 8, 'Surgical power tool system for orthopedics'],
            ['SRG-010', 'DePuy Synthes Carbon Fiber Retractor', 'Surgery', 1500000, 5, 'Radiolucent spine retractor system'],
            // LABORATORY (10)
            ['LAB-001', 'Eppendorf Mastercycler X50a PCR', 'Laboratory', 4500000, 6, 'Gradient thermal cycler for molecular diagnostics'],
            ['LAB-002', 'Thermo Fisher Sorvall Legend X1R', 'Laboratory', 2800000, 8, 'Refrigerated benchtop centrifuge'],
            ['LAB-003', 'Hettich ROTINA 380R Centrifuge', 'Laboratory', 1850000, 10, 'Benchtop refrigerated centrifuge for blood processing'],
            ['LAB-004', 'Memmert INCOmed CO2 Incubator', 'Laboratory', 3200000, 5, 'CO2 incubator with IR sensor and direct heat'],
            ['LAB-005', 'BioRad T100 Thermal Cycler', 'Laboratory', 2400000, 8, 'Dual-block PCR thermal cycler'],
            ['LAB-006', 'Kern ABT 220-4M Analytical Balance', 'Laboratory', 480000, 15, '0.1mg resolution analytical balance'],
            ['LAB-007', 'Mettler Toledo ML204T Balance', 'Laboratory', 620000, 12, '1mg precision balance with draft shield'],
            ['LAB-008', 'Grant SUB Aqua Pro Water Bath', 'Laboratory', 380000, 18, 'Stable temperature water bath for sample prep'],
            ['LAB-009', 'Esco Airstream Class II BSC', 'Laboratory', 2900000, 4, 'Class II Type A2 biological safety cabinet'],
            ['LAB-010', 'Thermo Fisher 7000 Freezer -80C', 'Laboratory', 4200000, 3, 'Ultra-low temperature freezer for sample storage'],
            // CONSUMABLES (15)
            ['CON-001', 'BD Vacutainer EDTA Tubes 3mL', 'Consumables', 8500, 5000, 'EDTA anticoagulant blood collection tubes (box 100)'],
            ['CON-002', 'BD Syringe 5mL Luer Lock', 'Consumables', 4200, 8000, '5mL disposable syringe with Luer lock tip (box 100)'],
            ['CON-003', '3M Tegaderm Transparent Dressing', 'Consumables', 85000, 500, '10x12cm transparent film dressing for IV sites (box 100)'],
            ['CON-004', 'Kimberly-Clark Surgical Gloves 7.5', 'Consumables', 38000, 2000, 'Sterile latex surgical gloves (box 50 pairs)'],
            ['CON-005', 'Cardinal Health Nitrile Exam Gloves', 'Consumables', 22000, 5000, 'Powder-free nitrile examination gloves medium (box 100)'],
            ['CON-006', 'Smiths Medical Suction Catheter 14Fr', 'Consumables', 12000, 3000, 'Sterile flexible suction catheter (box 50)'],
            ['CON-007', 'Covidien SCD Compression Sleeves', 'Consumables', 35000, 800, 'Sequential compression device sleeves for DVT prevention'],
            ['CON-008', 'Braun Sterican Needle 21G x 1.5in', 'Consumables', 6500, 6000, 'Single-use hypodermic needle (box 100)'],
            ['CON-009', 'Hospira Normal Saline 0.9% 500mL', 'Consumables', 2500, 3000, 'Normal saline for IV infusion'],
            ['CON-010', 'Baxter Lactated Ringer 1000mL', 'Consumables', 3200, 2500, 'Balanced crystalloid IV solution'],
            ['CON-011', 'Molnlycke Mepilex Border 10x10cm', 'Consumables', 95000, 400, 'Self-adherent foam dressing for pressure ulcers (box 10)'],
            ['CON-012', 'Smith & Nephew Allevyn Gentle Border', 'Consumables', 88000, 350, 'Silicone-bordered foam dressing (box 10)'],
            ['CON-013', 'Natus OAE Disposable Probe Tips', 'Consumables', 45000, 1000, 'Disposable probe tips for OAE screeners (box 100)'],
            ['CON-014', 'Welch Allyn Otoscope Specula 4mm', 'Consumables', 18000, 1500, 'Disposable specula for reusable otoscope handles'],
            ['CON-015', 'ConvaTec Sur-Fit Natura Flange 45mm', 'Consumables', 55000, 600, 'Ostomy flange for two-piece systems (box 10)'],
            // MONITORING (10)
            ['MON-001', 'Philips MX40 Wearable Monitor', 'Monitoring', 4200000, 15, 'Wireless ambulatory patient monitor'],
            ['MON-002', 'Masimo Root Patient Platform', 'Monitoring', 3800000, 10, 'Multifunctional patient monitoring hub'],
            ['MON-003', 'Mindray BeneVision N19 Monitor', 'Monitoring', 2900000, 12, '19-inch touchscreen patient monitor'],
            ['MON-004', 'GE CARESCAPE B650 Monitor', 'Monitoring', 5500000, 8, 'Patient data module with advanced analytics'],
            ['MON-005', 'Welch Allyn Connex Vital Signs', 'Monitoring', 1200000, 20, 'Automated vital signs spot-check station'],
            ['MON-006', 'Nellcor PM10N Pulse Oximeter', 'Monitoring', 380000, 40, 'Handheld pulse oximeter with SpO2/PR'],
            ['MON-007', 'Mortara ELI 380 ECG Machine', 'Monitoring', 2100000, 8, '12-lead resting ECG system with interpretation'],
            ['MON-008', 'Spacelabs Qube Ambulatory BP Monitor', 'Monitoring', 950000, 15, '24-hour ambulatory blood pressure monitor'],
            ['MON-009', 'Nonin 3100 WristOx Pulse Oximeter', 'Monitoring', 280000, 30, 'Wrist-worn pulse oximeter for continuous monitoring'],
            ['MON-010', 'Draeger Infinity Delta Monitor', 'Monitoring', 3200000, 6, 'Modular bedside monitor for acute care'],
            // DENTAL (10)
            ['DNT-001', 'Planmeca ProMax 3D CBCT', 'Dental', 48000000, 2, 'Cone beam CT for dental and maxillofacial imaging'],
            ['DNT-002', 'Dentsply Sirona Cerec Primescan', 'Dental', 28000000, 3, 'Intraoral scanner for chairside CAD/CAM'],
            ['DNT-003', 'Acteon Satelec P5 Newtron Scaler', 'Dental', 850000, 15, 'Piezoelectric ultrasonic scaler with 5 presets'],
            ['DNT-004', 'Bien Air CA 1:5L Handpiece', 'Dental', 620000, 20, 'Contra-angle 1:5 speed-increasing handpiece'],
            ['DNT-005', 'Mectron Piezosurgery Touch', 'Dental', 4200000, 4, 'Piezoelectric bone surgery unit'],
            ['DNT-006', 'SDI Riva Self Cure GIC', 'Dental', 38000, 200, 'Self-curing glass ionomer cement (pack)'],
            ['DNT-007', '3M Filtek Supreme Ultra Composite', 'Dental', 45000, 300, 'Universal nanofilled composite restorative (syringe)'],
            ['DNT-008', 'Heraeus Kulzer Venus Pearl Composite', 'Dental', 35000, 250, 'Nano-hybrid composite anterior/posterior (syringe)'],
            ['DNT-009', 'Kerr Total Etch Etchant Gel 37%', 'Dental', 22000, 400, 'Phosphoric acid etching gel (pack of 10 syringes)'],
            ['DNT-010', 'Dentsply ProTaper Gold Rotary Files', 'Dental', 85000, 150, 'Endodontic rotary file system (pack of 6)'],
            // REHABILITATION (11)
            ['RHB-001', 'Biodex System 4 Pro Dynamometer', 'Rehabilitation', 18000000, 3, 'Isokinetic dynamometer for muscle testing'],
            ['RHB-002', 'BTL-6000 HIFU Physiotherapy', 'Rehabilitation', 8500000, 4, 'High intensity ultrasound for deep tissue therapy'],
            ['RHB-003', 'Enraf Nonius Sonopuls 492', 'Rehabilitation', 1200000, 8, 'Therapeutic ultrasound with combo therapy'],
            ['RHB-004', 'Medelec Synergy EMG/NCS Machine', 'Rehabilitation', 12000000, 2, 'Electromyography and nerve conduction system'],
            ['RHB-005', 'DJO DonJoy Reaction Web Knee Brace', 'Rehabilitation', 85000, 100, 'Lightweight open patella knee brace'],
            ['RHB-006', 'Patterson Medical Parallel Bars', 'Rehabilitation', 650000, 10, 'Height-adjustable parallel walking bars'],
            ['RHB-007', 'Permobil M3 Power Wheelchair', 'Rehabilitation', 5800000, 5, 'Power wheelchair with tilt-in-space and elevation'],
            ['RHB-008', 'Invacare Platinum Mobile Oxygen 3L', 'Rehabilitation', 380000, 20, 'Portable oxygen concentrator 3L/min continuous'],
            ['RHB-009', 'Stryker Medical-Surgical Stretcher', 'Rehabilitation', 1800000, 8, 'Transport stretcher with power head section'],
            ['RHB-010', 'Hausmann Adjustable Treatment Table', 'Rehabilitation', 420000, 12, 'Height-adjustable physical therapy table'],
            ['RHB-011', 'Natus Tympstar Pro Tympanometer', 'Rehabilitation', 2100000, 5, 'Clinical middle ear analyzer and audiometer'],
        ];

        foreach ($products as [$sku, $name, $category, $price, $stock, $description]) {
            Product::updateOrCreate(
                ['sku' => $sku],
                ['name' => $name, 'category' => $category, 'price' => $price, 'stock' => $stock, 'description' => $description]
            );
        }

        return count($products);
    }

    private function seedTechnicianProfile(int $techUserId): void
    {
        TechnicianProfile::updateOrCreate(
            ['user_id' => $techUserId],
            [
                'first_name' => 'Emeka',
                'last_name' => 'Okafor',
                'phone' => '+2348023456789',
                'specializations' => ['ULTRASOUND', 'X_RAY', 'ICU_EQUIPMENT', 'VENTILATORS'],
                'state' => 'Lagos',
                'city' => 'Ikeja',
                'base_address' => '14 Allen Avenue, Ikeja',
                'status' => TechnicianProfile::STATUS_ACTIVE,
                'is_available' => true,
                'is_on_job' => false,
                'rating' => 4.7,
                'total_jobs' => 142,
                'completed_jobs' => 138,
                'certifications' => ['CBET', 'ISO_13485', 'MDCE'],
                'years_of_experience' => 8,
            ]
        );
        $this->command->info('  Technician profile created');
    }

    private function seedSupplier(int $supplierUserId): void
    {
        Supplier::updateOrCreate(
            ['email' => 'supplier@cadical.com'],
            [
                'user_id' => $supplierUserId,
                'company_name' => 'MedTech Supply Nigeria Ltd',
                'contact_name' => 'Chukwuemeka Obi',
                'cac_number' => 'RC-1234567',
                'tax_id' => 'TIN-987654321',
                'phone' => '+2348012345678',
                'website' => 'https://medtechsupply.ng',
                'address' => '7 Broad Street',
                'city' => 'Lagos Island',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'category' => ['Imaging', 'Diagnostics', 'ICU', 'Monitoring'],
                'description' => 'Leading supplier of diagnostic and imaging equipment across West Africa',
                'status' => Supplier::STATUS_APPROVED,
                'is_active' => true,
                'rating' => 4.5,
                'total_orders' => 87,
            ]
        );
        $this->command->info('  Supplier profile created');
    }

    private function seedVendorSupplier(int $vendorUserId): void
    {
        Supplier::updateOrCreate(
            ['email' => 'vendor@cadical.com'],
            [
                'user_id' => $vendorUserId,
                'company_name' => 'Vendor Partner Supplies Ltd',
                'contact_name' => 'Amina Suleiman',
                'cac_number' => 'RC-7654321',
                'tax_id' => 'TIN-123456789',
                'phone' => '+2348098765432',
                'address' => '12 Independence Way',
                'city' => 'Kaduna',
                'state' => 'Kaduna',
                'country' => 'Nigeria',
                'category' => ['Consumables', 'Laboratory Equipment'],
                'description' => 'Regional distributor of lab consumables and diagnostic supplies.',
                'status' => Supplier::STATUS_APPROVED,
                'is_active' => true,
                'rating' => 4.2,
                'total_orders' => 34,
            ]
        );
        $this->command->info('  Vendor supplier profile created');
    }

    private function seedInstitution(int $hospitalUserId): int
    {
        $institution = Institution::firstOrCreate(
            ['inst_name' => 'Lagos General Hospital'],
            [
                'inst_type' => 'PUBLIC_HOSPITAL',
                'cac' => 'LGH-2001-0042',
                'state' => 'Lagos',
                'lga' => 'Lagos Island',
                'address' => '1 Hospital Road, Lagos Island, Lagos',
                'contact_name' => 'Dr. Adaeze Nwosu',
                'designation' => 'Medical Director',
                'phone' => '+2341234567890',
                'email' => 'hospital@cadical.com',
                'account_email' => 'hospital@cadical.com',
                'password_hash' => Hash::make(self::PASSWORD),
                'bed_capacity' => 350,
                'services' => ['Emergency', 'Surgery', 'Radiology', 'Laboratory'],
                'specialist_opts' => ['Cardiology', 'Neurology', 'Oncology'],
                'consult_opts' => ['Outpatient', 'Telemedicine'],
                'reagent_opts' => ['Chemistry', 'Hematology'],
                'edu_opts' => ['Training', 'Internship'],
            ]
        );
        $this->command->info('  Institution created');

        return $institution->id;
    }

    private function seedOrders(int $customerUserId): void
    {
        if (Order::where('user_id', $customerUserId)->exists()) {
            return;
        }

        $products = Product::limit(5)->get();
        if ($products->isEmpty()) {
            return;
        }

        $statuses = [Order::STATUS_PENDING, Order::STATUS_PROCESSING, Order::STATUS_DELIVERED];

        foreach ($statuses as $i => $status) {
            $product = $products[$i % $products->count()];
            $qty = random_int(1, 3);

            $order = Order::create([
                'user_id' => $customerUserId,
                'total_amount' => $product->price * $qty,
                'status' => $status,
                'tracking_code' => 'CAD-'.strtoupper(Str::random(10)),
                'shipping_address' => '15 Victoria Island, Lagos, Nigeria',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $product->price,
            ]);
        }
        $this->command->info('  Sample orders created');
    }

    private function seedMaintenanceSchedule(int $hospitalUserId, int $institutionId): void
    {
        if (MaintenanceSchedule::where('institution_id', $institutionId)->exists()) {
            return;
        }

        MaintenanceSchedule::create([
            'schedule_code' => 'SCH-'.strtoupper(Str::random(8)),
            'user_id' => $hospitalUserId,
            'institution_id' => $institutionId,
            'equipment_name' => 'GE Voluson E10 Ultrasound',
            'equipment_model' => 'Voluson E10',
            'equipment_serial' => 'GE-ULT-2024-001',
            'service_type' => 'PREVENTIVE_MAINTENANCE',
            'frequency' => 'QUARTERLY',
            'site_address' => '1 Hospital Road, Lagos Island, Lagos',
            'site_state' => 'Lagos',
            'last_completed_at' => '2026-02-15',
            'next_due_date' => '2026-05-15',
            'notes' => 'Quarterly preventive maintenance per manufacturer spec',
        ]);
        $this->command->info('  Maintenance schedule created');
    }

    private function seedServiceBooking(int $customerUserId, int $techUserId): void
    {
        if (ServiceBooking::where('user_id', $customerUserId)->exists()) {
            return;
        }

        $techProfile = TechnicianProfile::where('user_id', $techUserId)->first();
        if (! $techProfile) {
            return;
        }

        $booking = ServiceBooking::create([
            'booking_code' => 'BKG-'.strtoupper(Str::random(8)),
            'user_id' => $customerUserId,
            'service_type' => 'REPAIR',
            'urgency' => 'URGENT',
            'equipment_name' => 'Philips IntelliVue MX800 Monitor',
            'equipment_model' => 'MX800',
            'equipment_serial' => 'PH-MON-2023-445',
            'issue_description' => 'Screen flickering and intermittent alarm failure during ICU monitoring',
            'site_address' => '24 Adeola Odeku Street, Victoria Island',
            'site_city' => 'Lagos',
            'site_state' => 'Lagos',
            'site_contact' => 'Dr. Amaka Eze',
            'site_phone' => '+2348023456001',
            'preferred_date' => '2026-05-28',
            'status' => 'TECHNICIAN_ASSIGNED',
            'assigned_tech_id' => $techProfile->id,
        ]);

        foreach ([
            ['status' => 'BOOKED', 'message' => 'Service request submitted online', 'notes' => 'Customer submitted via web portal'],
            ['status' => 'APPROVED', 'message' => 'Request reviewed and approved by admin', 'notes' => 'Approved within SLA window'],
            ['status' => 'TECHNICIAN_ASSIGNED', 'message' => 'Technician Emeka Okafor assigned', 'notes' => 'Auto-assigned based on specialization and proximity'],
        ] as $event) {
            ServiceStatusEvent::create(array_merge($event, ['booking_id' => $booking->id]));
        }

        ServiceJob::firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'job_code' => 'JOB-'.strtoupper(Str::random(8)),
                'technician_id' => $techProfile->id,
                'status' => ServiceJob::STATUS_ASSIGNED,
                'scheduled_at' => now()->addDays(2),
                'estimated_duration' => 120,
            ]
        );

        $this->command->info('  Service booking + job created');
    }

    /** @param array<string, int> $users */
    private function seedNotifications(array $users): void
    {
        foreach ([
            ['type' => 'system', 'title' => 'New Supplier Registration', 'message' => 'MedTech Supply Nigeria Ltd has submitted KYC documents for review.', 'action_url' => '/admin/suppliers', 'is_read' => false],
            ['type' => 'service', 'title' => 'Service Job Completed', 'message' => 'Technician Emeka Okafor has completed the repair on Philips MX800.', 'action_url' => '/admin/service-jobs', 'is_read' => true],
            ['type' => 'maintenance', 'title' => 'Maintenance Overdue Alert', 'message' => 'GE Voluson E10 at Lagos General Hospital is overdue for quarterly maintenance.', 'action_url' => '/admin/maintenance', 'is_read' => false],
        ] as $notif) {
            AppNotification::create(array_merge($notif, ['user_id' => $users['admin']]));
        }

        foreach ([
            ['type' => 'service', 'title' => 'New Job Assigned', 'message' => 'You have been assigned to repair a Philips IntelliVue MX800 Monitor at Victoria Island.', 'action_url' => '/technician/jobs', 'is_read' => false],
            ['type' => 'system', 'title' => 'Welcome to Cadical Tech', 'message' => 'Your technician account is active. Keep your availability up to date to receive job offers.', 'action_url' => null, 'is_read' => true],
        ] as $notif) {
            AppNotification::create(array_merge($notif, ['user_id' => $users['technician']]));
        }

        foreach ([
            ['type' => 'system', 'title' => 'Bulk Order Received', 'message' => 'Lagos General Hospital placed a bulk order for ventilators and surgical gloves.', 'action_url' => '/supplier/orders', 'is_read' => false],
            ['type' => 'system', 'title' => 'Application Approved', 'message' => 'Your supplier application has been approved. Welcome to the Cadical marketplace.', 'action_url' => null, 'is_read' => true],
        ] as $notif) {
            AppNotification::create(array_merge($notif, ['user_id' => $users['supplier']]));
        }

        foreach ([
            ['type' => 'system', 'title' => 'Profile Verified', 'message' => 'Your clinician profile has been verified. You can now be listed as available.', 'action_url' => '/clinician/profile', 'is_read' => false],
        ] as $notif) {
            AppNotification::create(array_merge($notif, ['user_id' => $users['clinician']]));
        }

        $this->command->info('  Sample notifications created');
    }

    private function seedRfq(int $hospitalUserId, int $institutionId): void
    {
        $rfq = Rfq::firstOrCreate(
            ['rfq_code' => 'RFQ-2026-0001'],
            [
                'user_id' => $hospitalUserId,
                'institution_id' => $institutionId,
                'contact_name' => 'Dr. Adaeze Nwosu',
                'contact_email' => 'hospital@cadical.com',
                'contact_phone' => '+2341234567890',
                'organization' => 'Lagos General Hospital',
                'title' => 'Bulk ICU Ventilators and Patient Monitors',
                'description' => 'Requesting quotations for 10 ICU ventilators and 15 patient monitors for a new critical care wing.',
                'category' => ['ICU', 'Monitoring'],
                'quantity' => 25,
                'target_budget' => 250000000,
                'delivery_address' => '1 Hospital Road, Lagos Island, Lagos',
                'status' => Rfq::STATUS_OPEN,
                'closing_date' => now()->addDays(14),
            ]
        );

        $supplier = Supplier::first();
        if ($supplier && ! RfqBid::where('rfq_id', $rfq->id)->where('supplier_id', $supplier->id)->exists()) {
            RfqBid::create([
                'rfq_id' => $rfq->id,
                'supplier_id' => $supplier->id,
                'unit_price' => 9500000,
                'total_price' => 237500000,
                'lead_time_days' => 45,
                'notes' => 'Includes 2-year warranty and on-site installation training.',
            ]);
        }

        $this->command->info('  RFQ + bid created');
    }

    private function seedReferral(): void
    {
        Referral::firstOrCreate(
            ['ref_id' => 'REF-2026-0001'],
            [
                'referrer_full_name' => 'Dr. Bola Adekunle',
                'referrer_designation' => 'General Practitioner',
                'referrer_facility' => 'Sunrise Family Clinic',
                'referrer_facility_type' => 'CLINIC',
                'referrer_phone' => '+2348034567890',
                'referrer_email' => 'bola.adekunle@sunriseclinic.ng',
                'referrer_state' => 'Lagos',
                'referrer_lga' => 'Ikeja',
                'client_facility_name' => 'Lagos General Hospital',
                'client_type' => 'HOSPITAL',
                'client_contact_person' => 'Dr. Adaeze Nwosu',
                'client_phone' => '+2341234567890',
                'client_state' => 'Lagos',
                'client_lga' => 'Lagos Island',
                'reason_for_request' => 'Patient requires advanced cardiology diagnostics unavailable at referring clinic.',
                'supply_category' => ['Diagnostics', 'Cardiology'],
                'urgency_level' => 'URGENT',
                'quantity' => '1 patient',
                'delivery_method' => 'IN_PERSON',
                'consent' => true,
            ]
        );
        $this->command->info('  Referral created');
    }

    private function seedServices(): void
    {
        $services = [
            ['name' => 'Consultations', 'description' => 'Expert medical consultations across crucial health departments with international collaboration.', 'category' => Service::CATEGORY_CONSULTATIONS, 'icon' => 'message-circle', 'order' => 1],
            ['name' => 'Pharmaceuticals', 'description' => 'Latest WHO-approved drugs and medical equipment with 100% efficacy assurance.', 'category' => Service::CATEGORY_PHARMACEUTICALS, 'icon' => 'pill', 'order' => 2],
            ['name' => 'Surgical Equipment', 'description' => 'Advanced surgical devices including computer-assisted and robotically-assisted systems.', 'category' => Service::CATEGORY_SURGICAL_EQUIPMENT, 'icon' => 'scissors', 'order' => 3],
            ['name' => 'Diagnostics', 'description' => '3D radiological imaging and laboratory investigations with precision and sensitivity.', 'category' => Service::CATEGORY_DIAGNOSTICS, 'icon' => 'scan', 'order' => 4],
            ['name' => 'Rehabilitation', 'description' => 'Physical therapy, occupational therapy, and sports medicine rehabilitation.', 'category' => Service::CATEGORY_REHABILITATION, 'icon' => 'activity', 'order' => 5],
            ['name' => 'Emergency Services', 'description' => 'Quick response emergency medical services with advanced life support capabilities.', 'category' => Service::CATEGORY_EMERGENCY, 'icon' => 'siren', 'order' => 6],
            ['name' => 'Cosmetics', 'description' => 'Latest cosmetology and dermatology services with skin care solutions.', 'category' => Service::CATEGORY_COSMETICS, 'icon' => 'sparkles', 'order' => 7],
            ['name' => 'Referrals', 'description' => 'Professional referral services connecting you with expert healthcare networks.', 'category' => Service::CATEGORY_REFERRALS, 'icon' => 'handshake', 'order' => 8],
        ];

        foreach ($services as $s) {
            Service::updateOrCreate(['name' => $s['name']], $s);
        }
        $this->command->info('  Services catalog seeded');
    }

    private function seedClinician(int $clinicianUserId): void
    {
        Clinician::updateOrCreate(
            ['user_id' => $clinicianUserId],
            [
                'first_name' => 'Ifeoma',
                'last_name' => 'Balogun',
                'specialization' => 'Cardiology',
                'license_number' => 'MDCN-'.strtoupper(Str::random(6)),
                'years_of_experience' => 11,
                'bio' => 'Consultant cardiologist with over a decade of experience in interventional cardiology and telemedicine consultations across Nigerian tertiary hospitals.',
                'verified' => true,
                'is_available' => true,
            ]
        );
        $this->command->info('  Clinician profile created');
    }

    private function seedSupplierProducts(): void
    {
        $supplier = Supplier::first();
        if (! $supplier) {
            return;
        }

        $products = [
            ['name' => 'Ventilator Unit X500', 'category' => 'ICU', 'unit_price' => 240000, 'stock' => 6, 'is_approved' => true],
            ['name' => 'Surgical Gloves (100 pcs)', 'category' => 'Consumables', 'unit_price' => 1500, 'stock' => 2, 'is_approved' => true],
            ['name' => 'Blood Pressure Monitor', 'category' => 'Monitoring', 'unit_price' => 25000, 'stock' => 14, 'is_approved' => false],
        ];

        foreach ($products as $p) {
            SupplierProduct::updateOrCreate(
                ['supplier_id' => $supplier->id, 'name' => $p['name']],
                array_merge($p, ['description' => $p['name'].' — supplied via Cadical marketplace.'])
            );
        }
        $this->command->info('  Supplier products created');
    }

    private function seedBulkOrder(int $hospitalUserId, int $institutionId): void
    {
        $supplier = Supplier::first();
        if (! $supplier || BulkOrder::where('supplier_id', $supplier->id)->exists()) {
            return;
        }

        BulkOrder::create([
            'bulk_code' => 'BLK-'.strtoupper(Str::random(8)),
            'user_id' => $hospitalUserId,
            'institution_id' => $institutionId,
            'supplier_id' => $supplier->id,
            'contact_name' => 'Dr. Adaeze Nwosu',
            'contact_email' => 'hospital@cadical.com',
            'contact_phone' => '+2341234567890',
            'organization' => 'Lagos General Hospital',
            'items' => [
                ['name' => 'Ventilator Unit X500', 'qty' => 2, 'unit_price' => 240000],
                ['name' => 'Surgical Gloves (100 pcs)', 'qty' => 50, 'unit_price' => 1500],
            ],
            'total_amount' => 555000,
            'discount_percent' => 5,
            'final_amount' => 527250,
            'delivery_address' => '1 Hospital Road, Lagos Island, Lagos',
            'status' => BulkOrder::STATUS_PROCESSING,
        ]);
        $this->command->info('  Bulk order created');
    }

    /** @param array<string, int> $users */
    private function seedAuditLogs(array $users): void
    {
        $firstProduct = Product::first();

        AuditLog::create([
            'user_id' => $users['superadmin'],
            'user_email' => 'superadmin@cadical.com',
            'user_role' => User::ROLE_SUPER_ADMIN,
            'action' => 'login',
            'entity' => 'user',
            'entity_id' => (string) $users['superadmin'],
            'ip_address' => '197.210.55.1',
        ]);

        AuditLog::create([
            'user_id' => $users['admin'],
            'user_email' => 'admin@cadical.com',
            'user_role' => User::ROLE_ADMIN,
            'action' => 'approve',
            'entity' => 'supplier',
            'ip_address' => '105.113.22.8',
        ]);

        AuditLog::create([
            'user_id' => $users['admin'],
            'user_email' => 'admin@cadical.com',
            'user_role' => User::ROLE_ADMIN,
            'action' => 'update',
            'entity' => 'product',
            'entity_id' => $firstProduct ? (string) $firstProduct->id : null,
            'ip_address' => '105.113.22.8',
        ]);

        $this->command->info('  Audit logs created');
    }
}
