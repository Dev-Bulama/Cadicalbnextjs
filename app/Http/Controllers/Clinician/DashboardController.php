<?php

namespace App\Http\Controllers\Clinician;

use App\Http\Controllers\Clinician\OpportunitiesController as Opportunities;
use App\Http\Controllers\Controller;
use App\Models\Clinician;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $clinician = Clinician::where('user_id', $request->user()->id)->firstOrFail();
        $opportunities = Opportunities::LISTINGS;

        return view('clinician.dashboard', compact('clinician', 'opportunities'));
    }
}
