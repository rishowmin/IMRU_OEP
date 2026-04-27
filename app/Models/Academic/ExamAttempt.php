<?php

namespace App\Models\Academic;

use App\Models\Academic\Exam;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamAttempt extends Model
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
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
