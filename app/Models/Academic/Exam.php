<?php

namespace App\Models\Academic;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_exams';

    protected $fillable = [
        'course_id',
        'exam_title',
        'exam_code',
        'exam_type',
        'exam_date',
        'start_time',
        'end_time',
        'exam_duration_min',
        'total_marks',
        'passing_marks',
        'total_questions',
        'instructions',
        'basic_rules',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'exam_date',
        'deleted_at',
    ];

    protected $casts = [
        'exam_date' => 'date:d-M-Y',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'total_marks' => 'decimal:2',
        'passing_marks' => 'decimal:2',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'exam_id');
    }
}
