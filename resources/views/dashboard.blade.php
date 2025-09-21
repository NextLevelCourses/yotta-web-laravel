@extends('layouts.app')

{{-- Bootstrap Icons --}}
@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('content')
    <div class="bg-light">
        <div class="container py-5">

            {{-- Hero Section --}}
            <div class="text-center py-2">
                <div class="d-flex justify-content-center align-items-center mb-4">
                    <img src="{{ asset('assets/images/YAE_Image.png') }}" alt="Yotta Aksara Energy Logo" class="img-fluid me-3"
                        style="max-height: 80px;">
                    <h1 class="fw-bold text-dark m-0" style="font-size: 2rem;">
                        YOTTA AKSARA ENERGI
                    </h1>
                </div>

                <h2 class="fw-bold text-primary mb-3">
                    Mendorong Transformasi Industri 4.0
                </h2>
                <p class="lead text-secondary mx-auto" style="max-width: 750px;">
                    Industri modern membutuhkan <strong>kecepatan, efisiensi, dan data real-time</strong>.
                    Kami menghadirkan <strong>Industrial Internet of Things (IIoT)</strong> yang
                    memungkinkan pabrik, energi, dan bisnis Anda beroperasi lebih <em>cerdas, hemat, dan aman</em>.
                </p>
                <div class="mt-4">
                    <a href="#solutions" class="btn btn-primary btn-lg px-4 me-sm-3 fw-bold">
                        Jelajahi Solusi
                    </a>
                    <a href="#contact" class="btn btn-outline-dark btn-lg px-4">
                        Konsultasi Gratis
                    </a>
                </div>
            </div>

            {{-- Solutions Section --}}
            <div id="solutions" class="py-2">
                <h3 class="text-center fw-bold mb-4">
                    Solusi IIoT untuk Masa Depan Industri
                </h3>
                <p class="text-center text-muted mb-5 mx-auto" style="max-width: 700px;">
                    Dari otomasi pabrik hingga manajemen energi, teknologi kami dirancang
                    untuk <strong>membangun keunggulan kompetitif</strong> dan
                    membuat bisnis Anda <strong>selangkah lebih maju</strong>.
                </p>

                <div class="row text-center g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 rounded shadow-sm h-100 border-top border-primary border-3">
                            <i class="bi bi-gear-fill fs-1 text-primary"></i>
                            <h5 class="fw-bold mt-3">Otomasi & Kontrol</h5>
                            <p class="text-muted mb-0">
                                Produksi tanpa hambatan dengan integrasi mesin dan sistem kontrol cerdas.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 rounded shadow-sm h-100 border-top border-success border-3">
                            <i class="bi bi-lightning-charge-fill fs-1 text-success"></i>
                            <h5 class="fw-bold mt-3">Energi & Aset</h5>
                            <p class="text-muted mb-0">
                                Optimalkan konsumsi energi dan lakukan <em>predictive maintenance</em> mesin Anda.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 rounded shadow-sm h-100 border-top border-warning border-3">
                            <i class="bi bi-bar-chart-line-fill fs-1 text-warning"></i>
                            <h5 class="fw-bold mt-3">Monitoring Produksi</h5>
                            <p class="text-muted mb-0">
                                Dashboard OEE real-time untuk menemukan bottleneck lebih cepat.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-4 rounded shadow-sm h-100 border-top border-danger border-3">
                            <i class="bi bi-shield-check fs-1 text-danger"></i>
                            <h5 class="fw-bold mt-3">Keselamatan & Kepatuhan</h5>
                            <p class="text-muted mb-0">
                                Sensor HSE pintar memastikan keamanan karyawan & standar regulasi terpenuhi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
