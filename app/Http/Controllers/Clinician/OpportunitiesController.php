<?php

namespace App\Http\Controllers\Clinician;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpportunitiesController extends Controller
{
    public const LISTINGS = [
        ['id' => 1, 'title' => 'Consultant Cardiologist', 'company' => 'Lagos General Hospital', 'type' => 'Full-time', 'description' => 'Seeking an experienced cardiologist for our newly expanded cardiac care unit.', 'location' => 'Lagos', 'specialization' => 'Cardiology', 'yearsRequired' => 8, 'postedDate' => '2 days ago'],
        ['id' => 2, 'title' => 'Telemedicine Consultant', 'company' => 'Cadical Health Network', 'type' => 'Remote', 'description' => 'Provide remote consultations to patients across underserved regions of Nigeria.', 'location' => 'Remote', 'specialization' => 'General Practice', 'yearsRequired' => 3, 'postedDate' => '5 days ago'],
        ['id' => 3, 'title' => 'Locum Anaesthetist', 'company' => 'Rivers State Hospital', 'type' => 'Contract', 'description' => 'Short-term contract covering surgical theatre sessions for 3 months.', 'location' => 'Port Harcourt', 'specialization' => 'Anaesthesiology', 'yearsRequired' => 5, 'postedDate' => '1 week ago'],
        ['id' => 4, 'title' => 'Paediatric Specialist', 'company' => 'Abuja Children\'s Clinic', 'type' => 'Full-time', 'description' => 'Join a growing paediatric practice serving families across the FCT.', 'location' => 'Abuja', 'specialization' => 'Paediatrics', 'yearsRequired' => 4, 'postedDate' => '2 weeks ago'],
    ];

    public function index(Request $request): View
    {
        $search = strtolower((string) $request->query('search', ''));

        $opportunities = collect(self::LISTINGS)->filter(function ($opp) use ($search) {
            if ($search === '') {
                return true;
            }

            return str_contains(strtolower($opp['title']), $search) || str_contains(strtolower($opp['description']), $search);
        })->values();

        return view('clinician.opportunities', compact('opportunities', 'search'));
    }
}
