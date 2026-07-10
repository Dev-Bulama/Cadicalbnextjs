<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class NotificationsController extends Controller
{
    public function index(): View
    {
        return view('notifications');
    }
}
