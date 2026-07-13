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
        .navbar-public {
            background: rgba(15, 23, 42, 0.8) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .hero-section {
            padding: 120px 0 80px;
            background: radial-gradient(circle at top right, rgba(139, 92, 246, 0.15) 0%, transparent 40%),
                        radial-gradient(circle at bottom left, rgba(236, 72, 153, 0.1) 0%, transparent 40%);
        }
        .feature-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.1);
            border-color: rgba(139, 92, 246, 0.3);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark navbar-public fixed-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold fs-4" href="{{ route('public.landing') }}">
                <div class="bg-gradient-primary rounded d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 36px; height: 36px;">
                    <i class="bi bi-hexagon-fill text-white fs-5"></i>
                </div>
                Nexa<span class="text-accent">Flow</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.landing') ? 'active text-primary' : '' }}" href="{{ route('public.landing') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.about') ? 'active text-primary' : '' }}" href="{{ route('public.about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.pricing') ? 'active text-primary' : '' }}" href="{{ route('public.pricing') }}">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.contact') ? 'active text-primary' : '' }}" href="{{ route('public.contact') }}">Contact</a>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary px-4 rounded-pill">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light px-4 rounded-pill">Log In</a>
                        <a href="{{ route('register') }}" class="btn btn-primary px-4 rounded-pill">Get Started</a>
                    @endauth
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
                        <input type="email" class="form-control bg-dark border-secondary text-white" placeholder="Email address">
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
</body>
</html>
