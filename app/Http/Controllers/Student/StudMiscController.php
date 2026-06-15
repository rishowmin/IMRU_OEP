<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudMiscController extends Controller
{
    public function documentation()
    {
        return view('student.modules.misc.documentation');
    }

    public function flowchart()
    {
        return view('student.modules.misc.flowchart');
    }
}
