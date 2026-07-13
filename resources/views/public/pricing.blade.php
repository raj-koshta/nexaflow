@extends('layouts.public')

@section('title', 'Pricing')

@section('content')
<div class="container py-5 my-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-3">Simple, Transparent Pricing</h1>
        <p class="lead text-muted">Choose the perfect plan for your business needs.</p>
    </div>
    
    <div class="row g-4 justify-content-center">
        <!-- Starter Plan -->
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 h-100 position-relative" style="background: var(--card-bg); border: var(--glass-border) !important; border-radius: 16px;">
                <div class="card-body p-5 d-flex flex-column">
                    <h4 class="fw-bold mb-2">Starter</h4>
                    <p class="text-muted mb-4">Perfect for freelancers and small teams.</p>
                    <div class="mb-4">
                        <span class="display-4 fw-bold">$29</span><span class="text-muted">/month</span>
                    </div>
                    <ul class="list-unstyled mb-5 flex-grow-1">
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> Up to 5 Users</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> 100 Clients & Leads</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> Basic Project Tracking</li>
                        <li class="mb-3 d-flex align-items-center text-muted"><i class="bi bi-x-circle me-2"></i> AI Assistant</li>
                        <li class="mb-3 d-flex align-items-center text-muted"><i class="bi bi-x-circle me-2"></i> Advanced Reports</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 rounded-pill mt-auto">Get Started</a>
                </div>
            </div>
        </div>

        <!-- Professional Plan -->
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-lg border-primary h-100 position-relative" style="background: var(--card-bg); border-radius: 16px; border-width: 2px;">
                <div class="position-absolute top-0 start-50 translate-middle">
                    <span class="badge bg-primary rounded-pill px-3 py-2 text-uppercase letter-spacing-1 shadow-sm">Most Popular</span>
                </div>
                <div class="card-body p-5 d-flex flex-column mt-2">
                    <h4 class="fw-bold mb-2 text-primary">Professional</h4>
                    <p class="text-muted mb-4">For growing businesses needing more power.</p>
                    <div class="mb-4">
                        <span class="display-4 fw-bold">$79</span><span class="text-muted">/month</span>
                    </div>
                    <ul class="list-unstyled mb-5 flex-grow-1">
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> Up to 20 Users</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> Unlimited Clients</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> Advanced Project Tracking</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> AI Assistant (500 prompts)</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> Advanced Reports</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary w-100 rounded-pill mt-auto shadow-sm">Get Started</a>
                </div>
            </div>
        </div>

        <!-- Enterprise Plan -->
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 h-100 position-relative" style="background: var(--card-bg); border: var(--glass-border) !important; border-radius: 16px;">
                <div class="card-body p-5 d-flex flex-column">
                    <h4 class="fw-bold mb-2">Enterprise</h4>
                    <p class="text-muted mb-4">Custom solutions for large organizations.</p>
                    <div class="mb-4">
                        <span class="display-4 fw-bold">$199</span><span class="text-muted">/month</span>
                    </div>
                    <ul class="list-unstyled mb-5 flex-grow-1">
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> Unlimited Users</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> Unlimited Clients</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> Custom Integrations</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> Unlimited AI Assistant</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check-circle-fill text-success me-2"></i> Dedicated Support</li>
                    </ul>
                    <a href="{{ route('public.contact') }}" class="btn btn-outline-primary w-100 rounded-pill mt-auto">Contact Sales</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
