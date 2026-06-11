<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TechMiscController extends Controller
{
    public function documentation()
    {
        return view('teacher.modules.misc.documentation');
    }

    public function flowchart()
    {
        return view('teacher.modules.misc.flowchart');
    }
}
