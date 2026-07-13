@extends('layouts.public')

@section('title', 'Pricing')

@section('content')
<div class="container py-5 my-5">
    <div class="text-center mb-5 reveal">
        <h1 class="display-5 fw-bold mb-3">Simple, Transparent Pricing</h1>
        <p class="lead text-muted">Choose the perfect plan for your business needs.</p>
        
        <div class="d-flex justify-content-center align-items-center mt-4 gap-3">
            <span class="text-muted fw-bold">Monthly</span>
            <div class="form-check form-switch" style="transform: scale(1.5);">
                <input class="form-check-input bg-primary border-primary" type="checkbox" role="switch" id="billingToggle" checked>
            </div>
            <span class="text-primary fw-bold d-flex align-items-center">Yearly <span class="badge bg-accent bg-opacity-10 text-accent rounded-pill ms-2" style="font-size: 0.6rem;">SAVE 20%</span></span>
        </div>
    </div>
    
    <div class="row g-4 justify-content-center">
        <!-- Starter Plan -->
        <div class="col-lg-4 col-md-6 reveal delay-100">
            <div class="card shadow-sm border-0 h-100 position-relative feature-card" style="background: var(--public-card-bg) !important; border-radius: 16px;">
                <div class="card-body p-5 d-flex flex-column">
                    <div class="bg-primary bg-opacity-10 text-primary rounded d-inline-flex align-items-center justify-content-center mb-4" style="width: 48px; height: 48px;">
                        <i class="bi bi-rocket fs-4"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Starter</h4>
                    <p class="text-muted mb-4" style="font-size: 0.9rem;">Perfect for freelancers and small teams.</p>
                    <div class="mb-4 pb-4 border-bottom" style="border-color: var(--border-color) !important;">
                        <span class="display-4 fw-bold price-val" data-monthly="29" data-yearly="23">$29</span><span class="text-muted">/month</span>
                    </div>
                    <ul class="list-unstyled mb-5 flex-grow-1" style="font-size: 0.95rem;">
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> Up to 5 Users</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> 100 Clients & Leads</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> Basic Project Tracking</li>
                        <li class="mb-3 d-flex align-items-center text-muted opacity-50"><i class="bi bi-x me-3 fs-5"></i> AI Assistant</li>
                        <li class="mb-3 d-flex align-items-center text-muted opacity-50"><i class="bi bi-x me-3 fs-5"></i> Advanced Reports</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 rounded-pill mt-auto py-2">Get Started</a>
                </div>
            </div>
        </div>

        <!-- Professional Plan -->
        <div class="col-lg-4 col-md-6 reveal delay-200">
            <div class="card h-100 position-relative overflow-hidden feature-card" style="background: var(--public-card-bg) !important; border-radius: 16px; border: 2px solid transparent; box-shadow: 0 0 30px rgba(139, 92, 246, 0.2);">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, var(--primary), var(--accent)); padding: 2px; -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); -webkit-mask-composite: xor; mask-composite: exclude; z-index: 0; border-radius: 16px;"></div>
                <div class="position-absolute top-0 start-50 translate-middle mt-3" style="z-index: 10;">
                    <span class="badge bg-gradient-primary rounded-pill px-4 py-2 text-uppercase letter-spacing-1 shadow">Most Popular</span>
                </div>
                <div class="card-body p-5 d-flex flex-column mt-3 position-relative z-1">
                    <div class="bg-gradient-primary text-white rounded d-inline-flex align-items-center justify-content-center mb-4 shadow" style="width: 48px; height: 48px;">
                        <i class="bi bi-lightning-fill fs-4"></i>
                    </div>
                    <h4 class="fw-bold mb-2 text-primary">Professional</h4>
                    <p class="text-muted mb-4" style="font-size: 0.9rem;">For growing businesses needing more power.</p>
                    <div class="mb-4 pb-4 border-bottom" style="border-color: var(--border-color) !important;">
                        <span class="display-4 fw-bold price-val" data-monthly="79" data-yearly="63">$63</span><span class="text-muted">/month</span>
                    </div>
                    <ul class="list-unstyled mb-5 flex-grow-1" style="font-size: 0.95rem;">
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> Up to 20 Users</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> Unlimited Clients</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> Advanced Project Tracking</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> AI Assistant (500 prompts)</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> Advanced Reports</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary text-white w-100 rounded-pill mt-auto py-2 shadow-sm" style="background: linear-gradient(135deg, var(--primary), var(--accent)); border: none;">Get Started</a>
                </div>
            </div>
        </div>

        <!-- Enterprise Plan -->
        <div class="col-lg-4 col-md-6 reveal delay-300">
            <div class="card shadow-sm border-0 h-100 position-relative feature-card" style="background: var(--public-card-bg) !important; border-radius: 16px;">
                <div class="card-body p-5 d-flex flex-column">
                    <div class="bg-secondary bg-opacity-10 text-secondary rounded d-inline-flex align-items-center justify-content-center mb-4" style="width: 48px; height: 48px;">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Enterprise</h4>
                    <p class="text-muted mb-4" style="font-size: 0.9rem;">Custom solutions for large organizations.</p>
                    <div class="mb-4 pb-4 border-bottom" style="border-color: var(--border-color) !important;">
                        <span class="display-4 fw-bold price-val" data-monthly="199" data-yearly="159">$159</span><span class="text-muted">/month</span>
                    </div>
                    <ul class="list-unstyled mb-5 flex-grow-1" style="font-size: 0.95rem;">
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> Unlimited Users</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> Unlimited Clients</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> Custom Integrations</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> Unlimited AI Assistant</li>
                        <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-primary me-3 fs-5"></i> Dedicated Support</li>
                    </ul>
                    <a href="{{ route('public.contact') }}" class="btn btn-outline-secondary w-100 rounded-pill mt-auto py-2">Contact Sales</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggle = document.getElementById('billingToggle');
        const priceVals = document.querySelectorAll('.price-val');
        
        toggle.addEventListener('change', function() {
            const isYearly = this.checked;
            priceVals.forEach(el => {
                const val = isYearly ? el.dataset.yearly : el.dataset.monthly;
                el.innerText = '$' + val;
            });
        });
    });
</script>
@endsection
