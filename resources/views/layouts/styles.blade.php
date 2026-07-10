<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Custom Styles -->
<style>
    /* Default (Dark Mode) */
    :root, [data-bs-theme="dark"] {
        --primary-bg: #0f172a;
        --secondary-bg: #1e293b;
        --card-bg: rgba(30, 41, 59, 0.7);
        --accent: #8b5cf6;
        --accent-hover: #7c3aed;
        --text-main: #f8fafc;
        --text-muted: #94a3b8;
        --border-color: rgba(255, 255, 255, 0.1);
        --glass-border: 1px solid rgba(255,255,255,0.05);
        --input-bg: rgba(255,255,255,0.03);
        --input-border: rgba(255,255,255,0.1);
        --input-focus: rgba(255,255,255,0.05);
        --placeholder-color: rgba(248, 250, 252, 0.4);
    }

    /* Light Mode */
    [data-bs-theme="light"] {
        --primary-bg: #f8fafc;
        --secondary-bg: #ffffff;
        --card-bg: rgba(255, 255, 255, 0.7);
        --accent: #6d28d9;
        --accent-hover: #5b21b6;
        --text-main: #0f172a;
        --text-muted: #475569;
        --border-color: rgba(0, 0, 0, 0.1);
        --glass-border: 1px solid rgba(0,0,0,0.05);
        --input-bg: rgba(0,0,0,0.02);
        --input-border: rgba(0,0,0,0.1);
        --input-focus: rgba(0,0,0,0.05);
        --placeholder-color: rgba(15, 23, 42, 0.4);
    }

    body {
        background-color: var(--primary-bg);
        background-image: 
            radial-gradient(at 0% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(56, 189, 248, 0.15) 0px, transparent 50%);
        background-attachment: fixed;
        color: var(--text-main);
        font-family: 'Outfit', sans-serif;
        min-height: 100vh;
        overflow-x: hidden;
        transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1), color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        animation: fadeInPage 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    html {
        scroll-behavior: smooth;
    }

    @keyframes fadeInPage {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Global Micro-Animations */
    a, button, .btn, .nav-link, .dropdown-item {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .btn:active {
        transform: scale(0.96) !important;
    }

    /* Card Smooth Hover Lift */
    .card {
        background: var(--card-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: var(--glass-border);
        border-radius: 16px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform, box-shadow;
    }
    
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px -8px rgba(139, 92, 246, 0.2), 0 8px 12px -6px rgba(0, 0, 0, 0.1);
    }

    [data-bs-theme="dark"] .card:hover {
        box-shadow: 0 12px 30px -8px rgba(0, 0, 0, 0.6), 0 8px 12px -6px rgba(0, 0, 0, 0.5);
    }

    /* Table Row Hover */
    .table-hover tbody tr {
        transition: background-color 0.2s ease, transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .table-hover tbody tr:hover {
        transform: scale(1.005) translateX(2px);
    }

    /* Inputs Focus Polish */
    .form-control, .form-select {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Select & Optgroup Styling for Dark Mode */
    select.form-select option {
        background-color: var(--secondary-bg);
        color: var(--text-main);
    }
    
    select.form-select optgroup {
        background-color: var(--secondary-bg);
        color: var(--text-muted);
        font-weight: 600;
        font-style: normal;
    }
    
    [data-bs-theme="dark"] select.form-select optgroup {
        color: var(--accent);
    }

    /* Header */
    .navbar {
        background: var(--secondary-bg) !important;
        background: rgba(var(--secondary-bg), 0.9) !important;
        backdrop-filter: blur(20px);
        border-bottom: var(--glass-border);
        height: 64px;
        z-index: 1050;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 64px; /* matches header height */
        bottom: 0;
        left: 0;
        z-index: 1040;
        width: 250px;
        background: var(--secondary-bg);
        border-right: 1px solid var(--border-color);
        transition: transform 0.3s ease;
    }
    
    .sidebar-sticky {
        height: calc(100vh - 64px);
        padding-top: .5rem;
        padding-bottom: 2rem;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Main Content Layout */
    .main-content {
        margin-left: 250px;
        min-height: calc(100vh - 64px);
        transition: margin-left 0.3s ease;
    }
    
    /* Custom Scrollbar for Sidebar (Chrome/Safari) */
    .sidebar::-webkit-scrollbar,
    .sidebar-sticky::-webkit-scrollbar {
        width: 4px;
    }
    .sidebar::-webkit-scrollbar-track,
    .sidebar-sticky::-webkit-scrollbar-track {
        background: transparent;
    }
    .sidebar::-webkit-scrollbar-thumb,
    .sidebar-sticky::-webkit-scrollbar-thumb {
        background: rgba(139, 92, 246, 0.3);
        border-radius: 4px;
    }
    /* Mobile Responsiveness */
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar.show {
            transform: translateX(0);
        }
        .main-content {
            margin-left: 0;
        }
    }
    .sidebar::-webkit-scrollbar-thumb:hover,
    .sidebar-sticky::-webkit-scrollbar-thumb:hover {
        background: rgba(139, 92, 246, 0.6);
    }
    
    .sidebar .nav-link {
        color: var(--text-muted);
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        margin: 4px 12px;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .sidebar .nav-link:hover, .sidebar .nav-link.active {
        color: var(--text-main);
        background: rgba(139, 92, 246, 0.1);
    }
    
    .sidebar .nav-link.active {
        box-shadow: inset 3px 0 0 var(--accent);
    }

    .sidebar-icon {
        margin-right: 12px;
        font-size: 1.1rem;
        transition: transform 0.2s;
    }

    .sidebar .nav-link:hover .sidebar-icon, .sidebar .nav-link.active .sidebar-icon {
        transform: scale(1.1);
        color: var(--accent);
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        color: #fff;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
        background: linear-gradient(135deg, var(--accent-hover) 0%, var(--accent) 100%);
        color: #fff;
    }

    /* Inputs */
    .form-control, .form-select {
        background: var(--input-bg);
        border: 1px solid var(--input-border);
        color: var(--text-main);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control::placeholder {
        color: var(--placeholder-color) !important;
        opacity: 1;
    }

    .form-control:focus, .form-select:focus {
        background: var(--input-focus);
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
        color: var(--text-main);
    }
    
    .form-select option {
        background: var(--secondary-bg);
        color: var(--text-main);
    }

    /* Offcanvas */
    .offcanvas {
        z-index: 1060;
    }
    .offcanvas-backdrop {
        z-index: 1055;
    }

    .form-label {
        color: var(--text-muted);
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    /* Skeleton Loading */
    .skeleton {
        animation: skeleton-loading 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite alternate;
        border-radius: 6px;
    }

    @keyframes skeleton-loading {
        0% { background-color: rgba(255,255,255,0.05); }
        100% { background-color: rgba(255,255,255,0.15); }
    }

    .skeleton-text {
        height: 1.2rem;
        margin-bottom: 0.75rem;
    }

    .content-wrapper {
        padding: 24px;
    }

    /* Headings */
    h1, h2, h3, h4, h5, h6 {
        font-weight: 600;
        letter-spacing: -0.02em;
    }

    /* Utilities */
    .text-muted {
        color: var(--text-muted) !important;
    }
</style>
