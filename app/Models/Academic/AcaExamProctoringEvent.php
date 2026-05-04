<?php

namespace App\Models\Academic;

use App\Models\Academic\AcaExamAttempt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcaExamProctoringEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_exam_proctoring_events';

    protected $fillable = [
        'attempt_id',
        'event_type',
        'severity',
        'metadata',
        'detected_at',
        'updated_by',
    ];

    protected $casts = [
        'metadata'    => 'array',
        'detected_at' => 'datetime',
    ];

    // Event type constants
    const EVENT_TAB_SWITCH       = 'tab_switch';
    const EVENT_COPY_ATTEMPT     = 'copy_attempt';
    const EVENT_PASTE_ATTEMPT    = 'paste_attempt';
    const EVENT_FACE_NOT_FOUND   = 'face_not_detected';
    const EVENT_MULTIPLE_FACES   = 'multiple_faces';
    const EVENT_LOOKING_AWAY     = 'looking_away';

    // Severity constants
    const SEVERITY_LOW    = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH   = 'high';

    // ── Relationships ──────────────────────────────

    public function attempt()
    {
        return $this->belongsTo(AcaExamAttempt::class, 'attempt_id');
    }

    // ── Scopes ─────────────────────────────────────

    public function scopeHighSeverity($query)
    {
        return $query->where('severity', self::SEVERITY_HIGH);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('event_type', $type);
    }
}
