<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            'hero' => [
                'items' => [
                    [
                        'badge' => 'Medical Equipment Marketplace',
                        'headline' => 'Premium Medical Equipment for Hospitals, Clinics & Healthcare Providers',
                        'sub' => 'Source verified, certified medical devices from trusted suppliers across Nigeria.',
                        'cta1_label' => 'Shop Equipment', 'cta1_href' => '/products',
                        'cta2_label' => 'Request Quote', 'cta2_href' => '/booking',
                        'image' => 'mri.jpeg',
                        'gradient' => 'from-[#0D47A1]/90 via-[#1565C0]/80 to-[#1976D2]/70',
                    ],
                    [
                        'badge' => 'Medical Services',
                        'headline' => 'Installation, Maintenance & Repair Services Nationwide',
                        'sub' => 'Certified biomedical engineers at your facility within 24–48 hours.',
                        'cta1_label' => 'Book a Service', 'cta1_href' => '/booking',
                        'cta2_label' => 'View Service Plans', 'cta2_href' => '/booking',
                        'image' => 'test.jpeg',
                        'gradient' => 'from-[#004D40]/90 via-[#00695C]/80 to-[#00796B]/70',
                    ],
                    [
                        'badge' => 'Hospital Procurement',
                        'headline' => 'Bulk Procurement Solutions for Healthcare Institutions',
                        'sub' => 'Streamlined procurement with institutional pricing and dedicated account managers.',
                        'cta1_label' => 'Procurement Request', 'cta1_href' => '/institutional-portal',
                        'cta2_label' => 'Talk to Specialist', 'cta2_href' => '/booking',
                        'image' => 'images/home-image.jpeg',
                        'gradient' => 'from-[#1A237E]/90 via-[#283593]/80 to-[#303F9F]/70',
                    ],
                    [
                        'badge' => 'Supplier Marketplace',
                        'headline' => 'Trusted Medical Suppliers & Vendors Across Nigeria',
                        'sub' => 'Partner with verified suppliers to grow your medical equipment business.',
                        'cta1_label' => 'Become a Supplier', 'cta1_href' => '/auth/register',
                        'cta2_label' => 'Explore Products', 'cta2_href' => '/products',
                        'image' => 'images/Cadical.jpg',
                        'gradient' => 'from-[#4A148C]/90 via-[#6A1B9A]/80 to-[#7B1FA2]/70',
                    ],
                ],
            ],

            'categories' => [
                'meta' => ['eyebrow' => 'Browse by Category', 'heading' => 'Find the right equipment'],
                'items' => [
                    ['icon' => 'scan', 'name' => 'Imaging', 'sub' => 'Ultrasound, X-Ray, CT', 'href' => '/products?category=Imaging', 'color' => 'bg-blue-50 text-blue-600 border-blue-100'],
                    ['icon' => 'activity', 'name' => 'Diagnostics', 'sub' => 'Analyzers, POC, Reagents', 'href' => '/products?category=Diagnostics', 'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                    ['icon' => 'heart', 'name' => 'ICU Equipment', 'sub' => 'Ventilators, Monitors', 'href' => '/products?category=ICU', 'color' => 'bg-red-50 text-red-600 border-red-100'],
                    ['icon' => 'scissors', 'name' => 'Surgical', 'sub' => 'Instruments, Electrosurgery', 'href' => '/products?category=Surgery', 'color' => 'bg-violet-50 text-violet-600 border-violet-100'],
                    ['icon' => 'flask-conical', 'name' => 'Laboratory', 'sub' => 'Centrifuges, PCR, Incubators', 'href' => '/products?category=Laboratory', 'color' => 'bg-amber-50 text-amber-600 border-amber-100'],
                    ['icon' => 'monitor', 'name' => 'Monitoring', 'sub' => 'Vital Signs, ECG, Oximetry', 'href' => '/products?category=Monitoring', 'color' => 'bg-cyan-50 text-cyan-600 border-cyan-100'],
                    ['icon' => 'eye', 'name' => 'Dental', 'sub' => 'CBCT, Handpieces, Scalers', 'href' => '/products?category=Dental', 'color' => 'bg-pink-50 text-pink-600 border-pink-100'],
                    ['icon' => 'accessibility', 'name' => 'Rehabilitation', 'sub' => 'Wheelchairs, Therapy', 'href' => '/products?category=Rehabilitation', 'color' => 'bg-orange-50 text-orange-600 border-orange-100'],
                    ['icon' => 'package-2', 'name' => 'Consumables', 'sub' => 'Gloves, IV Sets, Dressings', 'href' => '/products?category=Consumables', 'color' => 'bg-slate-50 text-slate-600 border-slate-100'],
                ],
            ],

            'partners' => [
                'meta' => ['eyebrow' => 'Our Partners', 'heading' => 'Brands and manufacturers we work with'],
                'items' => [
                    ['name' => 'Mindray', 'logo' => '', 'website' => ''],
                    ['name' => 'Spectrum Diagnostics', 'logo' => '', 'website' => ''],
                    ['name' => 'HiMedia Laboratories', 'logo' => '', 'website' => ''],
                    ['name' => 'Accumedia', 'logo' => '', 'website' => ''],
                    ['name' => 'Juhel Nigeria Limited', 'logo' => '', 'website' => ''],
                    ['name' => 'Drugfield Pharmaceuticals', 'logo' => '', 'website' => ''],
                    ['name' => 'Dana Pharmaceuticals Limited', 'logo' => '', 'website' => ''],
                    ['name' => 'Emzor Pharmaceutical Industries Ltd.', 'logo' => '', 'website' => ''],
                    ['name' => 'Swiss Pharma Nigeria Ltd. (Swipha)', 'logo' => '', 'website' => ''],
                    ['name' => 'Sysmex', 'logo' => '', 'website' => ''],
                    ['name' => 'Roche', 'logo' => '', 'website' => ''],
                    ['name' => 'Biobase', 'logo' => '', 'website' => ''],
                    ['name' => 'Siemens', 'logo' => '', 'website' => ''],
                    ['name' => 'Sonoscape', 'logo' => '', 'website' => ''],
                    ['name' => 'Randox Laboratories', 'logo' => '', 'website' => ''],
                    ['name' => 'Agappe Diagnostics', 'logo' => '', 'website' => ''],
                    ['name' => 'Abbott Diagnostics', 'logo' => '', 'website' => ''],
                    ['name' => 'Thermo Fisher Scientific', 'logo' => '', 'website' => ''],
                    ['name' => 'SD Biosensor', 'logo' => '', 'website' => ''],
                    ['name' => 'Skytec', 'logo' => '', 'website' => ''],
                    ['name' => 'Biorapid', 'logo' => '', 'website' => ''],
                    ['name' => 'Olympus', 'logo' => '', 'website' => ''],
                    ['name' => 'Labtech', 'logo' => '', 'website' => ''],
                    ['name' => 'Bio-Rad Laboratories', 'logo' => '', 'website' => ''],
                    ['name' => 'Finlab', 'logo' => '', 'website' => ''],
                    ['name' => 'Promed', 'logo' => '', 'website' => ''],
                    ['name' => 'Accu-Chek', 'logo' => '', 'website' => ''],
                ],
            ],

            'portals' => [
                'meta' => ['eyebrow' => 'What We Offer', 'heading' => 'One platform. Three ways to access us.', 'sub' => "Whether you're a hospital, clinic, or individual — we have the right solution for your healthcare supply needs."],
                'items' => [
                    ['icon' => 'shopping-bag', 'badge' => 'For Everyone', 'title' => 'MediStore', 'sub' => 'Browse and order certified medical equipment online. Fast delivery anywhere in Nigeria.', 'color' => 'border-blue-200 bg-blue-50/50', 'iconColor' => 'bg-blue-100 text-blue-700', 'features' => ['NAFDAC-certified products', 'Secure card & transfer payment', 'Fast nationwide delivery', 'Order tracking & history'], 'cta_label' => 'Browse Products', 'cta_href' => '/products', 'accent' => '#1565C0', 'popular' => false],
                    ['icon' => 'building-2', 'badge' => 'For Institutions', 'title' => 'Supply Portal', 'sub' => 'Dedicated procurement platform for hospitals, clinics, and healthcare institutions.', 'color' => 'border-emerald-200 bg-emerald-50/50 ring-2 ring-emerald-200', 'iconColor' => 'bg-emerald-100 text-emerald-700', 'features' => ['Institutional bulk pricing', 'Auto-invoicing & delivery notes', 'Monthly supply agreements', 'Dedicated account manager'], 'cta_label' => 'Open Portal', 'cta_href' => '/institutional-portal', 'accent' => '#059669', 'popular' => true],
                    ['icon' => 'wrench', 'badge' => 'Physical & Virtual', 'title' => 'Services', 'sub' => 'Qualified engineers and biomedical technicians for equipment maintenance and repair.', 'color' => 'border-violet-200 bg-violet-50/50', 'iconColor' => 'bg-violet-100 text-violet-700', 'features' => ['Equipment repair & calibration', 'Preventive maintenance plans', 'Supply chain consultation', '24hr emergency response'], 'cta_label' => 'Book Service', 'cta_href' => '/booking', 'accent' => '#7C3AED', 'popular' => false],
                ],
            ],

            'featured_products' => [
                'meta' => ['eyebrow' => 'MediStore', 'heading' => 'Featured Products'],
                'items' => [],
            ],

            'why' => [
                'meta' => [
                    'eyebrow' => 'Why Cadical', 'heading' => "Reliable supply is not a luxury.",
                    'heading_accent' => "It's the baseline.",
                    'paragraph' => 'Most healthcare supply chains in Nigeria fail at the last mile — late deliveries, wrong products, no follow-up. We built Cadical to be the partner healthcare providers actually deserve.',
                    'image' => 'test.jpeg',
                    'badge_title' => 'Trusted by 50+ healthcare facilities',
                    'badge_sub' => 'Hospitals, clinics and labs across Nigeria',
                ],
                'items' => [
                    ['icon' => 'shield-check', 'title' => 'Certified Products Only', 'desc' => 'Every product we carry is NAFDAC-registered and manufacturer-verified. No counterfeits, no shortcuts.', 'color' => 'text-blue-600 bg-blue-50'],
                    ['icon' => 'zap', 'title' => 'Fast, Dependable Delivery', 'desc' => "Lagos same-day, nationwide within 48–72 hours. Your operations don't stop — we make sure of it.", 'color' => 'text-amber-600 bg-amber-50'],
                    ['icon' => 'users', 'title' => 'Relationship-Driven', 'desc' => 'A dedicated account manager, not a ticket system. We pick up the phone and we follow through.', 'color' => 'text-emerald-600 bg-emerald-50'],
                    ['icon' => 'award', 'title' => 'Healthcare Specialists', 'desc' => 'Our team has deep domain knowledge — biomedical engineers, procurement experts, and logistics specialists.', 'color' => 'text-violet-600 bg-violet-50'],
                ],
            ],

            'stats' => [
                'meta' => [],
                'items' => [
                    ['value' => 100, 'suffix' => '+', 'label' => 'Products Available', 'sub' => 'Across 9 categories'],
                    ['value' => 50, 'suffix' => '+', 'label' => 'Healthcare Clients', 'sub' => 'Hospitals & clinics'],
                    ['value' => 99, 'suffix' => '%', 'label' => 'Certified Products', 'sub' => 'NAFDAC & ISO compliant'],
                    ['value' => 24, 'suffix' => 'hr', 'label' => 'Service Response', 'sub' => 'Emergency & planned'],
                ],
            ],

            'services' => [
                'meta' => ['eyebrow' => 'Medical Services', 'heading' => "We don't just supply — we support.", 'sub' => 'From emergency repair to scheduled maintenance, our certified engineers keep your equipment operational.', 'cta_label' => 'Book Any Service'],
                'items' => [
                    ['icon' => 'wrench', 'title' => 'Equipment Repair', 'desc' => 'Fast on-site diagnosis and repair for all major brands — Philips, GE, Siemens, Mindray and more.', 'tags' => ['Imaging', 'ICU', 'Diagnostics'], 'color' => 'text-blue-600 bg-blue-50', 'cta' => 'Book Repair'],
                    ['icon' => 'settings', 'title' => 'Preventive Maintenance', 'desc' => 'Scheduled quarterly or annual maintenance contracts to keep your equipment running at peak performance.', 'tags' => ['Annual Plans', 'Quarterly', 'Reports'], 'color' => 'text-emerald-600 bg-emerald-50', 'cta' => 'Get a Plan'],
                    ['icon' => 'gauge', 'title' => 'Calibration', 'desc' => 'Certified calibration services for diagnostic and measurement equipment to regulatory standards.', 'tags' => ['ISO 9001', 'Certificates', 'Auditable'], 'color' => 'text-violet-600 bg-violet-50', 'cta' => 'Book Calibration'],
                    ['icon' => 'phone', 'title' => 'Supply Consultation', 'desc' => 'Expert procurement advice for hospitals, clinics and institutions on sourcing, budgeting, and contracts.', 'tags' => ['Free Consult', 'Procurement', 'Budget'], 'color' => 'text-amber-600 bg-amber-50', 'cta' => 'Talk to Us'],
                ],
            ],

            'tracking_showcase' => [
                'meta' => [
                    'eyebrow' => 'Real-Time Tracking', 'heading' => 'Always know where your order is.',
                    'paragraph' => 'From the moment you place an order to the point of delivery, you have full visibility into every step of your shipment. No calls required.',
                    'bullets' => ['Live status updates via SMS & email', 'Estimated delivery window', 'Delivery confirmation & proof'],
                    'cta_label' => 'Track Your Order',
                ],
                'items' => [
                    ['icon' => 'check-circle-2', 'label' => 'Order Placed', 'desc' => 'Order confirmed & payment processed'],
                    ['icon' => 'package', 'label' => 'Processing', 'desc' => 'Equipment verified and packed'],
                    ['icon' => 'truck', 'label' => 'Dispatched', 'desc' => 'In transit via certified carrier'],
                    ['icon' => 'map-pin', 'label' => 'Out for Delivery', 'desc' => 'Near your location'],
                    ['icon' => 'check-circle-2', 'label' => 'Delivered', 'desc' => 'Signed & received by your facility'],
                ],
            ],

            'process' => [
                'meta' => ['eyebrow' => 'How It Works', 'heading' => 'Simple from start to finish.', 'sub' => "Whether you're ordering supplies or booking a service — the process is seamless."],
                'items' => [
                    ['n' => '01', 'title' => 'Browse or Request', 'desc' => 'Explore the MediStore, use the institutional portal, or submit a custom equipment request.'],
                    ['n' => '02', 'title' => 'Verify & Confirm', 'desc' => 'Our compliance team confirms product availability, certification, and pricing for your order.'],
                    ['n' => '03', 'title' => 'Fast Dispatch', 'desc' => 'We pick, pack, and dispatch same-day (Lagos) or next-day (nationwide) with tracking.'],
                    ['n' => '04', 'title' => 'Track & Receive', 'desc' => 'Monitor your shipment in real-time. Receive delivery confirmation and invoice automatically.'],
                ],
            ],

            'testimonials' => [
                'meta' => ['eyebrow' => 'Testimonials', 'heading' => 'What healthcare professionals say'],
                'items' => [
                    ['name' => 'Dr. Amaka Okafor', 'role' => 'Medical Director', 'org' => "St. Raphael's Specialist Hospital, Lagos", 'text' => "Cadical has transformed how we procure medical equipment. The institutional portal is seamless — we raised a purchase order and had our ultrasound machines delivered in 48 hours. Exceptional service.", 'rating' => 5],
                    ['name' => 'Engr. Taiwo Balogun', 'role' => 'Biomedical Engineer', 'org' => 'University College Hospital, Ibadan', 'text' => 'Their maintenance engineers are genuinely knowledgeable. We had a Philips monitor down at 2am and their team was on-site by morning. That kind of reliability is rare in Nigeria.', 'rating' => 5],
                    ['name' => 'Mrs. Ngozi Eze', 'role' => 'Head of Procurement', 'org' => 'Redeemed Healthcare Centre, Abuja', 'text' => "We've used three other suppliers before Cadical. None of them offered the level of after-sales support and product certification documentation that Cadical provides as standard.", 'rating' => 5],
                    ['name' => 'Dr. Emmanuel Adeyemi', 'role' => 'Lab Director', 'org' => 'Synlab Nigeria, Lagos', 'text' => 'The diagnostic equipment we sourced through Cadical has been performing flawlessly for over a year. The calibration certificates and service reports were impeccable.', 'rating' => 5],
                ],
            ],

            'cta' => [
                'meta' => [
                    'badge' => 'Get Started Today',
                    'headline' => "Ready to work with Nigeria's most reliable medical supply partner?",
                    'sub' => 'Open a free account and get access to 100+ certified products, institutional pricing, and a dedicated support team.',
                    'button1_label' => 'Open Free Account', 'button1_href' => '/auth/register',
                    'button2_label' => 'Chat on WhatsApp', 'button2_href' => 'https://wa.me/2347076175550',
                ],
                'items' => [],
            ],

            'compliance' => [
                'meta' => ['eyebrow' => 'Compliance', 'heading' => 'Regulated. Registered.', 'heading_accent' => 'Auditable.', 'sub' => 'Every product and process at Cadical is backed by regulatory compliance.'],
                'items' => [
                    ['tag' => 'CAC', 'title' => 'Corporate Affairs Commission', 'detail' => 'RC 8969474 — legally registered entity'],
                    ['tag' => 'NAFDAC', 'title' => 'Drug & Device Compliance', 'detail' => 'Per-SKU product registration'],
                    ['tag' => 'NDPA', 'title' => 'Data Protection', 'detail' => 'privacy@cadical.com'],
                    ['tag' => 'ISO', 'title' => 'Quality Standards', 'detail' => 'ISO 13485 aligned processes'],
                ],
            ],

            'coverage' => [
                'meta' => ['eyebrow' => 'Nationwide Coverage', 'heading' => 'Where we', 'heading_accent' => 'deliver.', 'paragraph' => "Fast, reliable delivery of medical equipment and supplies across Nigeria's major cities and beyond.", 'image' => 'deliveries.png'],
                'items' => [
                    ['city' => 'Lagos', 'time' => 'Same-day'],
                    ['city' => 'Abuja', 'time' => '24–48hrs'],
                    ['city' => 'Port Harcourt', 'time' => '24–48hrs'],
                    ['city' => 'Kano', 'time' => '48–72hrs'],
                    ['city' => 'Ibadan', 'time' => '24–48hrs'],
                    ['city' => 'Enugu', 'time' => '48–72hrs'],
                ],
            ],
        ];

        foreach ($sections as $key => $content) {
            HomeSection::updateOrCreate(['section_key' => $key], ['content' => $content]);
        }

        $this->command->info('  Homepage content sections seeded ('.count($sections).')');
    }
}
