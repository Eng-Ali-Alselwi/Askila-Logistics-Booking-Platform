<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        // Toast::info($this, 'Welcome to the dashboard!');
        return view('dashboard.profile.profile');
    }


}
