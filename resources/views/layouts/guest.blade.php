<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>Otentikasi | Yotta Aksara Energy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Dashboard IoT Profesional" name="description" />
    <meta content="Yotta Aksara" name="author" />
    <link rel="shortcut icon" href="{{ asset('assets/images/YAE_Image.png') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" />
</head>
<body>
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0 min-vh-100">
                <!-- Kolom Gambar (hanya muncul di web/desktop) -->
                <div class="col-lg-7 d-none d-lg-block">
                    <div class="h-100 position-relative d-flex flex-column justify-content-center text-center text-white p-5">
                        <div class="bg-overlay bg-primary opacity-75 position-absolute top-0 start-0 w-100 h-100"></div>
                        <ul class="bg-bubbles">
                            <li></li><li></li><li></li><li></li><li></li>
                            <li></li><li></li><li></li><li></li><li></li>
                        </ul>
                        <div class="position-relative">
                            <img src="{{ asset('assets/images/YAE_Image.png') }}" alt="Logo" height="60" class="mb-4">
                            <h1 class="fw-bold display-5">Yotta Aksara Energy</h1>
                            <p class="fs-5 mt-3">Solusi monitoring & kontrol perangkat IoT yang aman, handal, dan modern.</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Form -->
                <div class="col-12 col-lg-5 d-flex align-items-center justify-content-center">
                    <div class="w-100 p-sm-5 p-4" style="max-width: 420px;">
                        <div class="text-center mb-4">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('assets/images/YAE_Image.png') }}" alt="" height="42">
                                <h4 class="mt-2 mb-0 fw-bold">Yotta Aksara</h4>
                            </a>
                        </div>

                        <!-- Yield Content -->
                        @yield('content')

                        <div class="mt-4 pt-3 text-center border-top">
                            <p class="mb-0">
                                © <script>document.write(new Date().getFullYear())</script>
                                Yotta Aksara Energy
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>
    

</body>
</html>
