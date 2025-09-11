@extends('layouts.app')

@section('content')
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
    {{-- Introductory text --}}
    <div class="row">
        <div class="col-12">
            <div class="py-3">
                <p class="text-muted">
                    Selamat datang di Hub Monitoring IoT Anda. Silakan pilih sistem di bawah ini untuk melihat data dan analitik real-time.
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6">
            <a href="{{ route('air-quality') }}" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                            style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(85, 110, 230, 0.1);">
                            <i class="fas fa-wind fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title">Kualitas Udara</h5>
                        <p class="card-text text-muted">Lihat data polusi dan kualitas udara secara real-time dari sensor.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-md-6">
            <a href="{{ route('soil-test') }}" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                            style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(52, 195, 143, 0.1);">
                            <i class="fas fa-seedling fa-2x text-success"></i>
                        </div>
                        <h5 class="card-title">Monitoring Tanah</h5>
                        <p class="card-text text-muted">Pantau kelembaban, suhu, dan tingkat nutrisi tanah.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                            style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(241, 180, 76, 0.1);">
                            <i class="fas fa-solar-panel fa-2x text-warning"></i>
                        </div>
                        <h5 class="card-title">Panel Surya</h5>
                        <p class="card-text text-muted">Lacak produksi energi dan efisiensi panel secara keseluruhan.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                            style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(23, 162, 184, 0.1);">
                            <i class="fas fa-tint fa-2x text-info"></i>
                        </div>
                        <h5 class="card-title">Kualitas Air</h5>
                        <p class="card-text text-muted">Analisis tingkat kemurnian, pH, dan suhu air.</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-xl-4 col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                            style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(244, 106, 106, 0.1);">
                            <i class="fas fa-bolt fa-2x text-danger"></i>
                        </div>
                        <h5 class="card-title">Konsumsi Energi</h5>
                        <p class="card-text text-muted">Monitor penggunaan daya di berbagai perangkat dan lokasi.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                            style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(108, 117, 125, 0.1);">
                            <i class="fas fa-map-marker-alt fa-2x text-secondary"></i>
                        </div>
                        <h5 class="card-title">Pelacakan Aset</h5>
                        <p class="card-text text-muted">Lacak lokasi dan status aset berharga secara real-time.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                            style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(255, 193, 7, 0.15);">
                            <i class="fas fa-lightbulb fa-2x text-warning"></i>
                        </div>
                        <h5 class="card-title">Pencahayaan Cerdas</h5>
                        <p class="card-text text-muted">Kontrol dan otomatisasi sistem pencahayaan untuk efisiensi.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-4 col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                            style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(52, 58, 64, 0.1);">
                            <i class="fas fa-shield-alt fa-2x text-dark"></i>
                        </div>
                        <h5 class="card-title">Sistem Keamanan</h5>
                        <p class="card-text text-muted">Pantau sensor gerak, kamera, dan status akses pintu.</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-xl-4 col-md-6">
            <a href="#" class="text-decoration-none">
                <div class="card card-h-100 text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center mb-3 mx-auto"
                            style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(40, 167, 69, 0.1);">
                            <i class="fas fa-leaf fa-2x text-success"></i>
                        </div>
                        <h5 class="card-title">Sistem Hidroponik</h5>
                        <p class="card-text text-muted">Kelola nutrisi, pH, dan kondisi lingkungan untuk tanaman.</p>
                    </div>
                </div>
            </a>
        </div>
        
    </div>
    @endsection
<!-- 
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
@endpush -->