<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            'about' => [
                'meta' => [
                    'hero_badge' => 'About Cadical',
                    'hero_heading' => 'Delivering Healthcare Solutions You Can Rely On',
                    'hero_paragraph' => 'Cadical bridges the gap between high-quality health products and skilled medical professionals. We empower healthcare facilities and organizations with reliable solutions that improve patient care and operational efficiency.',
                    'hero_cta1_label' => 'Partner With Us', 'hero_cta1_href' => '/contact',
                    'hero_cta2_label' => 'Explore Services', 'hero_cta2_href' => '/services',
                    'what_heading' => 'What We Do',
                    'what_sub' => 'Comprehensive healthcare solutions tailored for modern medical institutions and organizations.',
                    'cta_heading' => 'Ready to Partner With Cadical?',
                    'cta_paragraph' => "Let's provide your organization with trusted health products and skilled medical professionals.",
                    'cta_button_label' => 'Get Started Today', 'cta_button_href' => '/contact',
                ],
                'items' => [],
            ],

            'about_services' => [
                'meta' => [],
                'items' => [
                    [
                        'icon' => '🩺', 'title' => 'Health Product Sales',
                        'desc' => 'We supply hospitals, clinics, and pharmacies with safe, compliant, and affordable medical products sourced from trusted partners.',
                        'bullets' => ['Regulatory compliant products', 'Reliable supply chain', 'Affordable pricing structure'],
                    ],
                    [
                        'icon' => '👥', 'title' => 'Medical Staff Outsourcing',
                        'desc' => 'We connect healthcare facilities with verified and credentialed professionals for short-term, long-term, and contract-based roles.',
                        'bullets' => ['Doctors & Specialists', 'Nurses & Caregivers', 'Pharmacists & Lab Scientists'],
                    ],
                ],
            ],

            'about_values' => [
                'meta' => [],
                'items' => [
                    ['icon' => 'shield-check', 'title' => 'Our Mission', 'text' => 'To deliver accessible healthcare solutions through quality products and dependable medical staffing services.'],
                    ['icon' => 'heart-pulse', 'title' => 'Our Vision', 'text' => 'To become a leading healthcare solutions provider recognized for excellence, integrity, and innovation.'],
                    ['icon' => 'stethoscope', 'title' => 'Our Core Values', 'text' => 'Integrity, Quality, Reliability, and Care guide everything we do and every partnership we build.'],
                ],
            ],

            'contact' => [
                'meta' => [
                    'heading' => 'Get in Touch With Cadical',
                    'sub' => 'Whether you need reliable health products or qualified medical professionals, our team is ready to assist you.',
                    'email' => 'support@cadicalsolutions.com',
                    'phone' => '+234 707 617 5550',
                    'cta_heading' => "Let's Improve Healthcare Together",
                    'cta_paragraph' => 'Partner with Cadical for trusted healthcare solutions tailored to your needs.',
                    'cta_button_label' => 'Learn More About Us',
                ],
                'items' => [],
            ],

            'terms' => [
                'meta' => ['heading' => 'Terms'],
                'items' => [
                    ['title' => '1. Product Refunds', 'body' => 'Refunds are subject to product condition and regulatory compliance. Medical products that have been opened or used may not be eligible for return due to safety standards.'],
                    ['title' => '2. Service Modifications', 'body' => 'Cadical reserves the right to modify service terms where necessary to comply with healthcare regulations.'],
                ],
            ],

            'privacy' => [
                'meta' => ['heading' => 'Privacy Policy', 'effective_date' => 'Effective Date: January 1, 2026'],
                'items' => [
                    ['title' => '1. Introduction', 'body' => 'Cadical is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our healthcare product and staffing services.', 'bullets' => []],
                    ['title' => '2. Information We Collect', 'body' => 'We may collect personal information that you voluntarily provide to us, including:', 'bullets' => ['Full name', 'Email address', 'Phone number', 'Organization or facility details', 'Any information submitted through contact forms']],
                    ['title' => '3. How We Use Your Information', 'body' => 'We use the information we collect to:', 'bullets' => ['Respond to inquiries and service requests', 'Provide healthcare products and staffing solutions', 'Improve our website and services', 'Communicate updates or important information']],
                    ['title' => '4. Data Protection', 'body' => 'We implement appropriate administrative, technical, and physical safeguards to protect your personal information from unauthorized access, disclosure, or misuse.', 'bullets' => []],
                    ['title' => '5. Sharing of Information', 'body' => 'Cadical does not sell or rent your personal information. We may share information with trusted partners or service providers solely for the purpose of delivering our services.', 'bullets' => []],
                    ['title' => '6. Cookies', 'body' => 'Our website may use cookies or similar technologies to enhance user experience and analyze website performance. You may choose to disable cookies in your browser settings.', 'bullets' => []],
                    ['title' => '7. Your Rights', 'body' => 'You have the right to request access to, correction of, or deletion of your personal information. To exercise these rights, please contact us using the information below.', 'bullets' => []],
                ],
            ],

            'referral_form' => [
                'meta' => [
                    'banner_heading' => 'Referral Form',
                    'banner_sub' => "Nigeria's Healthcare Supply Partner",
                    'form_title' => 'Cadical Solutions Ltd',
                    'form_sub' => 'cadicalsolutions.com • Healthcare Supplies',
                    'section1_title' => 'Section 1: Contact / Ordering Party',
                    'section1_sub' => 'Details of the healthcare professional, facility, or affiliate partner placing this supply request',
                    'section2_title' => 'Section 2: Client / Facility Details',
                    'section2_sub' => "Details of the client, facility, or institution requesting Cadical's supplies or services",
                    'section3_title' => 'Section 3: Supply & Product Request',
                    'section3_sub' => 'Specify the healthcare supplies, diagnostic products, or services required',
                    'section4_title' => 'Section 4: Supply Referral & Affiliate Tracking',
                    'section4_sub' => 'For affiliate partners referring clients to Cadical: commission tracking and order attribution',
                    'confirmation_heading' => 'Referral Submitted!',
                    'confirmation_message' => 'Your referral has been received by Cadical Solutions Ltd. A confirmation will be sent to your email or phone shortly.',
                ],
                'items' => [],
            ],

            'booking_wizard' => [
                'meta' => [
                    'step1_heading' => 'What do you need?',
                    'step1_sub' => 'Select the service that fits your situation.',
                    'step2_heading' => 'Your Details',
                    'step2_sub' => 'Tell us who you are so we can prepare properly.',
                    'step3_heading' => 'When works for you?',
                    'step3_sub' => "Pick a slot or request a callback and we'll confirm a time that suits both sides.",
                    'step4_heading' => 'Review & Confirm',
                    'step4_sub' => 'Check everything looks right before submitting.',
                    'confirmation_heading' => 'Booking Received.',
                    'confirmation_message' => 'Thank you. Your booking request has been submitted. The Cadical team will confirm your appointment within 24 hours via phone or WhatsApp.',
                    'emergency_notice' => "Equipment emergency? Don't use this form. Call us directly on +234 707 617 5550 for same-day response.",
                ],
                'items' => [],
            ],

            'booking_wizard_expect' => [
                'meta' => ['title' => 'What to Expect'],
                'items' => [
                    ['icon' => '⏱️', 'title' => '24hr Confirmation', 'desc' => 'We confirm every booking within 24 hours, sooner for urgent requests'],
                    ['icon' => '📍', 'title' => 'We Come to You', 'desc' => 'Physical services are delivered at your facility or location across Nigeria'],
                    ['icon' => '💻', 'title' => 'Virtual Available', 'desc' => 'Consultations can be done via WhatsApp video or any preferred platform'],
                    ['icon' => '🎁', 'title' => 'Free First Consultation', 'desc' => 'Your first supply consultation is completely free, no strings attached'],
                    ['icon' => '📋', 'title' => 'Maintenance Contracts', 'desc' => 'Ask about quarterly contracts for ongoing equipment servicing'],
                ],
            ],

            'booking_wizard_contact' => [
                'meta' => ['title' => 'Prefer to Call?'],
                'items' => [
                    ['icon' => '📞', 'title' => 'Call Us', 'desc' => '+234 707 617 5550', 'href' => 'tel:+2347076175550'],
                    ['icon' => '💬', 'title' => 'WhatsApp', 'desc' => 'Message us directly for fastest response', 'href' => 'https://wa.me/2347076175550'],
                    ['icon' => '✉️', 'title' => 'Email', 'desc' => 'services@cadicalsolutions.com', 'href' => 'mailto:services@cadicalsolutions.com'],
                ],
            ],

            'service_booking_wizard' => [
                'meta' => [
                    'banner_title' => 'Book a Service',
                    'banner_sub' => 'Medical equipment service request',
                    'confirmation_heading' => 'Booking Confirmed',
                    'confirmation_message' => 'Your service request has been received. Our team will review and assign a technician shortly.',
                    'next_steps' => [
                        'Booking review within 2 hours',
                        'Technician assignment notification',
                        'Real-time status updates via SMS/app',
                        'Technician arrives at scheduled time',
                    ],
                ],
                'items' => [],
            ],
        ];

        foreach ($sections as $key => $content) {
            HomeSection::updateOrCreate(['section_key' => $key], ['content' => $content]);
        }

        $this->command->info('  Page content sections seeded ('.count($sections).')');
    }
}
