<?php

namespace App\Models\Academic;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aca_courses';

    protected $fillable = [
        'course_title',
        'course_code',
        'credits',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
}
