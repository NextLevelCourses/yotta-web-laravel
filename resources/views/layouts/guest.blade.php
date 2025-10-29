<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Otentikasi | Yotta Aksara Energy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Dashboard IoT Profesional" name="description" />
    <meta content="Yotta Aksara" name="author" />
    
    <!-- (PENTING) CSRF Token untuk logika Fetch API -->
    <!-- Di file .blade.php Anda, gunakan: <meta name="csrf-token" content="{{ csrf_token() }}"> -->
    <meta name="csrf-token" content="PLACEHOLDER_CSRF_TOKEN"> 

    <link rel="shortcut icon" href="https://placehold.co/32x32/00994C/FFFFFF?text=YAE">

    <!-- CSS (Menggunakan placeholder CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <!-- AOS untuk animasi fade-up kartu -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    
    <!-- Style untuk layout kartu terpusat -->
    <style>
        :root {
            --yae-green: #00994C;
            --yae-yellow: #FFD700;
            --yae-dark: #0b2e13;
            --yae-light-gray: #f8f9fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--yae-light-gray);
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
            background-image: url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1770&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .login-wrapper::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(11, 46, 19, 0.7); 
            z-index: 1;
        }

        .login-card {
            width: 100%;
            max-width: 450px; 
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            z-index: 2; 
        }
        
        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            text-decoration: none;
        }
        .login-logo img {
            height: 32px;
        }
        .login-logo span {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--yae-green);
        }

    </style>
</head>
<body>
    <!-- Menggunakan layout login-wrapper -->
    <div class="login-wrapper">
        <!-- Menggunakan layout login-card -->
        <div class="login-card card" data-aos="fade-up">
            <div class="card-body p-4 p-md-5">

                <!-- Logo (disesuaikan untuk di dalam kartu) -->
                <a class="login-logo" href="landing_page.html">
                    <img src="{{ asset('assets/images/YAE_Image.png') }}" alt="Logo">
                    <span>Yotta Aksara Energy</span>
                </a>

                <!-- (BARU) Konten akan di-yield di sini -->
                @yield('content')
                

                <!-- Footer kembali ke beranda dari versi awal -->
                <div class="mt-4 pt-3 text-center border-top">
                    <a href="{{ url('/') }}" class="text-decoration-none text-muted">
                        <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Beranda
                    </a>
                </div>

            </div>  
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    <script>
        // Inisialisasi AOS (milik layout)
        AOS.init({
            duration: 700,
            once: true,
            easing: 'ease-out-cubic',
        });
    </script>

    <!-- (BARU) Stack untuk skrip khusus halaman -->
    <!-- @stack('scripts') -->
</body>
</html>
