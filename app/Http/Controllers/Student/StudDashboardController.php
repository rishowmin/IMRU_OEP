<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudDashboardController extends Controller
{
    public function dashboard()
    {
        return view('student.dashboard');
    }
}
