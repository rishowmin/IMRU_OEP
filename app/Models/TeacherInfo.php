<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'teacher_infos';

    protected $fillable = [
        'teacher_id',

        // Academic
        'teacher_id_no',
        'designation',
        'department',
        'specialization',
        'qualification',
        'joining_date',
        'experience_years',

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
        'signature',
        'bio',

        // Social
        'linkedin',
        'google_scholar',
        'researchgate',
        'website',

        // Audit
        'aca_created_by',
        'aca_updated_by',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'dob',
        'joining_date',
        'deleted_at',
    ];

    protected $casts = [
        'dob'          => 'date:d-M-Y',
        'joining_date' => 'date:d-M-Y',
        'deleted_at'   => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
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

    // public function getFormattedDobAttribute(): ?string
    // {
    //     return $this->dob ? $this->dob->format('d M Y') : null;
    // }

    // public function getFormattedJoiningDateAttribute(): ?string
    // {
    //     return $this->joining_date ? $this->joining_date->format('d M Y') : null;
    // }
}
