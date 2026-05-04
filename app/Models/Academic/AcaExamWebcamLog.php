<?php

namespace App\Models\Academic;

use App\Models\Academic\AcaExamAttempt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcaExamWebcamLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_exam_webcam_logs';

    protected $fillable = [
        'attempt_id',
        'image_url',
        'ai_flag',
        'confidence',
        'captured_at',
        'updated_by',
    ];

    protected $casts = [
        'captured_at' => 'datetime',
        'confidence'  => 'decimal:2',
    ];

    // AI Flag constants
    const FLAG_CLEAR          = 'clear';
    const FLAG_NO_FACE        = 'no_face';
    const FLAG_MULTIPLE_FACES = 'multiple_faces';
    const FLAG_SUSPICIOUS     = 'suspicious';

    // ── Relationships ──────────────────────────────

    public function attempt()
    {
        return $this->belongsTo(AcaExamAttempt::class, 'attempt_id');
    }

    // ── Scopes ─────────────────────────────────────

    public function scopeSuspicious($query)
    {
        return $query->whereIn('ai_flag', [
            self::FLAG_NO_FACE,
            self::FLAG_MULTIPLE_FACES,
            self::FLAG_SUSPICIOUS,
        ]);
    }

    public function scopeClear($query)
    {
        return $query->where('ai_flag', self::FLAG_CLEAR);
    }

    // ── Helpers ────────────────────────────────────

    public function getIsSuspiciousAttribute(): bool
    {
        return $this->ai_flag !== self::FLAG_CLEAR;
    }
}
