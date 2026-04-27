<?php

namespace App\Models\Academic;

use App\Models\Academic\Exam;
use App\Models\Academic\ExamRule;
use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamRuleMap extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_exam_rule_maps';

    protected $fillable = [
        'exam_id',
        'rule_id',
        'order',
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
        'is_active'  => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function rule()
    {
        return $this->belongsTo(ExamRule::class, 'rule_id');
    }

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

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id();
            $model->updated_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }
}
