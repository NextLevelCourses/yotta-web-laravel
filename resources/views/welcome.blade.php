<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Yotta Aksara Energy | Solusi IoT Profesional</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Platform monitoring dan kontrol IoT profesional untuk industri modern." />
    <meta name="author" content="Yotta Aksara Energy" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/YAE_Image.png') }}">

    <!-- CSS External -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <style>
        :root {
            --yae-green: #00994C;
            --yae-yellow: #FFD700;
            --yae-dark: #0b2e13;
            --yae-light-gray: #f8f9fa;
        }

        body {
            scroll-behavior: smooth;
            font-family: 'Inter', sans-serif; /* Menggunakan font yang lebih modern */
        }

        /* --- Navbar --- */
        .navbar-light { 
            background: transparent;
            transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out; 
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .navbar-light.navbar-scrolled {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        
        .navbar-brand span { 
            color: var(--yae-green); 
            font-weight: 700;
        }
        .navbar-light .nav-link { 
            font-weight: 500; 
            color: #333; 
            transition: 0.2s; 
            padding: 0.5rem 1rem;
        }
        /* Saat di hero, navbar link berwarna putih */
        .navbar-light:not(.navbar-scrolled) .nav-link {
            color: #fff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .navbar-light:not(.navbar-scrolled) .nav-link:hover {
            color: var(--yae-yellow);
        }
        .navbar-light.navbar-scrolled .nav-link:hover {
            color: var(--yae-green);
        }
        .navbar-light:not(.navbar-scrolled) .navbar-brand span {
             color: #fff;
        }

        /* --- Tombol (Buttons) --- */
        .btn {
            border-radius: 0.5rem; /* Sudut lebih bulat */
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-yae { 
            background: var(--yae-green); 
            color: #fff; 
            border: none; 
        }
        .btn-yae:hover { 
            background: #007a3b; 
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,153,76,0.2);
        }
        
        /* Tombol kuning baru untuk CTA */
        .btn-yae-yellow {
            background: var(--yae-yellow);
            color: var(--yae-dark);
            border: none;
        }
        .btn-yae-yellow:hover {
            background: #e6c000;
            color: #000;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(255,215,0,0.3);
        }
        
        /* Tombol outline kuning baru */
        .btn-outline-yae-yellow {
            border: 2px solid var(--yae-yellow);
            color: var(--yae-yellow);
            background: transparent;
        }
        .btn-outline-yae-yellow:hover {
            background: var(--yae-yellow);
            color: var(--yae-dark);
            transform: translateY(-3px);
        }

        /* Tombol outline hijau (dipakai di navbar) */
        .btn-outline-yae {
             border: 2px solid var(--yae-yellow); 
             color: var(--yae-yellow);
        }
        .btn-outline-yae:hover { 
             background: var(--yae-yellow); 
             color: var(--yae-dark); 
             transform: translateY(-3px);
        }
        /* Transparansi saat di hero */
        .navbar-light:not(.navbar-scrolled) .btn-yae {
            background: #fff;
            color: var(--yae-green);
        }
        .navbar-light:not(.navbar-scrolled) .btn-outline-yae {
            border-color: #fff;
            color: #fff;
        }
        .navbar-light:not(.navbar-scrolled) .btn-outline-yae:hover {
            background: #fff;
            color: var(--yae-green);
            border-color: #fff;
        }


        /* --- Hero Section (REVISI) --- */
        .hero-section {
            color: #fff;
            padding: 160px 0 100px 0; /* Padding lebih besar */
            position: relative;
            background-image: url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1770&q=80');
            background-size: cover;
            background-position: center;
        }
        /* Overlay gelap untuk keterbacaan teks */
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(11, 46, 19, 0.7); /* Overlay hijau tua transparan */
            z-index: 1;
        }
        /* Konten hero harus di atas overlay */
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        .hero-section h1 { 
            font-size: 3.2rem; /* Sedikit lebih besar */
            font-weight: 800; 
            line-height: 1.2;
        }
        .hero-section p { 
            font-size: 1.25rem; 
            opacity: 0.95; 
            max-width: 600px; /* Batasi lebar paragraf */
            margin-left: auto;
            margin-right: auto;
        }

        /* --- Section Umum --- */
        section {
            padding: 80px 0;
        }
        .section-title {
            color: var(--yae-green);
            font-weight: 700;
        }

        /* --- Tentang Kami --- */
        #tentang-kami img {
            border-radius: 0.75rem; /* Sudut lebih bulat */
        }
        
        /* --- Layanan (REVISI) --- */
        #layanan {
            background-color: var(--yae-light-gray);
        }
        /* Kartu layanan baru yang lebih menarik */
        .service-card {
            border: none;
            border-radius: 12px;
            background: #fff;
            transition: all 0.3s ease-in-out;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            height: 100%;
            padding: 2rem;
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,153,76,0.15);
        }
        .service-card .icon-wrapper {
            font-size: 3rem;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--yae-green), #00b359);
            color: #fff;
            box-shadow: 0 5px 15px rgba(0,153,76,0.3);
        }
        .service-card h4 {
            color: var(--yae-dark);
            font-weight: 600;
        }

        /* --- Footer --- */
        footer { 
            background: var(--yae-dark); 
            color: #ccc; 
            padding: 60px 0 40px 0;
        }
        footer a { 
            color: #aaa; 
            transition: 0.2s; 
            text-decoration: none;
        }
        footer a:hover { 
            color: var(--yae-yellow) !important; 
        }
        footer .list-unstyled li {
            margin-bottom: 0.5rem;
        }
        footer .social-icons a {
            font-size: 1.5rem;
            margin-right: 1rem;
        }

    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#navbar-yae" data-bs-offset="100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="navbar-yae">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="https://aksarayotta.com/">
                <!-- Ganti dengan logo asli jika ada, atau placeholder -->
                <img src="{{ asset('assets/images/YAE_Image.png') }}" alt="Logo" height="32">
                <span class="fs-5">Yotta Aksara Energy</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li ></li>
                    <li ></li>
                    <li ></li>
                </ul>
                <div class="d-flex gap-2">
                    <!-- Tampilkan ini jika user belum login -->
                    <a href="{{ route('login') }}" class="btn btn-yae"><i class="mdi mdi-login me-1"></i> Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-yae"><i class="mdi mdi-account-plus me-1"></i> Daftar</a>
                    <!-- Tampilkan ini jika user sudah login
                    <a href="#" class="btn btn-yae"><i class="mdi mdi-view-dashboard me-1"></i> Dashboard</a>
                    -->
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="mb-3" data-aos="fade-up">Solusi Monitoring & Kontrol IoT Terintegrasi</h1>
            <p class="mb-4" data-aos="fade-up" data-aos-delay="100">Pantau, analisis, dan kendalikan data Anda dalam satu platform pintar.</p>
            <div data-aos="fade-up" data-aos-delay="200">
                <!-- Menggunakan tombol kuning baru untuk CTA Utama -->
                <a href="https://aksarayotta.com/" target="_blank" class="btn btn-outline-yae-yellow btn-lg">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </section>

    <!-- Tentang Kami -->
    <section id="tentang-kami">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <!-- Placeholder gambar yang lebih menarik -->
                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1770&q=80" alt="Tentang Kami" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <span class="badge bg-success-subtle text-success mb-2 p-2">Tentang Kami</span>
                    <h2 class="section-title fw-bold mb-3">Siapa Yotta Aksara Energy?</h2>
                    <p class="text-muted fs-5">Kami menyediakan solusi teknologi IoT untuk monitoring dan kontrol perangkat industri secara real-time dan terukur.</p>
                    <p class="text-muted">Kami membantu sektor energi, pertanian, dan manufaktur untuk bertransformasi digital.</p>
                    <a href="https://aksarayotta.com/" target="_blank" class="btn btn-outline-success mt-3">Info Lengkap Perusahaan</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan -->
    <section id="layanan" class="bg-light">
        <div class="container text-center">
            <h2 class="section-title fw-bold mb-3" data-aos="fade-up">Solusi Unggulan Kami</h2>
            <p class="text-muted fs-5 mb-5" data-aos="fade-up" data-aos-delay="100">Dirancang untuk skalabilitas, keamanan, dan kemudahan integrasi.</p>
            
            <div class="row g-4">
                <!-- Item Layanan 1 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card text-center">
                        <div class="icon-wrapper">
                            <i class="mdi mdi-weather-partly-cloudy"></i>
                        </div>
                        <h4 class="mb-2">Monitoring Stasiun Cuaca</h4>
                        <p class="text-muted">Pantau data suhu, kelembaban, curah hujan, dan kualitas udara.</p>
                    </div>
                </div>
                <!-- Item Layanan 2 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card text-center">
                        <div class="icon-wrapper">
                            <i class="mdi mdi-radio-tower"></i>
                        </div>
                        <h4 class="mb-2">Monitoring LoRaWAN</h4>
                        <p class="text-muted">Jaringan sensor jarak jauh untuk berbagai lokasi.</p>
                    </div>
                </div>
                <!-- Item Layanan 3 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card text-center">
                        <div class="icon-wrapper">
                            <i class="mdi mdi-flask-outline"></i>
                        </div>
                        <h4 class="mb-2">Soil Monitoring</h4>
                        <p class="text-muted">Analisis kelembaban tanah, pH, dan nutrisi untuk sektor pertanian.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="text-white-50">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('assets/images/YAE_Image.png') }}" alt="Logo" height="30">
                        <span class="fs-5 fw-bold text-white">Yotta Aksara Energy</span>
                    </div>
                    <p>Solusi IoT profesional untuk industri modern.</p>
                    <div class="d-flex gap-3 mt-3 social-icons">
                        <a href="#" class="text-white-50"><i class="mdi mdi-linkedin"></i></a>
                        <a href="#" class="text-white-50"><i class="mdi mdi-instagram"></i></a>
                        <a href="#" class="text-white-50"><i class="mdi mdi-whatsapp"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-3">Layanan Kami</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Monitoring Cuaca</a></li>
                        <li><a href="#">Soil Monitoring</a></li>
                        <li><a href="#">LoRa Monitoring</a></li>
                    </ul>
                </div>

                <div class="col-lg-5">
                    <h5 class="text-white mb-3">Kontak Kami</h5>
                    <ul class="list-unstyled">
                        <li class="d-flex mb-2"><i class="mdi mdi-map-marker-outline me-2 fs-5"></i> Bukit Cemara Tidar Blok G10 No 2, Malang</li>
                        <li class="d-flex mb-2"><i class="mdi mdi-email-outline me-2 fs-5"></i> support@yottaaksara.com</li>
                        <li class="d-flex"><i class="mdi mdi-phone-outline me-2 fs-5"></i> +62 811-2692-898</li>
                    </ul>
                </div>
            </div>

            <hr class="my-4 border-secondary">
            <div class="text-center">
                <p class="mb-0">
                    © <script>document.write(new Date().getFullYear())</script> Yotta Aksara Energy — Dibuat dengan <i class="mdi mdi-heart text-danger"></i> untuk masa depan IoT Indonesia.
                </p>
            </div>
        </div>
    </footer>

    <!-- JS External -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    
    <script>
        // Inisialisasi Feather Icons
        feather.replace();

        // Inisialisasi Bootstrap ScrollSpy
        new bootstrap.ScrollSpy(document.body, { target: '#navbar-yae', offset: 100 });
        
        // Inisialisasi AOS
        AOS.init({
            duration: 700,
            once: true,
            easing: 'ease-out-cubic',
        });

        // Skrip untuk Navbar transparan
        const navbar = document.getElementById('navbar-yae');
        if (navbar) {
            window.onscroll = function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            };
        }
    </script>
</body>
</html>
