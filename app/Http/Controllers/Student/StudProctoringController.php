<?php

namespace App\Http\Controllers\Student;

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

class StudProctoringController extends Controller
{
    // ── Student: Log Tab Switch ────────────────────────────────────

    public function logTabSwitch(LogTabSwitchRequest  $request, AcaExamAttempt $attempt): JsonResponse
    {
        $switchCount = AcaExamTabSwitchLog::where('attempt_id', $attempt->id)->count() + 1;

        AcaExamTabSwitchLog::create([
            'attempt_id'  => $attempt->id,
            'switched_at' => now(),
            // 'returned_at' => $request->returned_at ?? null,
            'returned_at' => now(),
            'duration_ms' => $request->duration_ms ?? null,
            'switch_count'=> $switchCount,
            // 'updated_by'  => auth('student')->id(),
            'updated_by' => auth()->id(),
        ]);

        AcaExamProctoringEvent::create([
            'attempt_id'  => $attempt->id,
            'event_type'  => AcaExamProctoringEvent::EVENT_TAB_SWITCH,
            'severity'    => $switchCount >= 5
                                ? AcaExamProctoringEvent::SEVERITY_HIGH
                                : ($switchCount >= 3
                                    ? AcaExamProctoringEvent::SEVERITY_MEDIUM
                                    : AcaExamProctoringEvent::SEVERITY_LOW),
            'metadata'    => ['switch_count' => $switchCount],
            'detected_at' => now(),
            // 'updated_by'  => auth('student')->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'success'      => true,
            'switch_count' => $switchCount,
        ]);
    }

    // ── Student: Log Clipboard ─────────────────────────────────────

    public function logClipboard(LogClipboardRequest $request, AcaExamAttempt $attempt): JsonResponse
    {
        AcaExamClipboardLog::create([
            'attempt_id'   => $attempt->id,
            'action_type'  => $request->action_type, // copy, paste, cut
            'attempted_at' => now(),
            // 'updated_by'   => auth('student')->id(),
            'updated_by' => auth()->id(),
        ]);

        AcaExamProctoringEvent::create([
            'attempt_id'  => $attempt->id,
            'event_type'  => $request->action_type . '_attempt',
            'severity'    => AcaExamProctoringEvent::SEVERITY_MEDIUM,
            'metadata'    => ['action' => $request->action_type],
            'detected_at' => now(),
            // 'updated_by'  => auth('student')->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    // ── Student: Log Webcam ────────────────────────────────────────

    public function logWebcam(LogWebcamRequest $request, AcaExamAttempt $attempt): JsonResponse
    {
        $file      = $request->file('image');
        $extension = $file->getClientOriginalExtension() ?: 'jpg';

        // ✅ Unique filename: attemptId_timestamp.jpg
        $fileName   = $attempt->id . '_' . time() . '.' . $extension;
        $uploadPath = public_path('storage/proctoring/webcam/');

        // ✅ Create directory if it doesn't exist
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file->move($uploadPath, $fileName);

        // TODO: Plug in AI service here (AWS Rekognition / Azure)
        $aiFlag     = 'clear';
        $confidence = 100.00;

        AcaExamWebcamLog::create([
            'attempt_id'  => $attempt->id,
            'image_url'   => $fileName,
            'ai_flag'     => $aiFlag,
            'confidence'  => $confidence,
            'captured_at' => now(),
            // 'updated_by'  => auth('student')->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'ai_flag' => $aiFlag,
        ]);
    }

    // ── Student: Log General Event ─────────────────────────────────

    public function logEvent(LogEventRequest $request, AcaExamAttempt $attempt): JsonResponse
    {
        AcaExamProctoringEvent::create([
            'attempt_id'  => $attempt->id,
            'event_type'  => $request->event_type,
            'severity'    => $request->severity ?? AcaExamProctoringEvent::SEVERITY_LOW,
            'metadata'    => $request->metadata ?? null,
            'detected_at' => now(),
            // 'updated_by'  => auth('student')->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}
