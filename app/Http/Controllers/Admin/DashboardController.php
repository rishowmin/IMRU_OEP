<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function academicDashboard()
    {
        return view('admin.academic.dashboard');
    }

    public function professionalDashboard()
    {
        return view('admin.professional.dashboard');
    }
}
