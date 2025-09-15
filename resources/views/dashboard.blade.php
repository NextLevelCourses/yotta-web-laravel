@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        {{-- Breadcrumbs Minimalis --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="m-0 text-muted">Dashboard / <span class="fw-bold text-primary">Monitoring Hub</span></h5>
        </div>
        
        {{-- Introductory Text --}}
        <div class="row mb-4">
            <div class="col-12">
                <p class="text-muted">
                    Selamat datang di Hub Monitoring IoT Yotta. Silakan pilih sistem di bawah ini untuk melihat data dan analitik real-time.
                </p>
            </div>
        </div>

        {{-- Monitoring Cards --}}
        <div class="row g-4">
            @php
                $modules = [
                    ['href' => route('air-quality'), 'icon' => 'fas fa-wind', 'color' => 'primary', 'title' => 'Kualitas Udara', 'text' => 'Lihat data polusi dan kualitas udara secara real-time dari sensor.'],
                    ['href' => route('soil-test'), 'icon' => 'fas fa-seedling', 'color' => 'success', 'title' => 'Monitoring Tanah', 'text' => 'Pantau kelembaban, suhu, dan tingkat nutrisi tanah.'],
                    ['href' => '#', 'icon' => 'fas fa-solar-panel', 'color' => 'warning', 'title' => 'Panel Surya', 'text' => 'Lacak produksi energi dan efisiensi panel secara keseluruhan.'],
                    ['href' => '#', 'icon' => 'fas fa-tint', 'color' => 'info', 'title' => 'Kualitas Air', 'text' => 'Analisis tingkat kemurnian, pH, dan suhu air.'],
                    ['href' => '#', 'icon' => 'fas fa-bolt', 'color' => 'danger', 'title' => 'Konsumsi Energi', 'text' => 'Monitor penggunaan daya di berbagai perangkat dan lokasi.'],
                    ['href' => '#', 'icon' => 'fas fa-map-marker-alt', 'color' => 'secondary', 'title' => 'Pelacakan Aset', 'text' => 'Lacak lokasi dan status aset berharga secara real-time.'],
                    ['href' => '#', 'icon' => 'fas fa-lightbulb', 'color' => 'warning', 'title' => 'Pencahayaan Cerdas', 'text' => 'Kontrol dan otomatisasi sistem pencahayaan untuk efisiensi.'],
                    ['href' => route('soil-manag'), 'icon' => 'fas fa-leaf', 'color' => 'success', 'title' => 'Soil Management', 'text' => 'Kelola nutrisi, pH, dan kondisi lingkungan untuk tanaman.'],
                ];
            @endphp
            
            @foreach ($modules as $module)
            <div class="col-xl-4 col-md-6">
                <a href="{{ $module['href'] }}" class="text-decoration-none">
                    <div class="card card-h-100 text-center shadow-sm rounded-3 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-center align-items-center mb-3 mx-auto icon-bg-{{ $module['color'] }}">
                                <i class="{{ $module['icon'] }} fa-2x text-{{ $module['color'] }}"></i>
                            </div>
                            <h5 class="card-title fw-bold">{{ $module['title'] }}</h5>
                            <p class="card-text text-muted">{{ $module['text'] }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
@endsection

@push('styles')
<style>
    .icon-bg-primary { background-color: rgba(85, 110, 230, 0.1); }
    .icon-bg-success { background-color: rgba(52, 195, 143, 0.1); }
    .icon-bg-warning { background-color: rgba(241, 180, 76, 0.1); }
    .icon-bg-info { background-color: rgba(23, 162, 184, 0.1); }
    .icon-bg-danger { background-color: rgba(244, 106, 106, 0.1); }
    .icon-bg-secondary { background-color: rgba(108, 117, 125, 0.1); }
    
    .icon-bg-primary, .icon-bg-success, .icon-bg-warning, .icon-bg-info, .icon-bg-danger, .icon-bg-secondary {
        width: 60px;
        height: 60px;
        border-radius: 50%;
    }

    /* Card hover effect */
    .hover-lift {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
</style>
@endpush
