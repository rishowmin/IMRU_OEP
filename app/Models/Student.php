<?php

namespace App\Models;

use App\Models\Academic\Course;
use App\Models\Academic\Enrollment;
use App\Models\Academic\ExamAnswer;
use App\Models\StudentInfo;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guard = 'student';

    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'is_active',
        'aca_created_by',
        'aca_updated_by',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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

    public function courses()
    {
        return $this->hasMany(Course::class, 'course_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function examAnswers()
    {
        return $this->hasMany(ExamAnswer::class, 'exam_id');
    }

    public function info()
    {
        return $this->hasOne(StudentInfo::class, 'student_id');
    }
}
