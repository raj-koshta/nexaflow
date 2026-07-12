<header class="navbar sticky-top px-4 d-flex align-items-center justify-content-between w-100 shadow-sm" style="background: var(--primary-bg); border-bottom: 1px solid var(--border-color); height: 60px; z-index: 1050;">
    <!-- Left: Logo -->
    <a class="navbar-brand fw-bold m-0 p-0 d-flex align-items-center" href="{{ url('/') }}" style="color: var(--accent); font-size: 1.5rem; letter-spacing: -0.5px;">
        <i class="bi bi-layers-fill me-2"></i>NexaFlow
    </a>

    <!-- Center: Global Search -->
    @auth
    <div class="position-relative d-none d-md-block mx-4 flex-grow-1" style="max-width: 500px;" id="globalSearchContainer">
        <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0" style="border-color: rgba(255,255,255,0.1);"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control border-start-0 ps-0" id="globalSearchInput" placeholder="Search clients, projects, tasks..." style="background: transparent; border-color: rgba(255,255,255,0.1); color: var(--text-main); box-shadow: none;" autocomplete="off">
        </div>
        
        <div class="dropdown-menu w-100 shadow mt-1 p-0" id="globalSearchResults" style="background: var(--secondary-bg); border: var(--glass-border); max-height: 400px; overflow-y: auto;">
            <!-- Injected via AJAX -->
        </div>
    </div>
    @endauth

    <!-- Right: Icons & Profile + Hamburger -->
    <div class="d-flex align-items-center gap-3 gap-md-4">
        <!-- Theme Toggler -->
        <button class="btn btn-link nav-link p-0" style="color: var(--text-main);" id="theme-toggle" aria-label="Toggle Theme">
            <i class="bi bi-moon-stars-fill fs-5" id="theme-icon"></i>
        </button>
        
        @auth
        <!-- Notifications -->
        <div class="dropdown" id="notificationDropdownContainer">
            <a class="nav-link position-relative p-0" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--text-main);">
                <i class="bi bi-bell-fill fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle d-none" id="notificationBadge">
                    <span class="visually-hidden">New alerts</span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow p-0" style="width: 320px; background: var(--secondary-bg); border: var(--glass-border); margin-top: 10px;" aria-labelledby="notificationDropdown">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
                    <h6 class="mb-0 fw-bold">Notifications</h6>
                    <button class="btn btn-sm btn-link text-decoration-none p-0" id="markAllReadBtn" style="font-size: 0.8rem; color: var(--accent);">Mark all read</button>
                </div>
                <div class="notification-list" style="max-height: 300px; overflow-y: auto;">
                    <!-- Injected via AJAX -->
                    <div class="text-center p-4 text-muted small" id="noNotificationsMsg">No new notifications</div>
                </div>
                <div class="p-2 border-top text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                    <a href="{{ route('notifications.index') }}" class="text-decoration-none small" style="color: var(--accent);">View All Notifications</a>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center p-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--text-main);">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=8b5cf6&color=fff" alt="User" class="rounded-circle" width="32" height="32">
                <span class="fw-medium ms-2 d-none d-md-inline">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end position-absolute shadow-sm" style="background: var(--secondary-bg); border: var(--glass-border); margin-top: 10px;" aria-labelledby="userDropdown">
                <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}" style="color: var(--text-main);"><i class="bi bi-person me-2 text-muted"></i>Profile</a></li>
                <li><a class="dropdown-item py-2" href="{{ route('settings.index') }}" style="color: var(--text-main);"><i class="bi bi-gear me-2 text-muted"></i>Settings</a></li>
                <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger py-2"><i class="bi bi-box-arrow-right me-2"></i>Sign out</button>
                    </form>
                </li>
            </ul>
        </div>
        @else
        <div class="d-flex align-items-center gap-2">
            <a class="btn fw-medium btn-sm" style="color: var(--text-main); border: 1px solid var(--border-color);" href="{{ route('login') }}">Login</a>
            <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Get Started</a>
        </div>
        @endauth

        <!-- Hamburger Menu (Mobile Only, on Right) -->
        <button class="btn btn-link text-decoration-none d-md-none p-0 ms-1" type="button" id="sidebarToggle" style="color: var(--text-main);">
            <i class="bi bi-list fs-2"></i>
        </button>
    </div>
</header>
