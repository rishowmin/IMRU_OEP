<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TechDashboardController extends Controller
{
    public function dashboard()
    {
        return view('teacher.dashboard');
    }
}
