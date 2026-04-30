@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')

@if(session('status'))
@include('layouts.inc.common.messages.status')
@endif

<div class="pagetitle mb-0">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h1>
                            <i class="bi bi-grid"></i>
                            @yield('title')
                        </h1>
                        <nav style="--bs-breadcrumb-divider: '•';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item active"><i class="bi bi-house"></i> Dashboard</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div><!-- End Page Title -->

<section class="section dashboard">

</section>

@endsection
