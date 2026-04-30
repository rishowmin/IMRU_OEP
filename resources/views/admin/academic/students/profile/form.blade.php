@extends('admin.layouts.app')
@section('title', 'Students')
@section('title2', 'Student Profile')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('admin.layouts.common.status')
@endif

<section class="section">

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-header-left">
                        <h5>
                            <i class="bi bi-person-badge"></i>
                            <span class="ms-1">@yield('title2')</span>
                        </h5>
                        <nav style="--bs-breadcrumb-divider: '•';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.academic.students.index') }}">@yield('title')</a></li>
                                <li class="breadcrumb-item active">@yield('title2')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('admin.academic.students.index') }}" class="btn btn-outline-theme btn-sm">
                            <i class="bi bi-arrow-left-square"></i>
                            <span class="ms-1">Back to List</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php $info = $student->info; @endphp

    <form action="{{ route('admin.academic.students.profile.store', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">

            {{-- Left Column --}}
            <div class="col-lg-4">

                {{-- Student Info Card --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body text-center">
                        <div class="profile-photo mb-3 position-relative d-inline-block" onclick="document.getElementById('profile_photo_input').click()">

                            <input type="file" name="profile_photo" id="profile_photo_input" class="d-none @error('profile_photo') is-invalid @enderror" accept="image/*">

                            {{-- Preview image --}}
                            <img id="photo-preview" src="{{ $info?->profile_photo ? asset('storage/profile_photo/student/' . $info->profile_photo) : '' }}" alt="Profile Photo" class="rounded-circle object-fit-cover border" style="{{ $info?->profile_photo ? '' : 'display:none;' }}">

                            {{-- Initials fallback --}}
                            @if(!$info?->profile_photo)
                                @php
                                    $firstName = $student->first_name ?? '';
                                    $lastName  = $student->last_name ?? '';
                                    $initials  = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                    $colors    = ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#f6c23e', '#6f42c1', '#fd7e14', '#20c9a6'];
                                    $bgColor   = $colors[abs(crc32($firstName . $lastName)) % count($colors)];
                                @endphp
                                <div id="photo-initials" class="rounded-circle d-flex align-items-center justify-content-center border" style="background-color:{{ $bgColor }};">
                                    <span>{{ $initials ?: '?' }}</span>
                                </div>
                            @endif

                            {{-- Camera overlay --}}
                            <div class="camera-overlay">
                                <i class="bi bi-camera-fill text-white"></i>
                            </div>

                            @error('profile_photo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <h6 class="fw-bold mb-0">{{ $student->first_name }} {{ $student->last_name }}</h6>
                        <small class="text-muted">{{ $student->email }}</small>

                        <hr>

                        <ul class="list-group list-group-flush small text-start">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Student ID</span>
                                <strong>{{ $info->student_id_no ?? 'N/A' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Department</span>
                                <strong>{{ $info->department ?? 'N/A' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Program</span>
                                <strong>{{ $info->program ?? 'N/A' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Session</span>
                                <strong>{{ $info->session ?? 'N/A' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Phone</span>
                                <strong>{{ $info->phone ?? 'N/A' }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Profile Photo --}}
                <div class="card border-0 shadow-sm mb-3 d-none">
                    <div class="card-header">
                        <h6 class="card-title fw-semibold mb-0 p-0">
                            <i class="bi bi-image me-1"></i>Profile Photo
                        </h6>
                    </div>
                    <div class="card-body">
                        {{-- Hidden real input --}}

                        {{-- Styled trigger button --}}
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('profile_photo_input').click()">
                                <i class="bi bi-upload me-1"></i>Choose Photo
                            </button>
                            <span id="file-name-label" class="text-muted small">No file chosen</span>
                        </div>

                    </div>
                </div>

                {{-- Bio --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header">
                        <h6 class="card-title fw-semibold mb-0 p-0">
                            <i class="bi bi-card-text me-1"></i>Bio
                        </h6>
                    </div>
                    <div class="card-body">
                        <textarea name="bio" rows="4"
                                  class="form-control form-control-sm @error('bio') is-invalid @enderror"
                                  placeholder="Write a short bio about the student...">{{ old('bio', $info->bio ?? '') }}</textarea>
                        @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-grid gap-2">
                        <button type="submit" class="btn btn-sm btn-theme">
                            <i class="bi bi-save me-1"></i>Save Profile
                        </button>
                        <a href="{{ route('admin.academic.students.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </a>
                    </div>
                </div>

            </div>

            {{-- Right Column --}}
            <div class="col-lg-8">

                {{-- Academic Information --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header">
                        <h6 class="card-title fw-semibold mb-0 p-0">
                            <i class="bi bi-mortarboard me-1"></i>Academic Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Student ID No</label>
                                <input type="text" name="student_id_no"
                                       class="form-control form-control-sm @error('student_id_no') is-invalid @enderror"
                                       placeholder="e.g. STD-2024-001"
                                       value="{{ old('student_id_no', $info->student_id_no ?? '') }}">
                                @error('student_id_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Session</label>
                                <input type="text" name="session"
                                       class="form-control form-control-sm @error('session') is-invalid @enderror"
                                       placeholder="e.g. 2021-2022"
                                       value="{{ old('session', $info->session ?? '') }}">
                                @error('session')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Batch</label>
                                <input type="text" name="batch"
                                       class="form-control form-control-sm @error('batch') is-invalid @enderror"
                                       placeholder="e.g. 10th"
                                       value="{{ old('batch', $info->batch ?? '') }}">
                                @error('batch')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Semester</label>
                                <input type="text" name="semester"
                                       class="form-control form-control-sm @error('semester') is-invalid @enderror"
                                       placeholder="e.g. 3rd"
                                       value="{{ old('semester', $info->semester ?? '') }}">
                                @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Department</label>
                                <input type="text" name="department"
                                       class="form-control form-control-sm @error('department') is-invalid @enderror"
                                       placeholder="e.g. Computer Science"
                                       value="{{ old('department', $info->department ?? '') }}">
                                @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Program</label>
                                <select name="program"
                                        class="form-select form-select-sm @error('program') is-invalid @enderror">
                                    <option value="">-- Select Program --</option>
                                    @foreach(['BSc', 'MSc', 'PhD', 'Diploma', 'Certificate', 'Other'] as $program)
                                    <option value="{{ $program }}" {{ old('program', $info->program ?? '') == $program ? 'selected' : '' }}>
                                        {{ $program }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('program')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Admission Date</label>
                                <input type="date" name="admission_date"
                                       class="form-control form-control-sm @error('admission_date') is-invalid @enderror"
                                       value="{{ old('admission_date', isset($info->admission_date) ? $info->admission_date->format('Y-m-d') : '') }}">
                                @error('admission_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Personal Information --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header">
                        <h6 class="card-title fw-semibold mb-0 p-0">
                            <i class="bi bi-person me-1"></i>Personal Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Gender</label>
                                <select name="gender"
                                        class="form-select form-select-sm @error('gender') is-invalid @enderror">
                                    <option value="">-- Select Gender --</option>
                                    @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('gender', $info->gender ?? '') == $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Date of Birth</label>
                                <input type="date" name="dob"
                                       class="form-control form-control-sm @error('dob') is-invalid @enderror"
                                       value="{{ old('dob', isset($info->dob) ? $info->dob->format('Y-m-d') : '') }}">
                                @error('dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Blood Group</label>
                                <select name="blood_group"
                                        class="form-select form-select-sm @error('blood_group') is-invalid @enderror">
                                    <option value="">-- Select Blood Group --</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group', $info->blood_group ?? '') == $bg ? 'selected' : '' }}>
                                        {{ $bg }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('blood_group')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Religion</label>
                                <select name="religion"
                                        class="form-select form-select-sm @error('religion') is-invalid @enderror">
                                    <option value="">-- Select Religion --</option>
                                    @foreach(['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'Other'] as $religion)
                                    <option value="{{ $religion }}" {{ old('religion', $info->religion ?? '') == $religion ? 'selected' : '' }}>
                                        {{ $religion }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('religion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Nationality</label>
                                <input type="text" name="nationality"
                                       class="form-control form-control-sm @error('nationality') is-invalid @enderror"
                                       placeholder="e.g. Bangladeshi"
                                       value="{{ old('nationality', $info->nationality ?? 'Bangladeshi') }}">
                                @error('nationality')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Marital Status</label>
                                <select name="marital_status"
                                        class="form-select form-select-sm @error('marital_status') is-invalid @enderror">
                                    <option value="">-- Select Status --</option>
                                    @foreach(['single' => 'Single', 'married' => 'Married', 'divorced' => 'Divorced', 'widowed' => 'Widowed'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('marital_status', $info->marital_status ?? '') == $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('marital_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">NID Number</label>
                                <input type="text" name="nid_number"
                                       class="form-control form-control-sm @error('nid_number') is-invalid @enderror"
                                       placeholder="National ID Number"
                                       value="{{ old('nid_number', $info->nid_number ?? '') }}">
                                @error('nid_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Birth Certificate No</label>
                                <input type="text" name="birth_certificate_no"
                                       class="form-control form-control-sm @error('birth_certificate_no') is-invalid @enderror"
                                       placeholder="Birth Certificate Number"
                                       value="{{ old('birth_certificate_no', $info->birth_certificate_no ?? '') }}">
                                @error('birth_certificate_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header">
                        <h6 class="card-title fw-semibold mb-0 p-0">
                            <i class="bi bi-telephone me-1"></i>Contact Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Phone</label>
                                <input type="text" name="phone"
                                       class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                       placeholder="Phone Number"
                                       value="{{ old('phone', $info->phone ?? '') }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name"
                                       class="form-control form-control-sm @error('emergency_contact_name') is-invalid @enderror"
                                       placeholder="Emergency Contact Name"
                                       value="{{ old('emergency_contact_name', $info->emergency_contact_name ?? '') }}">
                                @error('emergency_contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Emergency Contact Phone</label>
                                <input type="text" name="emergency_contact_phone"
                                       class="form-control form-control-sm @error('emergency_contact_phone') is-invalid @enderror"
                                       placeholder="Emergency Contact Phone"
                                       value="{{ old('emergency_contact_phone', $info->emergency_contact_phone ?? '') }}">
                                @error('emergency_contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Emergency Contact Relation</label>
                                <select name="emergency_contact_relation"
                                        class="form-select form-select-sm @error('emergency_contact_relation') is-invalid @enderror">
                                    <option value="">-- Select Relation --</option>
                                    @foreach(['Father', 'Mother', 'Brother', 'Sister', 'Spouse', 'Guardian', 'Other'] as $relation)
                                    <option value="{{ $relation }}" {{ old('emergency_contact_relation', $info->emergency_contact_relation ?? '') == $relation ? 'selected' : '' }}>
                                        {{ $relation }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('emergency_contact_relation')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header">
                        <h6 class="card-title fw-semibold mb-0 p-0">
                            <i class="bi bi-geo-alt me-1"></i>Address
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Present Address</label>
                                <textarea name="present_address" rows="2"
                                          class="form-control form-control-sm @error('present_address') is-invalid @enderror"
                                          placeholder="Present Address">{{ old('present_address', $info->present_address ?? '') }}</textarea>
                                @error('present_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold small">Permanent Address</label>
                                <textarea name="permanent_address" rows="2"
                                          class="form-control form-control-sm @error('permanent_address') is-invalid @enderror"
                                          placeholder="Permanent Address">{{ old('permanent_address', $info->permanent_address ?? '') }}</textarea>
                                @error('permanent_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label fw-bold small">City</label>
                                <input type="text" name="city"
                                       class="form-control form-control-sm @error('city') is-invalid @enderror"
                                       placeholder="City"
                                       value="{{ old('city', $info->city ?? '') }}">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label fw-bold small">District</label>
                                <input type="text" name="district"
                                       class="form-control form-control-sm @error('district') is-invalid @enderror"
                                       placeholder="District"
                                       value="{{ old('district', $info->district ?? '') }}">
                                @error('district')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label fw-bold small">Division</label>
                                <select name="division"
                                        class="form-select form-select-sm @error('division') is-invalid @enderror">
                                    <option value="">-- Select Division --</option>
                                    @foreach(['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Sylhet', 'Barisal', 'Rangpur', 'Mymensingh'] as $division)
                                    <option value="{{ $division }}" {{ old('division', $info->division ?? '') == $division ? 'selected' : '' }}>
                                        {{ $division }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('division')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label fw-bold small">Postal Code</label>
                                <input type="text" name="postal_code"
                                       class="form-control form-control-sm @error('postal_code') is-invalid @enderror"
                                       placeholder="Postal Code"
                                       value="{{ old('postal_code', $info->postal_code ?? '') }}">
                                @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label fw-bold small">Country</label>
                                <input type="text" name="country"
                                       class="form-control form-control-sm @error('country') is-invalid @enderror"
                                       placeholder="Country"
                                       value="{{ old('country', $info->country ?? 'Bangladesh') }}">
                                @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </form>

</section>

@endsection

@section('scripts')
<script>
    document.getElementById('profile_photo_input').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const preview  = document.getElementById('photo-preview');
            const initials = document.getElementById('photo-initials');

            preview.src = e.target.result;
            preview.style.display = 'block';

            if (initials) {
                initials.style.cssText = initials.style.cssText + 'display:none !important;';
            }
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection
