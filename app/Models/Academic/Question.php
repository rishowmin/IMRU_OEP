<?php

namespace App\Models\Academic;

use App\Models\Academic\ExamAnswer;
use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_questions';

    protected $fillable = [
        'exam_id',
        'topic',
        'question_type',
        'question_text',
        'difficulty_level',
        'marks',
        'evaluation_type',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'question_figure',
        'question_order',
        'is_active',
        'aca_created_by',
        'aca_updated_by',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
        'marks' => 'decimal:2',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function acaCreatedBy()
    {
        return $this->belongsTo(Teacher::class, 'aca_created_by');
    }

    public function acaUpdatedBy()
    {
        return $this->belongsTo(Teacher::class, 'aca_updated_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function examAnswers()
    {
        return $this->hasMany(ExamAnswer::class, 'exam_id');
    }
}
