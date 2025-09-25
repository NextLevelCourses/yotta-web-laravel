<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>Selamat Datang | Yotta Aksara Energy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Dashboard IoT Profesional" name="description" />
    <meta content="Yotta Aksara" name="author" />
    <link rel="shortcut icon" href="{{ asset('assets/images/YAE_Image.png') }}">

    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-12">
                    <div class="auth-bg d-flex align-items-center min-vh-100 position-relative overflow-hidden">
                        <!-- Overlay -->
                        <div class="bg-overlay bg-primary position-absolute top-0 start-0 w-100 h-100"></div>

                        <!-- Bubble background -->
                        <ul class="bg-bubbles position-absolute top-0 start-0 w-100 h-100 m-0 p-0">
                            <li></li><li></li><li></li><li></li><li></li>
                            <li></li><li></li><li></li><li></li><li></li>
                        </ul>

                        <!-- Content -->
                        <div class="container position-relative">
                            <div class="row justify-content-center text-center">
                                <div class="col-lg-8 col-md-10">
                                    <!-- Logo -->
                                    <div class="mb-4">
                                        <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2">
                                            <img src="{{ asset('assets/images/YAE_Image.png') }}" alt="Logo" height="42">
                                            <span class="logo-txt fs-2 fw-bold text-white">Yotta Aksara Energy</span>
                                        </a>
                                    </div>

                                    <!-- Headline -->
                                    <h1 class="fw-bold text-white display-6 display-md-5">
                                        Kendalikan IoT Anda dengan Presisi
                                    </h1>
                                    <p class="text-white-50 fs-6 fs-md-5 mt-3">
                                        Solusi monitoring & kontrol perangkat IoT yang aman, handal, dan dirancang untuk kebutuhan industri modern.
                                    </p>

                                    <!-- Action Buttons -->
                                    <div class="mt-4 d-flex flex-column flex-sm-row justify-content-center gap-3">
                                        @auth
                                            <a href="{{ url('/dashboard') }}" class="btn btn-success btn-lg shadow">
                                                <i class="mdi mdi-view-dashboard me-1"></i> Masuk ke Dashboard
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-light btn-lg shadow">
                                                <i class="mdi mdi-login me-1"></i> Mulai Sekarang
                                            </a>
                                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg shadow-sm">
                                                <i class="mdi mdi-account-plus me-1"></i> Buat Akun
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Content -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>
</body>
</html>
