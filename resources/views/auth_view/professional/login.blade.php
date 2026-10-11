@extends('layouts.auth')
@section('title', 'Corporate Login')

@section('content')

<div class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-6 d-flex flex-column align-items-center justify-content-center">

                <div class="card">
                    <div class="card-header">
                        <div class="back-to-home">
                            <a href="{{ url('/') }}" class="btn btn-sm btn-outline-theme"><i class="bi bi-arrow-left"></i></a>
                        </div>
                        <div class="row justify-content-center">
                            <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto">
                                <img src="{{ asset('assets/admin/img/brand/logo.png') }}" alt="IMRU OEP Logo" width="100%">
                            </a>
                        </div>
                        <div class="status-header">
                            <span class="badge bg-warning-light border border-warning text-dark rounded-pill px-3 py-2 mb-0">
                                <i class="bi bi-clock-history me-1"></i>
                                In Progress
                            </span>
                        </div>

                    </div>

                    <div class="card-body">
                        <div class="row g-3 justify-content-center">
                            <div class="col-lg-12">

                                {{-- Coming Soon --}}
                                <div class="Coming-soon text-center px-5 pb-3">
                                    <h4 class="display-1 text-warning"><i class="bi bi-cone-striped"></i></h4>
                                    <h3 class="text-theme fw-bold mb-2">Corporate Module Coming Soon!</h3>
                                    <p class="text-muted mb-3">We're working on something great.</p>
                                    <p class="text-muted mb-3">The Corporate Examination Module is currently under development. We're working to bring you recruitment assessments and corporate examination management features.</p>
                                    <p class="text-muted mb-0">Thank you for your patience and interest in IMRU OEP.</p>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="row justify-content-center text-center mt-2">
                    <div class="credits small">
                        Developed by <a href="https://github.com/rishowmin" target="_blank">Muhammad Raisul Islam, IIT, JU</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const candidateBtn    = document.getElementById('showCandidateLogin');
    const recruiterBtn    = document.getElementById('showRecruiterLogin');
    const candidateForm   = document.getElementById('candidateLoginForm');
    const recruiterForm   = document.getElementById('recruiterLoginForm');

    function showCandidate() {
        candidateForm.style.display = 'block';
        recruiterForm.style.display = 'none';
        candidateBtn.classList.add('active');
        recruiterBtn.classList.remove('active');
        localStorage.setItem('corporateLoginTab', 'candidate');
    }

    function showRecruiter() {
        recruiterForm.style.display = 'block';
        candidateForm.style.display = 'none';
        recruiterBtn.classList.add('active');
        candidateBtn.classList.remove('active');
        localStorage.setItem('corporateLoginTab', 'recruiter');
    }

    candidateBtn.addEventListener('click', showCandidate);
    recruiterBtn.addEventListener('click', showRecruiter);

    // Restore last active tab
    const savedTab = localStorage.getItem('corporateLoginTab') || 'candidate';
    if (savedTab === 'recruiter') {
        showRecruiter();
    } else {
        showCandidate();
    }

    // ======= Candidate Password Toggle =======
    const candidatePasswordToggle = document.getElementById('candidate_password_toggle');
    const candidatePasswordInput  = document.getElementById('candidate_password');
    const candidatePasswordIcon   = document.getElementById('candidate_password_icon');

    if (candidatePasswordToggle) {
        candidatePasswordToggle.addEventListener('click', function () {
            if (candidatePasswordInput.type === 'password') {
                candidatePasswordInput.type = 'text';
                candidatePasswordIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                candidatePasswordInput.type = 'password';
                candidatePasswordIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    }

    // ======= Recruiter Password Toggle =======
    const recruiterPasswordToggle = document.getElementById('recruiter_password_toggle');
    const recruiterPasswordInput  = document.getElementById('recruiter_password');
    const recruiterPasswordIcon   = document.getElementById('recruiter_password_icon');

    if (recruiterPasswordToggle) {
        recruiterPasswordToggle.addEventListener('click', function () {
            if (recruiterPasswordInput.type === 'password') {
                recruiterPasswordInput.type = 'text';
                recruiterPasswordIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                recruiterPasswordInput.type = 'password';
                recruiterPasswordIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    }

});
</script>
@endsection
