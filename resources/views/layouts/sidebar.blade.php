<nav id="sidebarMenu" class="sidebar bg-secondary-bg">
    <div class="position-sticky sidebar-sticky">
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 sidebar-icon"></i>
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
                <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                    <i class="bi bi-briefcase-fill sidebar-icon"></i>
                    Projects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                    <i class="bi bi-check-square-fill sidebar-icon"></i>
                    Tasks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" href="{{ route('activities.index') }}">
                    <i class="bi bi-activity sidebar-icon"></i>
                    Activities
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('follow-ups.*') ? 'active' : '' }}" href="{{ route('follow-ups.index') }}">
                    <i class="bi bi-calendar-check sidebar-icon"></i>
                    Follow Ups
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('file-manager.*') ? 'active' : '' }}" href="{{ route('file-manager.index') }}">
                    <i class="bi bi-folder2-open sidebar-icon"></i>
                    File Manager
                </a>
            </li>
            
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-4 mt-4 mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--accent);">
                <span>Support</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}" href="{{ route('tickets.index') }}">
                    <i class="bi bi-ticket-detailed sidebar-icon"></i>
                    Tickets
                </a>
            </li>
            
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-4 mt-4 mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--accent);">
                <span>AI Assistant</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('ai.chat.*') ? 'active' : '' }}" href="{{ route('ai.chat.index') }}">
                    <i class="bi bi-chat-dots sidebar-icon"></i>
                    AI Chat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('ai.email.*') ? 'active' : '' }}" href="{{ route('ai.email.index') }}">
                    <i class="bi bi-envelope-paper sidebar-icon"></i>
                    Email Generator
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('ai.meetings.*') ? 'active' : '' }}" href="{{ route('ai.meetings.index') }}">
                    <i class="bi bi-mic sidebar-icon"></i>
                    Meeting Notes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('ai.insights.*') ? 'active' : '' }}" href="{{ route('ai.insights.index') }}">
                    <i class="bi bi-graph-up-arrow sidebar-icon"></i>
                    Business Insights
                </a>
            </li>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-4 mt-4 mb-2 text-uppercase" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: var(--accent);">
                <span>Intelligence</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <i class="bi bi-bar-chart-line-fill sidebar-icon"></i>
                    Reports
                </a>
            </li>
            
            @role('Administrator')
            <li class="nav-item mt-3">
                <h6 class="sidebar-heading px-3 text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    System Settings
                </h6>
            </li>
            <ul class="nav flex-column mb-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <i class="bi bi-sliders sidebar-icon"></i>
                        General Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-people sidebar-icon"></i>
                        User Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}" href="{{ route('activity-logs.index') }}">
                        <i class="bi bi-clock-history sidebar-icon"></i>
                        Audit Logs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('teams.*') ? 'active' : '' }}" href="{{ route('teams.index') }}">
                        <i class="bi bi-diagram-3 sidebar-icon"></i>
                        Team Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}">
                        <i class="bi bi-building sidebar-icon"></i>
                        Company Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                        <i class="bi bi-person-badge sidebar-icon"></i>
                        Role Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}" href="{{ route('permissions.index') }}">
                        <i class="bi bi-shield-lock sidebar-icon"></i>
                        Permission Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('import-export.*') ? 'active' : '' }}" href="{{ route('import-export.index') }}">
                        <i class="bi bi-arrow-left-right sidebar-icon"></i>
                        Import / Export
                    </a>
                </li>
            </ul>
            @endrole
        </ul>
    </div>
</nav>
