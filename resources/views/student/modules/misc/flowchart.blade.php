@extends('student.layouts.app')
@section('title', 'Flow Chart')

@section('content')

<div class="pagetitle mb-0">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-header-left">
                            <h1>
                                <i class="bi bi-diagram-2"></i>
                                <span class="ms-1">@yield('title')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                    <li class="breadcrumb-item active">@yield('title')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="section">

    <div class="row">
        <div class="col-lg-12">

            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <img src="{{ asset('assets/admin/img/flowcharts/student_fc.png') }}" alt="Student portal flow chart" class="img-fluid rounded">
                </div>
            </div>

        </div>
    </div>

</section>

@endsection
