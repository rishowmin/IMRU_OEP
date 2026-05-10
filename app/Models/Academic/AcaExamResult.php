<?php

namespace App\Models\Academic;

use App\Models\Academic\AcaExam;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcaExamResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_exam_results';

    protected $fillable = [
        'student_id',
        'exam_id',

        'mcq_total',
        'mcq_correct',
        'mcq_wrong',
        'mcq_unanswered',
        'mcq_marks_obtained',
        'mcq_total_marks',

        'subjective_total',
        'subjective_reviewed',
        'subjective_marks_obtained',
        'subjective_total_marks',

        'total_marks_obtained',
        'total_marks',
        'percentage',
        'grade',
        'is_pass',

        'grading_status',
        'graded_at',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'graded_at' => 'datetime',
        'is_pass'   => 'boolean',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function exam()
    {
        return $this->belongsTo(AcaExam::class, 'exam_id');
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /**
     * Returns grade label CSS class for badges.
     */
    public function getGradeBadgeClassAttribute(): string
    {
        return match ($this->grade) {
            'A+'        => 'badge grade-a-plus',
            'A'         => 'badge grade-a',
            'A-'        => 'badge grade-a-minus',
            'B+'        => 'badge grade-b-plus',
            'B'         => 'badge grade-b',
            'B-'        => 'badge grade-b-minus',
            'C+'        => 'badge grade-c-plus',
            'C'         => 'badge grade-c',
            'D'         => 'badge grade-d',
            default     => 'badge grade-f', // F
        };
    }

    /**
     * Calculate and return grade from percentage.
     */
    public static function calculateGrade(float $percentage): string
    {
        return match (true) {
            $percentage >= 80 => 'A+',
            $percentage >= 75 => 'A',
            $percentage >= 70 => 'A-',
            $percentage >= 65 => 'B+',
            $percentage >= 60 => 'B',
            $percentage >= 55 => 'B-',
            $percentage >= 50 => 'C+',
            $percentage >= 45 => 'C',
            $percentage >= 40 => 'D',
            default           => 'F',
        };
    }
}
