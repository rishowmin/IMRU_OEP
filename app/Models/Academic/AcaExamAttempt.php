<?php

namespace App\Models\Academic;

use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamClipboardLog;
use App\Models\Academic\AcaExamProctoringEvent;
use App\Models\Academic\AcaExamTabSwitchLog;
use App\Models\Academic\AcaExamWebcamLog;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcaExamAttempt extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_exam_attempts';

    protected $fillable = [
        'student_id',
        'exam_id',
        'started_at',
        'submitted_at',
        'status',
        'is_active',
        'aca_updated_by',
        'updated_by'
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];


    public function acaUpdatedBy()
    {
        return $this->belongsTo(Teacher::class, 'aca_updated_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function exam()
    {
        return $this->belongsTo(AcaExam::class, 'exam_id');
    }





    

    public function proctoringEvents()
    {
        return $this->hasMany(AcaExamProctoringEvent::class, 'attempt_id');
    }

    public function webcamLogs()
    {
        return $this->hasMany(AcaExamWebcamLog::class, 'attempt_id');
    }

    public function tabSwitchLogs()
    {
        return $this->hasMany(AcaExamTabSwitchLog::class, 'attempt_id');
    }

    public function clipboardLogs()
    {
        return $this->hasMany(AcaExamClipboardLog::class, 'attempt_id');
    }

    // ── Helper Methods ─────────────────────────────

    public function getTotalTabSwitchesAttribute()
    {
        return $this->tabSwitchLogs()->count();
    }

    public function getTotalClipboardAttemptsAttribute()
    {
        return $this->clipboardLogs()->count();
    }

    public function getHasSuspiciousWebcamAttribute()
    {
        return $this->webcamLogs()
            ->whereIn('ai_flag', ['no_face', 'multiple_faces', 'suspicious'])
            ->exists();
    }
}
