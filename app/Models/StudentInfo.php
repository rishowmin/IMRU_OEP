<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_infos';

    protected $fillable = [
        // Academic
        'student_id',
        'student_id_no',
        'session',
        'batch',
        'semester',
        'department',
        'program',
        'admission_date',

        // Personal
        'gender',
        'dob',
        'blood_group',
        'religion',
        'nationality',
        'marital_status',
        'nid_number',
        'birth_certificate_no',

        // Contact
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',

        // Address
        'present_address',
        'permanent_address',
        'city',
        'district',
        'division',
        'postal_code',
        'country',

        // Profile
        'profile_photo',
        'bio',

        // Audit
        'aca_created_by',
        'aca_updated_by',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'dob',
        'admission_date',
        'deleted_at',
    ];

    protected $casts = [
        'dob'            => 'date:d-M-Y',
        'admission_date' => 'date:d-M-Y',
        'deleted_at'     => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
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

    // Helper — get age from dob
    public function getAgeAttribute(): ?int
    {
        return $this->dob ? $this->dob->age : null;
    }

    // // Helper — get formatted dob
    // public function getFormattedDobAttribute(): ?string
    // {
    //     return $this->dob ? $this->dob->format('d M Y') : null;
    // }
}
