<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Proctoring\LogClipboardRequest;
use App\Http\Requests\Proctoring\LogEventRequest;
use App\Http\Requests\Proctoring\LogTabSwitchRequest;
use App\Http\Requests\Proctoring\RecordWebcamVideoChunkRequest;
use App\Models\Academic\AcaExamAttempt;
use App\Models\Academic\AcaExamClipboardLog;
use App\Models\Academic\AcaExamProctoringEvent;
use App\Models\Academic\AcaExamTabSwitchLog;
use App\Models\Academic\AcaExamWebcamVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StudProctoringController extends Controller
{
    // Student: Log Tab Switch

    public function logTabSwitch(LogTabSwitchRequest  $request, AcaExamAttempt $attempt): JsonResponse
    {
        $switchCount = AcaExamTabSwitchLog::where('attempt_id', $attempt->id)->count() + 1;

        AcaExamTabSwitchLog::create([
            'attempt_id'  => $attempt->id,
            'switched_at' => now(),
            'returned_at' => now(),
            'duration_ms' => $request->duration_ms ?? null,
            'switch_count'=> $switchCount,
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

    public function recordWebcamVideoChunk(RecordWebcamVideoChunkRequest $request, AcaExamAttempt $attempt): JsonResponse
    {
        $mimeType = $request->input('mime_type');
        $extension = [
            'video/webm' => 'webm',
            'video/mp4' => 'mp4',
            'video/ogg' => 'ogv',
        ][$mimeType] ?? null;

        if ($extension === null) {
            abort(422, 'Unsupported webcam video format.');
        }

        $file = $request->file('video');
        $chunk = file_get_contents($file->getRealPath());
        if ($chunk === false || $chunk === '') {
            abort(422, 'Webcam video chunk is empty or unreadable.');
        }

        DB::transaction(function () use ($attempt, $request, $chunk, $mimeType, $extension) {
            $video = AcaExamWebcamVideo::where('attempt_id', $attempt->id)
                ->lockForUpdate()
                ->first();

            if (!$video && (int) $request->input('chunk_index') !== 0) {
                abort(409, 'The first webcam video chunk is missing.');
            }

            if ($video && (int) $request->input('chunk_index') < $video->chunk_count) {
                return;
            }

            if ($video && (int) $request->input('chunk_index') !== $video->chunk_count) {
                abort(409, 'Webcam video chunks arrived out of order.');
            }

            if ($video && $video->ended_at) {
                abort(409, 'The webcam video has already been finalized.');
            }

            $fileName = $attempt->id . '.' . $extension;
            if (!$video) {
                $video = AcaExamWebcamVideo::create([
                    'attempt_id' => $attempt->id,
                    'file_path' => $fileName,
                    'mime_type' => $mimeType,
                    'started_at' => now(),
                ]);
            } elseif ($video->mime_type !== $mimeType) {
                abort(422, 'Webcam video format changed during recording.');
            }

            $directory = public_path(AcaExamWebcamVideo::STORAGE_DIRECTORY);
            if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
                throw new RuntimeException('Unable to create webcam video directory.');
            }

            $handle = fopen($video->absolute_path, 'ab');
            if ($handle === false) {
                throw new RuntimeException('Unable to open webcam video for writing.');
            }

            try {
                if (!flock($handle, LOCK_EX)) {
                    throw new RuntimeException('Unable to lock webcam video for writing.');
                }

                $offset = 0;
                $length = strlen($chunk);
                while ($offset < $length) {
                    $written = fwrite($handle, substr($chunk, $offset, 1048576));
                    if ($written === false || $written === 0) {
                        throw new RuntimeException('Unable to write webcam video chunk.');
                    }
                    $offset += $written;
                }

                if (!fflush($handle)) {
                    throw new RuntimeException('Unable to flush webcam video chunk.');
                }
            } finally {
                flock($handle, LOCK_UN);
                fclose($handle);
            }

            $video->increment('chunk_count');
            $video->increment('size_bytes', strlen($chunk));
        });

        return response()->json(['success' => true]);
    }

    public function finishWebcamVideo(AcaExamAttempt $attempt): JsonResponse
    {
        abort_unless((int) $attempt->student_id === (int) auth()->id(), 403);
        abort_if($attempt->submitted_at !== null, 409, 'The exam attempt has already been submitted.');

        $video = $attempt->webcamVideo;
        if (!$video) {
            abort(422, 'No webcam video was recorded.');
        }

        $video->update(['ended_at' => now()]);

        return response()->json(['success' => true]);
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
