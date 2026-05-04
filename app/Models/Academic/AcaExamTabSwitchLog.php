<?php

namespace App\Models\Academic;

use App\Models\Academic\AcaExamAttempt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcaExamTabSwitchLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_exam_tab_switch_logs';

    protected $fillable = [
        'attempt_id',
        'switched_at',
        'returned_at',
        'duration_ms',
        'switch_count',
        'updated_by',
    ];

    protected $casts = [
        'switched_at' => 'datetime',
        'returned_at' => 'datetime',
        'duration_ms' => 'integer',
        'switch_count'=> 'integer',
    ];

    // ── Relationships ──────────────────────────────

    public function attempt()
    {
        return $this->belongsTo(AcaExamAttempt::class, 'attempt_id');
    }

    // ── Helpers ────────────────────────────────────

    public function getDurationInSecondsAttribute(): ?float
    {
        return $this->duration_ms ? $this->duration_ms / 1000 : null;
    }

    public function getIsLongAbsenceAttribute(): bool
    {
        // Flag if away for more than 30 seconds
        return $this->duration_ms && $this->duration_ms > 30000;
    }
}
