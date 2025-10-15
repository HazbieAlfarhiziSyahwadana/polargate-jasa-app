<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PT Polargate Indonesia Kreasi - Solusi Multimedia dan IT Services Terpercaya">
    <title>PT Polargate Indonesia Kreasi - Multimedia & IT Services</title>
    
    <link rel="icon" type="image/png" href="{{ asset('logo/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #1e40af;
            --secondary: #3b82f6;
            --accent: #60a5fa;
            --dark: #1e3a8a;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            overflow-x: hidden;
            color: #1f2937;
        }

        html { scroll-behavior: smooth; }

        /* Preloader */
        .preloader {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s;
        }
        .preloader.fade-out { opacity: 0; pointer-events: none; }
        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Navbar */
        .navbar {
            background: rgba(30, 64, 175, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .navbar.scrolled { padding: 0.5rem 0; }
        .navbar-brand img { height: 55px; transition: transform 0.3s; }
        .navbar-brand img:hover { transform: scale(1.05); }
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            position: relative;
            font-weight: 500;
            transition: all 0.3s;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: white;
            transition: all 0.3s;
            transform: translateX(-50%);
        }
        .nav-link:hover::after, .nav-link.active::after { width: 80%; }
        .btn-login {
            background: white;
            color: var(--primary);
            padding: 0.6rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid white;
        }
        .btn-login:hover {
            background: transparent;
            color: white;
            transform: translateY(-2px);
        }

        /* Hero */
        .hero-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary), var(--accent));
            color: white;
            padding: 140px 0 100px;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .hero-floating {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            animation: float 6s ease-in-out infinite;
        }
        .hero-floating:nth-child(1) { width: 100px; height: 100px; top: 15%; left: 10%; }
        .hero-floating:nth-child(2) { width: 150px; height: 150px; top: 50%; right: 8%; animation-delay: 2s; }
        .hero-floating:nth-child(3) { width: 80px; height: 80px; bottom: 25%; left: 15%; animation-delay: 4s; }
        .hero-floating:nth-child(4) { width: 60px; height: 60px; top: 30%; right: 20%; animation-delay: 1s; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }
        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.2);
        }
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 3rem;
            opacity: 0.95;
            line-height: 1.7;
        }
        .btn-hero {
            padding: 1.1rem 3rem;
            font-size: 1.15rem;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin: 0.5rem;
            text-decoration: none;
            display: inline-block;
        }
        .btn-hero-primary {
            background: white;
            color: var(--primary);
            border: none;
        }
        .btn-hero-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        .btn-hero-outline:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-5px);
        }
        .hero-icon-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-top: 2rem;
        }
        .hero-icon-box {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .hero-icon-box:hover {
            transform: translateY(-10px);
            background: rgba(255,255,255,0.25);
        }
        .hero-icon-box i { font-size: 3rem; margin-bottom: 1rem; }
        .hero-icon-box p { margin: 0; font-weight: 600; }

        /* Stats */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        .stat-box { text-align: center; padding: 2rem; }
        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.2);
        }
        .stat-label { font-size: 1.2rem; opacity: 0.9; }

        /* Services */
        .services-section {
            padding: 100px 0;
            background: linear-gradient(180deg, #f8f9fa, #ffffff);
        }
        .section-title {
            text-align: center;
            margin-bottom: 5rem;
        }
        .section-title h2 {
            font-size: 3.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }
        .section-title p { font-size: 1.3rem; color: #6b7280; }
        .category-section { margin-bottom: 5rem; }
        .category-header { text-align: center; margin-bottom: 3rem; }
        .category-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.7rem 2rem;
            border-radius: 50px;
            font-size: 1.3rem;
            font-weight: 700;
            box-shadow: 0 5px 20px rgba(30, 64, 175, 0.3);
        }
        .service-card {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .service-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 60px rgba(30, 64, 175, 0.2);
        }
        .service-icon-container {
            position: relative;
            overflow: hidden;
            height: 280px;
            background: linear-gradient(135deg, var(--primary), var(--secondary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .service-icon-container i {
            font-size: 6rem;
            color: white;
            transition: all 0.4s;
        }
        .service-card:hover .service-icon-container i {
            transform: scale(1.2) rotate(5deg);
        }
        .service-body {
            padding: 2.5rem 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .service-name {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        .service-description {
            color: #6b7280;
            font-size: 1.05rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }
        .service-features {
            list-style: none;
            padding: 0;
            margin-bottom: 1.5rem;
            flex: 1;
        }
        .service-features li {
            padding: 0.5rem 0;
            color: #4b5563;
            display: flex;
            align-items: center;
        }
        .service-features li i {
            color: var(--secondary);
            margin-right: 0.8rem;
        }
        .service-card-footer { padding: 0 2rem 2rem 2rem; margin-top: auto; }
        .btn-service {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            display: block;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-service:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.3);
            color: white;
        }

        /* About */
        .about-section { padding: 100px 0; background: white; }
        .about-content h3 {
            color: var(--primary);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }
        .about-content p {
            font-size: 1.15rem;
            line-height: 2;
            color: #4b5563;
            margin-bottom: 1.5rem;
        }
        .feature-box {
            text-align: center;
            padding: 2.5rem 1.5rem;
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 20px;
            transition: all 0.3s;
            height: 100%;
            border: 2px solid transparent;
        }
        .feature-box:hover {
            transform: translateY(-10px);
            border-color: var(--secondary);
            box-shadow: 0 15px 40px rgba(30, 64, 175, 0.15);
        }
        .feature-box i {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .feature-box h4 {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }
        .feature-box p { color: #6b7280; font-size: 0.95rem; margin: 0; }

        /* Testimonial */
        .testimonial-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        }
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            height: 100%;
            transition: all 0.3s;
        }
        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        }
        .testimonial-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            font-weight: 700;
        }
        .testimonial-stars { color: #fbbf24; font-size: 1.2rem; margin-bottom: 1rem; }
        .testimonial-text {
            font-style: italic;
            color: #4b5563;
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }
        .testimonial-name {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.3rem;
        }
        .testimonial-position { color: #6b7280; font-size: 0.9rem; }

        /* Contact */
        .contact-section {
            padding: 100px 0;
            background: linear-gradient(180deg, #ffffff, #f8f9fa);
        }
        .contact-card {
            padding: 4rem;
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(0,0,0,0.15);
        }
        .btn-whatsapp {
            background: linear-gradient(135deg, #25d366, #20ba5a);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 1.2rem 3.5rem;
            font-size: 1.2rem;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(37, 211, 102, 0.3);
            text-decoration: none;
            display: inline-block;
        }
        .btn-whatsapp:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(37, 211, 102, 0.4);
            color: white;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, var(--dark), var(--primary));
            color: white;
            padding: 4rem 0 2rem;
        }
        .footer h5 { font-weight: 700; margin-bottom: 1.5rem; font-size: 1.3rem; }
        .footer p, .footer a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.3s;
            line-height: 2.2;
        }
        .footer a:hover { color: white; padding-left: 5px; }
        .footer-social a {
            display: inline-block;
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 45px;
            margin-right: 1rem;
            transition: all 0.3s;
        }
        .footer-social a:hover {
            background: white;
            color: var(--primary) !important;
            transform: translateY(-5px);
            padding-left: 0;
        }

        /* Float Buttons */
        .whatsapp-float {
            position: fixed;
            width: 65px;
            height: 65px;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #25d366, #20ba5a);
            color: white;
            border-radius: 50%;
            font-size: 32px;
            box-shadow: 0 5px 25px rgba(37, 211, 102, 0.5);
            z-index: 1000;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 5px 25px rgba(37, 211, 102, 0.5); }
            50% { box-shadow: 0 5px 35px rgba(37, 211, 102, 0.8); }
        }
        .whatsapp-float:hover {
            transform: scale(1.15);
            color: white;
        }
        .scroll-to-top {
            position: fixed;
            bottom: 110px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 999;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(30, 64, 175, 0.3);
        }
        .scroll-to-top:hover {
            background: var(--dark);
            transform: translateY(-5px);
        }
        .scroll-to-top.show { display: flex; }

        /* Responsive */
        @media (max-width: 992px) {
            .hero-title { font-size: 2.8rem; }
            .hero-subtitle { font-size: 1.2rem; }
            .section-title h2 { font-size: 2.5rem; }
            .hero-section { padding: 120px 0 80px; min-height: auto; }
            .service-icon-container { height: 240px; }
            .service-icon-container i { font-size: 5rem; }
        }
        @media (max-width: 768px) {
            .hero-title { font-size: 2.2rem; }
            .hero-subtitle { font-size: 1.1rem; margin-bottom: 2rem; }
            .btn-hero {
                padding: 1rem 2rem;
                font-size: 1rem;
                display: block;
                margin: 0.5rem auto;
            }
            .contact-card { padding: 2.5rem 1.5rem; }
            .whatsapp-float { width: 55px; height: 55px; font-size: 28px; bottom: 20px; right: 20px; }
            .scroll-to-top { bottom: 90px; right: 20px; width: 45px; height: 45px; }
            .section-title h2 { font-size: 2rem; }
            .stat-number { font-size: 2.5rem; }
            .service-icon-container { height: 220px; }
            .service-icon-container i { font-size: 4.5rem; }
            .service-body { padding: 2rem 1.5rem; }
            .service-card-footer { padding: 0 1.5rem 1.5rem; }
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="preloader-content">
            <div class="spinner"></div>
            <p class="text-white mt-3">Loading...</p>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                @if(file_exists(public_path('storage/logo/polargate_logo_white-01.png')))
                    <img src="{{ asset('storage/logo/polargate_logo_white-01.png') }}" 
                        alt="PT Polargate Indonesia Kreasi" 
                        height="55">
                @else
                    <img src="{{ asset('logo/polargate_logo_white-01.png') }}" 
                        alt="PT Polargate Indonesia Kreasi" 
                        height="55">
                @endif
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="#home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Testimoni</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-section" id="home">
        <div class="hero-floating"></div>
        <div class="hero-floating"></div>
        <div class="hero-floating"></div>
        <div class="hero-floating"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-badge animate__animated animate__fadeInDown">
                        <i class="fas fa-star me-2"></i>Solusi Digital Profesional
                    </div>
                    <h1 class="hero-title animate__animated animate__fadeInUp">
                        Multimedia & IT Services Terpercaya
                    </h1>
                    <p class="hero-subtitle animate__animated animate__fadeInUp animate__delay-1s">
                        Wujudkan visi digital bisnis Anda bersama tim profesional yang berpengalaman dalam multimedia dan pengembangan IT
                    </p>
                    <div class="animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="#services" class="btn-hero btn-hero-primary">
                            <i class="fas fa-rocket me-2"></i>Lihat Layanan
                        </a>
                        <a href="https://wa.me/6281234567890?text=Halo%20Polargate,%20saya%20tertarik%20dengan%20layanan%20Anda" target="_blank" class="btn-hero btn-hero-outline">
                            <i class="fab fa-whatsapp me-2"></i>Konsultasi Gratis
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="hero-icon-grid">
                        <div class="hero-icon-box animate__animated animate__fadeInRight animate__delay-1s">
                            <i class="fas fa-video"></i><p>Multimedia</p>
                        </div>
                        <div class="hero-icon-box animate__animated animate__fadeInRight animate__delay-2s">
                            <i class="fas fa-code"></i><p>Web Dev</p>
                        </div>
                        <div class="hero-icon-box animate__animated animate__fadeInRight animate__delay-3s">
                            <i class="fas fa-mobile-alt"></i><p>App Dev</p>
                        </div>
                        <div class="hero-icon-box animate__animated animate__fadeInRight animate__delay-4s">
                            <i class="fas fa-magic"></i><p>Visual FX</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-box"><div class="stat-number">150</div><div class="stat-label">Proyek Selesai</div></div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-box"><div class="stat-number">100</div><div class="stat-label">Client Puas</div></div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-box"><div class="stat-number">5</div><div class="stat-label">Tahun Pengalaman</div></div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-box"><div class="stat-number">24</div><div class="stat-label">Support 24/7</div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="services-section" id="services">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Layanan Kami</h2>
                <p>Solusi lengkap untuk kebutuhan multimedia dan IT bisnis Anda</p>
            </div>

            <!-- Multimedia -->
            <div class="category-section">
                <div class="category-header" data-aos="fade-up">
                    <span class="category-badge"><i class="fas fa-film me-2"></i>Multimedia Services</span>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-card">
                            <div class="service-icon-container"><i class="fas fa-cube"></i></div>
                            <div class="service-body">
                                <h4 class="service-name">Animasi 3D</h4>
                                <p class="service-description">Ciptakan visual 3D yang memukau untuk presentasi produk, iklan, dan konten digital Anda dengan teknologi terkini</p>
                                <ul class="service-features">
                                    <li><i class="fas fa-check-circle"></i>Modeling & Texturing</li>
                                    <li><i class="fas fa-check-circle"></i>Character Animation</li>
                                    <li><i class="fas fa-check-circle"></i>Product Visualization</li>
                                </ul>
                            </div>
                            <div class="service-card-footer">
                                <a href="{{ route('login') }}" class="btn-service"><i class="fas fa-arrow-right me-2"></i>Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-card">
                            <div class="service-icon-container"><i class="fas fa-magic"></i></div>
                            <div class="service-body">
                                <h4 class="service-name">Visual Effect</h4>
                                <p class="service-description">Efek visual sinematik berkualitas Hollywood untuk meningkatkan kualitas video dan konten multimedia Anda</p>
                                <ul class="service-features">
                                    <li><i class="fas fa-check-circle"></i>Motion Graphics</li>
                                    <li><i class="fas fa-check-circle"></i>Compositing</li>
                                    <li><i class="fas fa-check-circle"></i>Color Grading</li>
                                </ul>
                            </div>
                            <div class="service-card-footer">
                                <a href="{{ route('login') }}" class="btn-service"><i class="fas fa-arrow-right me-2"></i>Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-card">
                            <div class="service-icon-container"><i class="fas fa-video"></i></div>
                            <div class="service-body">
                                <h4 class="service-name">Video Company Profile</h4>
                                <p class="service-description">Video profil perusahaan profesional yang menarik untuk meningkatkan brand image dan kredibilitas bisnis Anda</p>
                                <ul class="service-features">
                                    <li><i class="fas fa-check-circle"></i>Konsep Kreatif</li>
                                    <li><i class="fas fa-check-circle"></i>Produksi Berkualitas</li>
                                    <li><i class="fas fa-check-circle"></i>Post Production</li>
                                </ul>
                            </div>
                            <div class="service-card-footer">
                                <a href="{{ route('login') }}" class="btn-service"><i class="fas fa-arrow-right me-2"></i>Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-card">
                            <div class="service-icon-container"><i class="fas fa-tv"></i></div>
                            <div class="service-body">
                                <h4 class="service-name">TVC (TV Commercial)</h4>
                                <p class="service-description">Iklan televisi yang menarik dan efektif untuk meningkatkan awareness dan penjualan produk Anda</p>
                                <ul class="service-features">
                                    <li><i class="fas fa-check-circle"></i>Storyboard Design</li>
                                    <li><i class="fas fa-check-circle"></i>Professional Shooting</li>
                                    <li><i class="fas fa-check-circle"></i>TV-Ready Format</li>
                                </ul>
                            </div>
                            <div class="service-card-footer">
                                <a href="{{ route('login') }}" class="btn-service"><i class="fas fa-arrow-right me-2"></i>Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- IT Services -->
            <div class="category-section">
                <div class="category-header" data-aos="fade-up">
                    <span class="category-badge"><i class="fas fa-laptop-code me-2"></i>IT Development</span>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-card">
                            <div class="service-icon-container"><i class="fas fa-code"></i></div>
                            <div class="service-body">
                                <h4 class="service-name">Web Developer</h4>
                                <p class="service-description">Website modern, responsif, dan SEO-friendly untuk meningkatkan online presence bisnis Anda</p>
                                <ul class="service-features">
                                    <li><i class="fas fa-check-circle"></i>Responsive Design</li>
                                    <li><i class="fas fa-check-circle"></i>SEO Optimization</li>
                                    <li><i class="fas fa-check-circle"></i>CMS Integration</li>
                                    <li><i class="fas fa-check-circle"></i>E-Commerce Ready</li>
                                </ul>
                            </div>
                            <div class="service-card-footer">
                                <a href="{{ route('login') }}" class="btn-service"><i class="fas fa-arrow-right me-2"></i>Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-card">
                            <div class="service-icon-container"><i class="fas fa-mobile-alt"></i></div>
                            <div class="service-body">
                                <h4 class="service-name">Apps Developer</h4>
                                <p class="service-description">Aplikasi mobile Android & iOS dengan fitur lengkap, user-friendly, dan performa optimal</p>
                                <ul class="service-features">
                                    <li><i class="fas fa-check-circle"></i>Android & iOS</li>
                                    <li><i class="fas fa-check-circle"></i>Cross Platform</li>
                                    <li><i class="fas fa-check-circle"></i>API Integration</li>
                                    <li><i class="fas fa-check-circle"></i>Cloud Services</li>
                                </ul>
                            </div>
                            <div class="service-card-footer">
                                <a href="{{ route('login') }}" class="btn-service"><i class="fas fa-arrow-right me-2"></i>Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5" data-aos="fade-up">
                <p class="lead mb-4">Siap untuk memulai proyek Anda?</p>
                <a href="https://wa.me/6281234567890?text=Halo%20Polargate,%20saya%20ingin%20konsultasi%20mengenai%20layanan%20Anda" target="_blank" class="btn-hero btn-hero-primary">
                    <i class="fab fa-whatsapp me-2"></i>Hubungi Kami Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- About -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Tentang Kami</h2>
                <p>Mengapa memilih PT Polargate Indonesia Kreasi?</p>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="about-content">
                        <h3>PT Polargate Indonesia Kreasi</h3>
                        <p>Kami adalah perusahaan yang bergerak di bidang multimedia dan teknologi informasi dengan pengalaman lebih dari 5 tahun melayani berbagai klien dari berbagai industri.</p>
                        <p>Komitmen kami adalah memberikan solusi terbaik yang inovatif, berkualitas tinggi, dan sesuai dengan kebutuhan bisnis Anda. Dari pembuatan konten multimedia hingga pengembangan aplikasi, kami siap mewujudkan visi digital Anda.</p>
                        <p>Dengan tim profesional yang berpengalaman dan berdedikasi, kami memastikan setiap proyek diselesaikan dengan sempurna, tepat waktu, dan sesuai harapan.</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="row g-4">
                        <div class="col-6">
                            <div class="feature-box">
                                <i class="fas fa-users"></i>
                                <h4>Tim Profesional</h4>
                                <p>Expert berpengalaman di bidangnya</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="feature-box">
                                <i class="fas fa-award"></i>
                                <h4>Kualitas Terjamin</h4>
                                <p>Hasil kerja berkualitas tinggi</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="feature-box">
                                <i class="fas fa-clock"></i>
                                <h4>Tepat Waktu</h4>
                                <p>Pengerjaan sesuai deadline</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="feature-box">
                                <i class="fas fa-headset"></i>
                                <h4>Support 24/7</h4>
                                <p>Siap membantu kapan saja</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="feature-box">
                                <i class="fas fa-shield-alt"></i>
                                <h4>Keamanan Data</h4>
                                <p>Privasi terjamin 100%</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="feature-box">
                                <i class="fas fa-thumbs-up"></i>
                                <h4>Harga Kompetitif</h4>
                                <p>Nilai terbaik untuk investasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonial-section" id="testimonials">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Testimoni Client</h2>
                <p>Apa kata mereka tentang layanan kami</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <div class="testimonial-avatar">A</div>
                        <p class="testimonial-text">"Pelayanan yang sangat memuaskan! Tim Polargate sangat profesional dan hasil kerjanya melebihi ekspektasi kami. Highly recommended!"</p>
                        <h5 class="testimonial-name">Ahmad Rizki</h5>
                        <p class="testimonial-position">CEO PT Maju Jaya</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <div class="testimonial-avatar">S</div>
                        <p class="testimonial-text">"Website yang dibuat sangat responsive dan modern. Proses pengerjaannya juga cepat dan komunikasi sangat lancar. Terima kasih Polargate!"</p>
                        <h5 class="testimonial-name">Siti Nurhaliza</h5>
                        <p class="testimonial-position">Owner Fashion Store</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <div class="testimonial-stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <div class="testimonial-avatar">B</div>
                        <p class="testimonial-text">"Video company profile yang dibuat sangat keren! Animasi 3D nya detail banget. Pasti akan pakai jasa Polargate lagi untuk proyek berikutnya."</p>
                        <h5 class="testimonial-name">Budi Santoso</h5>
                        <p class="testimonial-position">Marketing Manager</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Hubungi Kami</h2>
                <p>Siap membantu mewujudkan proyek impian Anda</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-card text-center">
                        <div class="mb-4"><i class="fab fa-whatsapp" style="font-size: 5rem; color: #25d366;"></i></div>
                        <h3 style="color: var(--primary); font-weight: 700; margin-bottom: 1rem;">Hubungi Kami via WhatsApp</h3>
                        <p style="font-size: 1.15rem; color: #6b7280; margin-bottom: 2.5rem;">
                            Tim kami siap memberikan konsultasi gratis dan solusi terbaik untuk kebutuhan bisnis Anda. Chat sekarang dan dapatkan penawaran menarik!
                        </p>
                        <a href="https://wa.me/6281234567890?text=Halo%20Polargate,%20saya%20tertarik%20dengan%20layanan%20Anda" target="_blank" class="btn-whatsapp">
                            <i class="fab fa-whatsapp me-2"></i>Chat Sekarang
                        </a>
                        <div class="mt-4">
                            <p class="text-muted mb-2"><i class="fas fa-envelope me-2"></i>info@polargate.id</p>
                            <p class="text-muted mb-0"><i class="fas fa-map-marker-alt me-2"></i>Jakarta, Indonesia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5>PT Polargate Indonesia Kreasi</h5>
                    <p>Solusi terpercaya untuk kebutuhan multimedia dan IT bisnis Anda. Bersama kami, wujudkan transformasi digital perusahaan Anda.</p>
                    <div class="footer-social mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5>Layanan Multimedia</h5>
                    <p><a href="#services">Animasi 3D</a></p>
                    <p><a href="#services">Visual Effect</a></p>
                    <p><a href="#services">Video Company Profile</a></p>
                    <p><a href="#services">TVC (TV Commercial)</a></p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5>Layanan IT</h5>
                    <p><a href="#services">Web Developer</a></p>
                    <p><a href="#services">Apps Developer</a></p>
                    <p><a href="{{ route('login') }}">Login Client</a></p>
                    <p><a href="{{ route('register') }}">Daftar Sekarang</a></p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>Kontak</h5>
                    <p><i class="fab fa-whatsapp me-2"></i>+62 812-3456-7890</p>
                    <p><i class="fas fa-envelope me-2"></i>info@polargate.id</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Jakarta, Indonesia</p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 2.5rem 0 1.5rem;">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} PT Polargate Indonesia Kreasi. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Float Buttons -->
    <a href="https://wa.me/6281234567890?text=Halo%20Polargate,%20saya%20tertarik%20dengan%20layanan%20Anda" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
    <div class="scroll-to-top" id="scrollToTop"><i class="fas fa-arrow-up"></i></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({duration: 800, easing: 'ease-in-out', once: true, offset: 100});

        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.classList.add('fade-out');
                setTimeout(() => preloader.style.display = 'none', 500);
            }, 1000);
        });

        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('mainNav');
            const scrollBtn = document.getElementById('scrollToTop');
            navbar.classList.toggle('scrolled', window.scrollY > 50);
            scrollBtn.classList.toggle('show', window.scrollY > 300);
            updateActiveNav();
        });

        function updateActiveNav() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            let current = '';
            sections.forEach(section => {
                if (window.pageYOffset >= section.offsetTop - 200) {
                    current = section.getAttribute('id');
                }
            });
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) link.classList.add('active');
            });
        }

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', e => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    window.scrollTo({top: target.offsetTop - 80, behavior: 'smooth'});
                    document.querySelector('.navbar-collapse').classList.remove('show');
                }
            });
        });

        document.getElementById('scrollToTop').addEventListener('click', () => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.querySelectorAll('.stat-number').forEach(stat => {
                        const end = parseInt(stat.textContent);
                        let start = 0;
                        const duration = 2000;
                        const increment = end / (duration / 16);
                        const timer = setInterval(() => {
                            start += increment;
                            if (start >= end) {
                                stat.textContent = end + '+';
                                clearInterval(timer);
                            } else {
                                stat.textContent = Math.floor(start) + '+';
                            }
                        }, 16);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, {threshold: 0.5});

        const statsSection = document.querySelector('.stats-section');
        if (statsSection) observer.observe(statsSection);

        console.log('PT Polargate Indonesia Kreasi - Landing Page v1.0.0');
    </script>
</body>
</html>