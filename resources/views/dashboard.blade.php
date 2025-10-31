@extends('layouts.app')

@section('content')
<div class="dashboard-bg min-vh-100 py-5">
    <div class="container">
        {{-- Header --}}
        <div class="row mb-5 align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold text-dark mb-2 display-5">Dashboard IoT Aksara Yotta</h1>
                <p class="text-secondary fs-5 mb-0">
                    Selamat datang 👋 — pantau data real-time dari sistem IoT Anda di sini.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <img src="{{ asset('assets/images/YAE_Image.png') }}" 
                     alt="Yotta Aksara Energy Logo" 
                     class="img-fluid" 
                     style="max-height: 70px;">
            </div>
        </div>

        {{-- Main Cards --}}
        <div id="solutions" class="row g-4">
            {{-- Stasiun Cuaca --}}
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('stasiun-cuaca') }}" class="card-link">
                    <div class="card dashboard-card h-100 shadow-sm">
                        <div class="card-body text-center py-5 px-4">
                            <div class="icon-wrapper bg-gradient-primary mb-4">
                                <i class="bi bi-cloud-sun-fill fs-2 text-white"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Stasiun Cuaca 
                                <i class="bi bi-info-circle-fill text-primary" data-bs-toggle="tooltip" title="Pemantauan suhu, kelembaban, dan tekanan udara secara real-time."></i>
                            </h5>
                            <p class="text-muted small">
                                Pantau kondisi atmosfer dengan sensor yang terhubung melalui jaringan IoT.
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Soil Test --}}
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('soil-test') }}" class="card-link">
                    <div class="card dashboard-card h-100 shadow-sm">
                        <div class="card-body text-center py-5 px-4">
                            <div class="icon-wrapper bg-gradient-success mb-4">
                                <i class="bi bi-moisture fs-2 text-white"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Soil Test
                                <i class="bi bi-info-circle-fill text-success" data-bs-toggle="tooltip" title="Analisis pH tanah, kelembaban, dan nutrisi untuk pertanian cerdas."></i>
                            </h5>
                            <p class="text-muted small">
                                Optimalkan kualitas tanah dengan data sensor berbasis LoRa.
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Solar Dome --}}
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('monitoring.solar-dome') }}" class="card-link">
                    <div class="card dashboard-card h-100 shadow-sm">
                        <div class="card-body text-center py-5 px-4">
                            <div class="icon-wrapper bg-gradient-warning mb-4">
                                <i class="bi bi-brightness-high-fill fs-2 text-white"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Solar Dome
                                <i class="bi bi-info-circle-fill text-warning" data-bs-toggle="tooltip" title="Pemantauan panel surya dan konsumsi energi secara otomatis."></i>
                            </h5>
                            <p class="text-muted small">
                                Sistem cerdas untuk efisiensi energi berbasis tenaga surya.
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- LoRa Monitoring --}}
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('monitoring.lora') }}" class="card-link">
                    <div class="card dashboard-card h-100 shadow-sm">
                        <div class="card-body text-center py-5 px-4">
                            <div class="icon-wrapper bg-gradient-danger mb-4">
                                <i class="bi bi-broadcast-pin fs-2 text-white"></i>
                            </div>
                            <h5 class="fw-bold mb-2">LoRa Monitoring
                                <i class="bi bi-info-circle-fill text-danger" data-bs-toggle="tooltip" title="Jaringan komunikasi jarak jauh untuk perangkat IoT."></i>
                            </h5>
                            <p class="text-muted small">
                                Pantau sensor IoT jarak jauh dengan protokol LoRaWAN.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dashboard-bg {
        background: linear-gradient(135deg, #f8fafc, #eef2f7);
    }

    .card-link {
        text-decoration: none;
        color: inherit;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .dashboard-card {
        border: none;
        border-radius: 1rem;
        background: #fff;
        transition: all 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #4f46e5, #3b82f6);
    }

    .bg-gradient-success {
        background: linear-gradient(45deg, #10b981, #34d399);
    }

    .bg-gradient-warning {
        background: linear-gradient(45deg, #f59e0b, #fbbf24);
    }

    .bg-gradient-danger {
        background: linear-gradient(45deg, #ef4444, #f87171);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
