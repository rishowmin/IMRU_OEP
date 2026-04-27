@php
    /** @var array $mappedRuleIds */
    /** @var \Illuminate\Support\Collection $allRules */
    /** @var \App\Models\Academic\Exam $exam */
@endphp

@extends('admin.layouts.app')
@section('title', 'Exams')
@section('title2', 'Exam Settings')

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
                            <i class="bi bi-gear"></i>
                            <span class="ms-1">@yield('title2')</span>
                        </h5>
                        <nav style="--bs-breadcrumb-divider: '•';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.academic.exams.index') }}">@yield('title')</a></li>
                                <li class="breadcrumb-item active">@yield('title2')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('admin.academic.exams.index') }}" class="btn btn-outline-theme btn-sm">
                            <i class="bi bi-arrow-left-square"></i>
                            <span class="ms-1">Back to List</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.academic.exams.settings.update', $exam->id) }}">
        @csrf
        @method('PUT')

        <div class="row">

            {{-- Instructions --}}
            <div class="col-lg-6 mb-3">
                @if(isset($allRules['instruction']) && $allRules['instruction']->count())
                <div class="card border-0 shadow-sm mb-0 h-auto">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="card-title fw-semibold mb-0 p-0">
                            <i class="bi bi-info-circle me-1 text-primary"></i>Instructions
                        </h6>
                        <small class="text-muted">
                            <span id="instruction-selected">{{ collect($allRules['instruction'])->filter(fn($r) => in_array($r->id, $mappedRuleIds))->count() }}</span> / {{ $allRules['instruction']->where('is_active', 1)->count() }} selected
                        </small>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($allRules['instruction'] as $rule)
                            @php
                                $isActive  = $rule->is_active == 1;
                                $isChecked = in_array($rule->id, $mappedRuleIds);
                            @endphp
                            <li class="list-group-item px-4 py-3 rule-item {{ $isChecked ? 'bg-primary bg-opacity-10' : '' }} {{ !$isActive ? 'opacity-50' : '' }}">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input rule-toggle" type="checkbox" role="switch" name="rules[]" id="rule_{{ $rule->id }}" value="{{ $rule->id }}" {{ $isChecked ? 'checked' : '' }} {{ !$isActive ? 'disabled' : '' }}>
                                    </div>
                                    <label class="form-check-label w-100" for="rule_{{ $rule->id }}" style="cursor: {{ $isActive ? 'pointer' : 'not-allowed' }};">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fw-semibold">{{ $rule->title }}</span>
                                            <div class="d-flex gap-1">
                                                @if(!$isActive)
                                                <span class="badge bg-secondary">Deactivated</span>
                                                @elseif($isChecked)
                                                <span class="badge bg-primary rule-badge">Active</span>
                                                @else
                                                <span class="badge bg-light text-dark border rule-badge">Inactive</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($rule->description)
                                        <small class="text-muted d-block mt-1">{{ $rule->description }}</small>
                                        @endif
                                    </label>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @else
                <div class="alert alert-info d-flex align-items-center gap-2 h-100">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>No instructions found.</span>
                </div>
                @endif
            </div>

            {{-- Rules --}}
            <div class="col-lg-6 mb-3">
                @if(isset($allRules['rule']) && $allRules['rule']->count())
                <div class="card border-0 shadow-sm mb-0 h-auto">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="card-title fw-semibold mb-0 p-0">
                            <i class="bi bi-shield-exclamation me-1 text-danger"></i>Rules
                        </h6>
                        <small class="text-muted">
                            <span id="rule-selected">{{ collect($allRules['rule'])->filter(fn($r) => in_array($r->id, $mappedRuleIds))->count() }}</span> / {{ $allRules['rule']->where('is_active', 1)->count() }} selected
                        </small>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($allRules['rule'] as $rule)
                            @php
                                $isActive  = $rule->is_active == 1;
                                $isChecked = in_array($rule->id, $mappedRuleIds);
                            @endphp
                            <li class="list-group-item px-4 py-3 rule-item {{ $isChecked ? 'bg-danger bg-opacity-10' : '' }} {{ !$isActive ? 'opacity-50' : '' }}">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input rule-toggle" type="checkbox" role="switch" name="rules[]" id="rule_{{ $rule->id }}" value="{{ $rule->id }}" {{ $isChecked ? 'checked' : '' }} {{ !$isActive ? 'disabled' : '' }}>
                                    </div>
                                    <label class="form-check-label w-100" for="rule_{{ $rule->id }}" style="cursor: {{ $isActive ? 'pointer' : 'not-allowed' }};">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fw-semibold">{{ $rule->title }}</span>
                                            <div class="d-flex gap-1">
                                                @if(!$isActive)
                                                <span class="badge bg-secondary">Deactivated</span>
                                                @elseif($isChecked)
                                                <span class="badge bg-danger rule-badge">Active</span>
                                                @else
                                                <span class="badge bg-light text-dark border rule-badge">Inactive</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($rule->description)
                                        <small class="text-muted d-block mt-1">{{ $rule->description }}</small>
                                        @endif
                                    </label>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @else
                <div class="alert alert-info d-flex align-items-center gap-2 h-100">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>No rules found.</span>
                </div>
                @endif
            </div>

            {{-- Save --}}
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex justify-content-center gap-2">
                        <button type="submit" class="btn btn-outline-success">
                            <i class="bi bi-save me-1"></i>Save Settings
                        </button>
                        <a href="{{ route('admin.academic.exams.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </form>

</section>

@endsection

@section('scripts')
<script>
    // document.querySelectorAll('.rule-toggle').forEach(function (toggle) {
    //     toggle.addEventListener('change', function () {
    //         const listItem = this.closest('.rule-item');
    //         const badge = listItem.querySelector('.rule-badge');
    //         const isInstruction = listItem.closest('.card')
    //             ?.querySelector('.card-header i')
    //             ?.classList.contains('text-primary');

    //         if (this.checked) {
    //             listItem.classList.add(isInstruction ? 'bg-primary' : 'bg-danger', 'bg-opacity-10');
    //             badge.className = `badge rule-badge ${isInstruction ? 'bg-primary' : 'bg-danger'}`;
    //             badge.textContent = 'Active';
    //         } else {
    //             listItem.classList.remove('bg-primary', 'bg-danger', 'bg-opacity-10');
    //             badge.className = 'badge bg-light text-dark border rule-badge';
    //             badge.textContent = 'Inactive';
    //         }
    //     });
    // });

    document.querySelectorAll('.rule-toggle').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            const listItem = this.closest('.rule-item');
            const badge = listItem.querySelector('.rule-badge');
            const card = listItem.closest('.card');

            const isInstruction = card
                ?.querySelector('.card-header i')
                ?.classList.contains('text-primary');

            if (this.checked) {
                listItem.classList.add(isInstruction ? 'bg-primary' : 'bg-danger', 'bg-opacity-10');
                badge.className = `badge rule-badge ${isInstruction ? 'bg-primary' : 'bg-danger'}`;
                badge.textContent = 'Active';
            } else {
                listItem.classList.remove('bg-primary', 'bg-danger', 'bg-opacity-10');
                badge.className = 'badge bg-light text-dark border rule-badge';
                badge.textContent = 'Inactive';
            }

            // Update counter
            const counterId = isInstruction ? 'instruction-selected' : 'rule-selected';
            const checkedCount = card.querySelectorAll('.rule-toggle:checked').length;
            document.getElementById(counterId).textContent = checkedCount;
        });
    });
</script>
@endsection
