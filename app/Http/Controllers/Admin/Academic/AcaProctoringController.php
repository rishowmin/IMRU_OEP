<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Proctoring\LogClipboardRequest;
use App\Http\Requests\Proctoring\LogEventRequest;
use App\Http\Requests\Proctoring\LogTabSwitchRequest;
use App\Http\Requests\Proctoring\LogWebcamRequest;
use App\Models\Academic\AcaExamAttempt;
use App\Models\Academic\AcaExamClipboardLog;
use App\Models\Academic\AcaExamProctoringEvent;
use App\Models\Academic\AcaExamTabSwitchLog;
use App\Models\Academic\AcaExamWebcamLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AcaProctoringController extends Controller
{
    // ── Admin: List All Flagged Attempts ───────────────────────────

    public function index()
    {
        $serialNo = 1;
        $attempts = AcaExamAttempt::with(['student', 'exam'])
            ->whereHas('proctoringEvents')
            ->latest()
            ->paginate(20);

        return view('admin.academic.proctoring.index', compact('attempts', 'serialNo'));
    }

    // ── Admin: Full Report ─────────────────────────────────────────

    public function getReport(AcaExamAttempt $attempt)
    {
        $attempt->load([
            'student',
            'exam',
            'proctoringEvents',
            'webcamLogs',
            'tabSwitchLogs',
            'clipboardLogs',
        ]);

        return view('admin.academic.proctoring.report', compact('attempt'));
    }

    // ── Admin: Summary ─────────────────────────────────────────────

    public function getSummary(AcaExamAttempt $attempt): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'attempt_id'           => $attempt->id,
                'student'              => $attempt->student->name ?? null,
                'exam'                 => $attempt->exam->title ?? null,
                'tab_switches'         => $attempt->tabSwitchLogs()->count(),
                'clipboard_attempts'   => $attempt->clipboardLogs()->count(),
                'webcam_flags'         => $attempt->webcamLogs()->suspicious()->count(),
                'high_severity_events' => $attempt->proctoringEvents()->highSeverity()->count(),
                'risk_level'           => $this->calculateRiskLevel($attempt),
            ],
        ]);
    }

    // ── Risk Calculator ────────────────────────────────────────────

    private function calculateRiskLevel(AcaExamAttempt $attempt): string
    {
        $score = 0;
        $score += $attempt->tabSwitchLogs()->count()              * 5;
        $score += $attempt->clipboardLogs()->count()              * 10;
        $score += $attempt->webcamLogs()->suspicious()->count()   * 20;
        $score += $attempt->proctoringEvents()->highSeverity()->count() * 15;

        if ($score >= 60) return 'high';
        if ($score >= 30) return 'medium';
        return 'low';
    }
}
