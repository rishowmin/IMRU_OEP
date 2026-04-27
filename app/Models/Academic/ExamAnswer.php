<?php

namespace App\Models\Academic;

use App\Models\Academic\Exam;
use App\Models\Academic\Question;
use App\Models\Academic\ReviewAnswer;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamAnswer extends Model
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
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    // Which question this answer is for
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function reviewAnswer()
    {
        return $this->hasOne(ReviewAnswer::class, 'exam_answers_id');
    }
}
