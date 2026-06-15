<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaExamResult;
use Illuminate\Http\Request;

class StudMyResultController extends Controller
{
    public function index()
    {
        $student = auth()->id();

        // Load all results with exam + course, ordered by latest graded
        $myResults = AcaExamResult::where('student_id', $student)
            ->with(['exam.course'])
            ->orderBy('graded_at', 'desc')
            ->get()
            ->map(function ($result) use ($student) {

                $result->rank = AcaExamResult::where('exam_id', $result->exam_id)
                    ->where('percentage', '>', $result->percentage)
                    ->count() + 1;

                $result->total_students = AcaExamResult::where('exam_id', $result->exam_id)
                    ->count();

                return $result;
            });

        $serialNo = 1;

        return view('student.modules.myResults.index', compact('myResults', 'serialNo'));
    }
}
