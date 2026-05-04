<?php

namespace App\Models\Academic;

use App\Models\Academic\AcaExamAnswer;
use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcaReviewAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_review_answers';

    protected $fillable = [
        'exam_answers_id',
        'review',
        'marks_awarded',
        'is_active',
        'aca_created_by',
        'aca_updated_by',
        'created_by',
        'updated_by'
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
        'review'    => 'boolean',
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

    // Link to the original exam answer
    public function examAnswer()
    {
        return $this->belongsTo(AcaExamAnswer::class, 'exam_answers_id');
    }
}
