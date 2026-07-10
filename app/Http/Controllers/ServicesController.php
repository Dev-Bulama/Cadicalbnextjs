<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ServicesController extends Controller
{
    public const SERVICES = [
        'consultations' => [
            'title' => 'Consultations', 'icon' => '💬',
            'summary' => 'Expert medical consultations across crucial health departments with international collaboration.',
            'intro' => 'Expert medical consultations across crucial health departments with international collaboration. Our doctors provide personalized care and guidance for your health needs.',
            'items' => ['General health consultations', 'Specialist advice in cardiology, neurology, and more', 'Online & in-person appointments', 'International collaboration for advanced cases'],
        ],
        'pharmaceuticals' => [
            'title' => 'Pharmaceuticals', 'icon' => '💊',
            'summary' => 'Latest WHO-approved drugs and medical equipment with 100% efficacy assurance.',
            'intro' => 'Access the latest WHO-approved drugs and medical equipment. All products come with 100% efficacy assurance and safety standards.',
            'items' => ['Prescription medications', 'Over-the-counter drugs', 'Medical devices and equipment', 'Vaccines and immunizations'],
        ],
        'surgical-equipment' => [
            'title' => 'Surgical Equipment', 'icon' => '⚕️',
            'summary' => 'Advanced surgical devices including computer-assisted and robotically-assisted systems.',
            'intro' => 'Advanced surgical devices including computer-assisted and robotically-assisted systems. Ensuring precision and safety in all procedures.',
            'items' => ['Robot-assisted surgical systems', 'Laparoscopic and endoscopic tools', 'Precision monitoring equipment', 'Advanced sterilization and safety systems'],
        ],
        'diagnostics' => [
            'title' => 'Diagnostics', 'icon' => '🔬',
            'summary' => '3D radiological imaging and laboratory investigations with precision and sensitivity.',
            'intro' => '3D radiological imaging and laboratory investigations with precision and sensitivity. Fast, accurate, and reliable diagnostic services.',
            'items' => ['CT, MRI, and X-ray imaging', 'Laboratory testing and analysis', 'Pathology and molecular diagnostics', 'Health screening programs'],
        ],
        'rehabilitation' => [
            'title' => 'Rehabilitation', 'icon' => '🏃',
            'summary' => 'Physical therapy, occupational therapy, and sports medicine rehabilitation.',
            'intro' => 'Physical therapy, occupational therapy, and sports medicine rehabilitation. Helping patients regain strength, mobility, and independence.',
            'items' => ['Physiotherapy sessions', 'Sports injury rehab', 'Post-operative recovery', 'Occupational therapy support'],
        ],
        'emergency-services' => [
            'title' => 'Emergency Services', 'icon' => '🚑',
            'summary' => 'Quick response emergency medical services with advanced life support capabilities.',
            'intro' => 'Quick response emergency medical services with advanced life support capabilities. Available 24/7 for urgent care and critical situations.',
            'items' => ['Ambulance and rapid response', 'Advanced life support', 'Trauma care and stabilization', 'On-site emergency interventions'],
        ],
        'cosmetics' => [
            'title' => 'Cosmetics', 'icon' => '✨',
            'summary' => 'Latest cosmetology and dermatology services with skin care solutions.',
            'intro' => 'Latest cosmetology and dermatology services with skin care solutions. Enhancing your natural beauty safely and effectively.',
            'items' => ['Skin rejuvenation', 'Facial and aesthetic treatments', 'Laser therapies', 'Cosmetic dermatology consultations'],
        ],
        'referrals' => [
            'title' => 'Referrals', 'icon' => '🤝',
            'summary' => 'Professional referral services connecting you with expert healthcare networks.',
            'intro' => 'Professional referral services connecting you with expert healthcare networks. Ensuring you get the right care with trusted specialists.',
            'items' => ['Specialist doctor referrals', 'Inter-hospital patient transfers', 'International medical network connections', 'Coordinated care for complex cases'],
        ],
    ];

    public function index(): View
    {
        return view('services.index', ['services' => self::SERVICES]);
    }

    public function show(string $slug): View|Response
    {
        if (! isset(self::SERVICES[$slug])) {
            abort(404);
        }

        return view('services.show', ['slug' => $slug, 'service' => self::SERVICES[$slug]]);
    }
}
