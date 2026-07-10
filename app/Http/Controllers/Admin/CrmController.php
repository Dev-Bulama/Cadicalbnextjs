<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmConnection;
use Illuminate\View\View;

class CrmController extends Controller
{
    public function index(): View
    {
        $connection = CrmConnection::where('provider', 'zoho')->first();

        return view('admin.integrations.crm', compact('connection'));
    }
}
