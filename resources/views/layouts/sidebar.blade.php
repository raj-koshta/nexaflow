<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky sidebar-sticky">
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                    <i class="bi bi-grid-1x2-fill sidebar-icon"></i>
                    Dashboard
                </a>
            </li>
            
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-4 mt-4 mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--accent);">
                <span>CRM Core</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                    <i class="bi bi-buildings-fill sidebar-icon"></i>
                    Clients
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('leads.*') ? 'active' : '' }}" href="{{ route('leads.index') }}">
                    <i class="bi bi-funnel-fill sidebar-icon"></i>
                    Leads
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('contacts.*') ? 'active' : '' }}" href="{{ route('contacts.index') }}">
                    <i class="bi bi-person-badge-fill sidebar-icon"></i>
                    Contacts
                </a>
            </li>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-4 mt-4 mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--accent);">
                <span>Operations</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-briefcase-fill sidebar-icon"></i>
                    Projects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-check-square-fill sidebar-icon"></i>
                    Tasks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-headset sidebar-icon"></i>
                    Support
                </a>
            </li>
            
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-4 mt-4 mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--accent);">
                <span>Intelligence</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-robot sidebar-icon"></i>
                    AI Assistant
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-bar-chart-line-fill sidebar-icon"></i>
                    Reports
                </a>
            </li>
        </ul>
    </div>
</nav>
