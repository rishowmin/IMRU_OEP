<?php

namespace App\Models\Academic;

use App\Models\Academic\AcaExamAttempt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcaExamClipboardLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_exam_clipboard_logs';

    protected $fillable = [
        'attempt_id',
        'action_type',
        'attempted_at',
        'updated_by',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
    ];

    // Action type constants
    const ACTION_COPY  = 'copy';
    const ACTION_PASTE = 'paste';
    const ACTION_CUT   = 'cut';

    // ── Relationships ──────────────────────────────

    public function attempt()
    {
        return $this->belongsTo(AcaExamAttempt::class, 'attempt_id');
    }

    // ── Scopes ─────────────────────────────────────

    public function scopeCopyOnly($query)
    {
        return $query->where('action_type', self::ACTION_COPY);
    }

    public function scopePasteOnly($query)
    {
        return $query->where('action_type', self::ACTION_PASTE);
    }
}
