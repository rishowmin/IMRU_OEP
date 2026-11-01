<div class="modal fade" id="enrollModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <form action="{{ route('teacher.enrollments.store') }}" method="POST">
                @csrf

                @php $isActive = old('is_active', 1); @endphp

                <div class="modal-header bg-theme text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-bookmark-plus"></i>
                        @yield('title')
                    </h5>
                    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-sm-6">
                            {{-- Course ID --}}
                            <div class="row align-items-baseline mb-2">
                                <div class="col-sm-12">
                                    <label for="course_id" class="form-label fw-bold">
                                        <small>Course Title & Code</small>
                                        <small class="text-danger">*</small>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-select @error('course_id') is-invalid @elseif(old('course_id')) is-valid @enderror" name="course_id" id="course_id">
                                            <option selected disabled>Select Course</option>
                                            @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                {{ $course->course_title }} - [{{ $course->course_code }}]
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        @error('course_id')
                                        <div class="invalid-feedback d-block">
                                            <i class="bi bi-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                        @else
                                        @if(old('course_id'))
                                        <div class="valid-feedback d-block">
                                            <i class="bi bi-check-circle"></i>
                                            Looks good!
                                        </div>
                                        @endif
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="row align-items-baseline">
                                <div class="col-sm-12">
                                    <label for="is_active" class="form-label fw-bold"><small>Status</small></label>
                                    <div class="input-group">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="is_active" value="0">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $isActive ? 'checked' : '' }} onchange="updateLabelText(this)">
                                            <label class="form-check-label ms-2" for="is_active" id="isActiveLabel">
                                                <span class="badge {{ $isActive ? 'bg-success' : 'bg-danger' }}">
                                                    <i class="bi {{ $isActive ? 'bi-check-square' : 'bi-x-square' }} me-1"></i>
                                                    {{ $isActive ? 'Active' : 'Inactive' }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            {{-- Student --}}
                            <div class="row align-items-baseline">
                                <div class="col-sm-12">
                                    <label for="student-search" class="form-label fw-bold">
                                        <small>Student's Name</small>
                                        <small class="text-danger">*</small>
                                    </label>

                                    @error('student_id')
                                    <div class="alert alert-danger py-1 px-2 small mb-2">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                    @enderror

                                    {{-- Hidden inputs (populated by JS) --}}
                                    <div class="student-picker">
                                        <div class="student-hidden-inputs"></div>

                                        <div class="border border-theme rounded-2 overflow-hidden">
                                            <div class="d-flex align-items-center gap-2 px-3 border-bottom border-theme bg-white">
                                                <i class="bi bi-search text-muted" style="font-size:13px;"></i>
                                                <input type="text" class="student-search form-control form-control-sm border-0 shadow-none p-0"
                                                    placeholder="Search students by name or email…" autocomplete="off">
                                            </div>

                                            <div class="student-list overflow-auto" style="max-height:137px;"
                                                data-students="{{ json_encode($students->map(fn($s) => [
                                                    'id'    => $s->id,
                                                    'name'  => $s->first_name . ' ' . $s->last_name,
                                                    'email' => $s->email,
                                                    'photo' => $s->info?->profile_photo ? asset('assets/storage/profile_photo/student/' . $s->info->profile_photo) : null,
                                                ])) }}"
                                                data-selected="{{ json_encode(array_map('intval', (array) old('student_id', []))) }}">
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top border-theme bg-light">
                                                <div class="d-flex align-items-center gap-2">
                                                    <button type="button" class="btn-select-all btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px;">Select all</button>
                                                    <button type="button" class="btn-clear-all btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px;">Clear</button>
                                                    <span class="vr"></span>
                                                    <small class="student-count text-muted fw-semibold"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="d-flex align-items-center justify-content-center gap-2 w-100">
                        <button type="button" class="btn btn-secondary btn-sm w-100" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i>
                            <span class="ms-1">Close</span>
                        </button>
                        <button type="submit" class="btn btn-outline-success btn-sm w-100">
                            <i class="bi bi-person-plus"></i>
                            <span class="ms-1">Enroll</span>
                        </button>
                    </div>
                </div>



            </form>

        </div>
    </div>
</div>

