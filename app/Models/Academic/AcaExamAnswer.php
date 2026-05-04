<?php

namespace App\Models\Academic;

use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaQuestion;
use App\Models\Academic\AcaReviewAnswer;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcaExamAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_exam_answers';

    protected $fillable = [
        'student_id',
        'exam_id',
        'question_id',
        'answer',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // Who submitted this answer
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Which exam this answer belongs to
    public function exam()
    {
        return $this->belongsTo(AcaExam::class, 'exam_id');
    }

    // Which question this answer is for
    public function question()
    {
        return $this->belongsTo(AcaQuestion::class, 'question_id');
    }

    public function reviewAnswer()
    {
        return $this->hasOne(AcaReviewAnswer::class, 'exam_answers_id');
    }
}
