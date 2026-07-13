<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NexaFlow') - Business Automation</title>
    
    <!-- Initialize Theme -->
    <script>
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>
    
    @include('layouts.styles')
    
    <style>
        :root {
            --public-glass-bg: rgba(255, 255, 255, 0.85);
            --public-glass-border: rgba(0, 0, 0, 0.08);
            --public-card-bg: rgba(255, 255, 255, 0.7);
            --public-card-hover: rgba(255, 255, 255, 0.9);
            --blob-1-bg: rgba(139, 92, 246, 0.2);
            --blob-2-bg: rgba(236, 72, 153, 0.15);
        }
        
        [data-bs-theme="dark"] {
            --public-glass-bg: rgba(15, 23, 42, 0.8);
            --public-glass-border: rgba(255, 255, 255, 0.05);
            --public-card-bg: rgba(255, 255, 255, 0.02);
            --public-card-hover: rgba(255, 255, 255, 0.04);
            --blob-1-bg: rgba(139, 92, 246, 0.4);
            --blob-2-bg: rgba(236, 72, 153, 0.3);
        }

        /* Floating Pill Navbar */
        .navbar-public {
            background: var(--public-glass-bg) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--public-glass-border);
            border-radius: 50px;
            margin-top: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .hero-section {
            padding: 160px 0 80px;
        }
        
        .feature-card {
            background: var(--public-card-bg) !important;
            border: 1px solid var(--public-glass-border) !important;
            border-radius: 16px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(139, 92, 246, 0.15);
            border-color: rgba(139, 92, 246, 0.4) !important;
            background: var(--public-card-hover) !important;
        }
        
        /* Modern Background Blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            z-index: -1;
            animation: float 20s infinite alternate ease-in-out;
        }
        .blob-1 {
            width: 400px;
            height: 400px;
            background: var(--blob-1-bg);
            top: -100px;
            right: -100px;
        }
        .blob-2 {
            width: 500px;
            height: 500px;
            background: var(--blob-2-bg);
            bottom: -200px;
            left: -200px;
            animation-delay: -5s;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg) scale(1); }
            33% { transform: translate(50px, 50px) rotate(10deg) scale(1.1); }
            66% { transform: translate(-30px, 80px) rotate(-5deg) scale(0.9); }
            100% { transform: translate(0, 0) rotate(0deg) scale(1); }
        }

        /* Scroll Reveal Animations */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 position-relative overflow-x-hidden">
    <!-- Animated Blobs -->
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; overflow: hidden; pointer-events: none;">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-public fixed-top py-2 px-3 mx-auto" style="max-width: 1200px;">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center fw-bold fs-5" href="{{ route('public.landing') }}" style="color: var(--text-main);">
                <div class="bg-gradient-primary rounded d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 32px; height: 32px;">
                    <i class="bi bi-hexagon-fill text-white fs-6"></i>
                </div>
                Nexa<span class="text-accent">Flow</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <i class="bi bi-list fs-1 text-main" style="color: var(--text-main);"></i>
            </button>
            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium">
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('public.landing') ? 'active text-primary' : '' }}" href="{{ route('public.landing') }}" style="color: var(--text-main);">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('public.about') ? 'active text-primary' : '' }}" href="{{ route('public.about') }}" style="color: var(--text-main);">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('public.pricing') ? 'active text-primary' : '' }}" href="{{ route('public.pricing') }}" style="color: var(--text-main);">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('public.contact') ? 'active text-primary' : '' }}" href="{{ route('public.contact') }}" style="color: var(--text-main);">Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <!-- Theme Toggler -->
                    <button class="btn btn-link nav-link p-0" style="color: var(--text-main);" id="theme-toggle" aria-label="Toggle Theme">
                        <i class="bi bi-moon-stars-fill fs-5" id="theme-icon"></i>
                    </button>
                    <div class="vr bg-secondary opacity-25 d-none d-lg-block"></div>
                    <div class="d-flex gap-2 w-100 justify-content-center">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary px-4 rounded-pill w-100">Go to Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary px-4 rounded-pill">Log In</a>
                            <a href="{{ route('register') }}" class="btn btn-primary px-4 rounded-pill">Get Started</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-shrink-0" style="margin-top: 76px;">
        @yield('content')
    </main>

    <footer class="mt-auto py-5" style="background: var(--secondary-bg); border-top: 1px solid var(--border-color);">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center fw-bold fs-4 mb-3">
                        <i class="bi bi-hexagon-fill text-primary me-2"></i>
                        Nexa<span class="text-accent">Flow</span>
                    </div>
                    <p class="text-muted pe-4">The ultimate CRM and business automation platform to help you manage clients, projects, and tasks with AI-powered insights.</p>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="fw-bold mb-3">Product</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Features</a></li>
                        <li class="mb-2"><a href="{{ route('public.pricing') }}" class="text-muted text-decoration-none">Pricing</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Integrations</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="fw-bold mb-3">Company</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="{{ route('public.about') }}" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Careers</a></li>
                        <li class="mb-2"><a href="{{ route('public.contact') }}" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold mb-3">Subscribe to Newsletter</h6>
                    <p class="text-muted small">Get the latest updates and business tips directly in your inbox.</p>
                    <div class="input-group">
                        <input type="email" class="form-control" style="background: var(--primary-bg); border-color: var(--border-color); color: var(--text-main);" placeholder="Email address">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            <div class="border-top mt-4 pt-4 text-center text-muted small" style="border-color: var(--border-color) !important;">
                &copy; {{ date('Y') }} NexaFlow. All rights reserved.
            </div>
        </div>
    </footer>

    @include('layouts.scripts')
    
    <script>
        // Intersection Observer for Scroll Reveals
        document.addEventListener("DOMContentLoaded", function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
            });

            document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
        });
    </script>
</body>
</html>
