@extends('layouts.app')

@section('content')
<div class="dashboard-bg">
    <div class="container py-5">
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold text-dark mb-2" style="font-size: 2.2rem;">Dashboard IoT Aksara Yotta</h1>
                <p class="text-muted fs-5 mb-0">
                    Selamat datang! Berikut adalah data Real Time.
                </p>
            </div>
            <div class="col-md-4 text-md-end d-none d-md-block">
                <img src="{{ asset('assets/images/YAE_Image.png') }}" alt="Yotta Aksara Energy Logo" class="img-fluid" style="max-height: 60px;">
            </div>
        </div>

        <div id="solutions" class="row g-4 text-center">
            <div class="col-md-6 col-lg-3">
                <div class="card sidebar-alert h-100 shadow-sm">
                    <a href="{{ route('stasiun-cuaca') }}" class="text-decoration-none text-dark">
                        <div class="card-body py-5 px-4 d-flex flex-column align-items-center">
                            <div class="icon-shape icon-shape-danger rounded-circle mb-4">
                                <i class="bi bi-router-fill fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Stasiun Cuaca
                                <i class="bi bi-info-circle-fill text-danger info-badge" data-bs-toggle="tooltip" title="Jaringan IoT dengan jangkauan luas dan stabil untuk berbagai sensor."></i>
                            </h5>
                            <p class="text-muted small text-center">
                                Solusi IoT area luas dengan konektivitas handal dan monitoring sensor real-time.
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card sidebar-alert h-100 shadow-sm">
                    <a href="{{ route('soil-test') }}" class="text-decoration-none text-dark">
                        <div class="card-body py-5 px-4 d-flex flex-column align-items-center">
                            <div class="icon-shape icon-shape-danger rounded-circle mb-4">
                                <i class="bi bi-router-fill fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Soil Test
                                <i class="bi bi-info-circle-fill text-danger info-badge" data-bs-toggle="tooltip" title="Jaringan IoT dengan jangkauan luas dan stabil untuk berbagai sensor."></i>
                            </h5>
                            <p class="text-muted small text-center">
                                Solusi IoT area luas dengan konektivitas handal dan monitoring sensor real-time.
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card sidebar-alert h-100 shadow-sm">
                    <a href="{{ route('monitoring.solar-dome') }}" class="text-decoration-none text-dark">
                        <div class="card-body py-5 px-4 d-flex flex-column align-items-center">
                            <div class="icon-shape icon-shape-danger rounded-circle mb-4">
                                <i class="bi bi-router-fill fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Solar Dome
                                <i class="bi bi-info-circle-fill text-danger info-badge" data-bs-toggle="tooltip" title="Jaringan IoT dengan jangkauan luas dan stabil untuk berbagai sensor."></i>
                            </h5>
                            <p class="text-muted small text-center">
                                Solusi IoT area luas dengan konektivitas handal dan monitoring sensor real-time.
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card sidebar-alert h-100 shadow-sm">
                    <a href="{{ route('monitoring.lora') }}" class="text-decoration-none text-dark">
                        <div class="card-body py-5 px-4 d-flex flex-column align-items-center">
                            <div class="icon-shape icon-shape-danger rounded-circle mb-4">
                                <i class="bi bi-router-fill fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Lora Monitoring
                                <i class="bi bi-info-circle-fill text-danger info-badge" data-bs-toggle="tooltip" title="Jaringan IoT dengan jangkauan luas dan stabil untuk berbagai sensor."></i>
                            </h5>
                            <p class="text-muted small text-center">
                                Solusi IoT area luas dengan konektivitas handal dan monitoring sensor real-time.
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
