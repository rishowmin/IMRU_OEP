<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Model;

class AcaExamWebcamVideo extends Model
{
    public const STORAGE_DIRECTORY = 'assets/storage/proctoring/webcam';

    protected $table = 'aca_exam_webcam_videos';

    protected $fillable = [
        'attempt_id',
        'file_path',
        'mime_type',
        'chunk_count',
        'size_bytes',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function attempt()
    {
        return $this->belongsTo(AcaExamAttempt::class, 'attempt_id');
    }

    public function getAbsolutePathAttribute(): string
    {
        $fileName = basename($this->file_path);
        $publicPath = public_path(self::STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . $fileName);

        if (is_file($publicPath)) {
            return $publicPath;
        }

        $legacyPath = storage_path('app/proctoring/webcam/videos/' . $fileName);
        return is_file($legacyPath) ? $legacyPath : $publicPath;
    }
}
