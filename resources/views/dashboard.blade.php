@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">IoT Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Monitoring Hub</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    {{-- Introductory text --}}
    <div class="row">
        <div class="col-12">
            <div class="py-3">
                <p class="text-muted">
                    Welcome to your IoT Monitoring Hub. Please select a system below to view its real-time data and analytics.
                </p>
            </div>
        </div>
    </div>

    <!-- Container for monitoring system cards -->
    <div class="row">
        <!-- Air Quality Monitoring Card -->
        <div class="col-xl-4 col-md-6">
            <a href="{{ route('air-quality') }}" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                             style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(85, 110, 230, 0.1);">
                            <i class="fas fa-wind fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title">Air Quality</h5>
                        <p class="card-text text-muted">View real-time pollution and air quality data from sensors.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Soil Monitoring Card -->
        <div class="col-xl-4 col-md-6">
            <a href="{{ route('soil-test') }}" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                             style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(52, 195, 143, 0.1);">
                            <i class="fas fa-seedling fa-2x text-success"></i>
                        </div>
                        <h5 class="card-title">Soil Monitoring</h5>
                        <p class="card-text text-muted">Monitor soil moisture, temperature, and nutrient levels.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Solar Panel Monitoring Card -->
        <div class="col-xl-4 col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                             style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(241, 180, 76, 0.1);">
                            <i class="fas fa-solar-panel fa-2x text-warning"></i>
                        </div>
                        <h5 class="card-title">Solar Panel</h5>
                        <p class="card-text text-muted">Track energy production and overall panel efficiency.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <!-- end row -->
@endsection

@push('styles')
<style>
    /* Card hover effect */
    .card.card-h-100 {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        height: 100%;
    }
    .card.card-h-100:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .card a {
        color: inherit; /* Prevent default blue link color */
        text-decoration: none;
    }
</style>
@endpush
