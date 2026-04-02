<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Result Management System')</title>
    
    <!-- Google Fonts: Inter (Professional UI) & Outfit (Brand) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #6366f1;
            --dark-color: #0f172a;
            --light-bg: #f1f5f9;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
        }

        body { 
            background-color: var(--light-bg); 
            font-family: 'Inter', sans-serif; 
            color: #1e293b;
            overflow-x: hidden;
            letter-spacing: -0.01em;
        }

        h1, h2, h3, h4, h5, h6, .sidebar-header, .fw-black {
            font-family: 'Outfit', sans-serif;
        }

        /* Sidebar Styles */
        .sidebar { 
            height: 100vh; 
            background: var(--dark-color); 
            color: #fff; 
            width: var(--sidebar-width); 
            position: fixed; 
            left: 0;
            top: 0;
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }

        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            white-space: nowrap;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .sidebar-header h4 {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin: 0;
        }

        .sidebar-header h4 i {
            font-size: 1.5rem;
            width: 24px;
            text-align: center;
            margin-right: 12px;
            transition: margin 0.3s;
        }
        
        .sidebar-header h4 span {
            transition: opacity 0.2s, display 0.2s;
        }

        body.sidebar-collapsed .sidebar-header {
            padding: 1.5rem 0;
        }

        body.sidebar-collapsed .sidebar-header h4 {
            justify-content: center;
        }

        body.sidebar-collapsed .sidebar-header h4 i {
            margin-right: 0 !important;
        }

        body.sidebar-collapsed .sidebar-header h4 span {
            opacity: 0;
            display: none;
        }

        .sidebar-content {
            padding: 1rem 0;
            height: calc(100vh - 80px);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-heading {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            overflow: hidden;
        }

        body.sidebar-collapsed .sidebar-heading {
            padding: 0 !important;
            margin: 1.5rem 20px 1rem !important;
            height: 1px;
            background-color: rgba(255,255,255,0.1);
        }

        body.sidebar-collapsed .sidebar-heading span {
            opacity: 0;
            display: none;
        }

        .sidebar a { 
            color: #94a3b8; 
            text-decoration: none; 
            padding: 12px 16px; 
            display: flex;
            align-items: center;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 6px 16px;
            border-radius: 10px;
            white-space: nowrap;
            overflow: hidden;
            font-weight: 500;
        }

        .sidebar a i { 
            font-size: 1.25rem; 
            width: 24px;
            text-align: center;
            margin-right: 12px;
            transition: margin 0.3s;
        }

        .sidebar a span {
            transition: opacity 0.2s;
        }

        body.sidebar-collapsed .sidebar a {
            padding: 14px 0;
            margin: 8px 16px;
            justify-content: center;
            border-radius: 12px;
        }

        body.sidebar-collapsed .sidebar a i {
            margin-right: 0;
        }

        body.sidebar-collapsed .sidebar a span {
            opacity: 0;
            display: none;
        }

        .sidebar a:hover { 
            background: rgba(255,255,255,0.05); 
            color: #fff; 
        }
        
        .sidebar a.active {
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        /* Top Navigation Header */
        .top-navbar {
            height: 70px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }

        .sidebar-toggle {
            background: #f1f5f9;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            color: var(--dark-color);
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-toggle:hover {
            background: #e2e8f0;
        }

        /* Responsive & Overlays */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        @media (max-width: 992px) {
            .sidebar { 
                transform: translateX(-100%); 
                width: 280px; 
            }
            body.mobile-sidebar-active .sidebar { 
                transform: translateX(0); 
            }
            body.mobile-sidebar-active .sidebar-overlay {
                opacity: 1;
                visibility: visible;
            }
            .main-wrapper { 
                margin-left: 0 !important; 
            }
            .top-navbar {
                padding: 0 1rem;
                height: 60px;
            }
            .sidebar-toggle { 
                margin-right: 0.5rem; 
            }
            main.p-4 { padding: 1.5rem !important; }
        }

        @media print {
            .sidebar, .top-navbar, .no-print { display: none !important; }
            .main-wrapper { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
        }
        
        /* Dark Mode Overrides for Admin Only */
        body.admin-body.dark-mode {
            --bg-main: #0f172a;
            --text-main: #f8fafc;
            --card-bg: #1e293b;
            --border-color: #334155;
            --topbar-bg: #1e293b;
            background-color: var(--bg-main) !important;
            color: var(--text-main) !important;
        }

        body.admin-body.dark-mode .top-navbar {
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--border-color);
        }

        body.admin-body.dark-mode .sidebar-toggle {
            background: #334155;
            color: #f8fafc;
        }

        body.admin-body.dark-mode .sidebar-toggle:hover {
            background: #475569;
        }

        body.admin-body.dark-mode .card,
        body.admin-body.dark-mode .bg-white {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
            color: var(--text-main) !important;
        }

        body.admin-body.dark-mode .bg-light {
            background-color: var(--bg-main) !important;
        }

        body.admin-body.dark-mode .table {
            color: var(--text-main) !important;
            border-color: var(--border-color) !important;
        }

        body.admin-body.dark-mode .table td, 
        body.admin-body.dark-mode .table th {
            background-color: var(--card-bg) !important;
            border-bottom-color: var(--border-color) !important;
            color: var(--text-main) !important;
        }

        body.admin-body.dark-mode .form-control,
        body.admin-body.dark-mode .form-select {
            background-color: #334155 !important;
            border-color: var(--border-color) !important;
            color: var(--text-main) !important;
        }

        body.admin-body.dark-mode .form-control:focus,
        body.admin-body.dark-mode .form-select:focus {
            background-color: #334155 !important;
            border-color: var(--primary-color) !important;
            color: var(--text-main) !important;
        }

        body.admin-body.dark-mode .dropdown-menu {
            background-color: var(--card-bg);
            border-color: var(--border-color);
        }

        body.admin-body.dark-mode .dropdown-item {
            color: var(--text-main);
        }

        body.admin-body.dark-mode .dropdown-item:hover {
            background-color: #334155;
            color: var(--text-main);
        }

        body.admin-body.dark-mode .modal-content {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-main);
        }

        body.admin-body.dark-mode .modal-header,
        body.admin-body.dark-mode .modal-footer {
            border-color: var(--border-color);
        }

        body.admin-body.dark-mode .text-muted {
            color: #94a3b8 !important;
        }

        body.admin-body.dark-mode .text-dark {
            color: var(--text-main) !important;
        }
        
        body.admin-body.dark-mode .top-navbar .btn {
            color: var(--text-main);
        }
        
        body.admin-body.dark-mode .border {
            border-color: var(--border-color) !important;
        }
    </style>
</head>
<body class="@auth admin-body @endauth">
    @auth
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0 fw-bold d-flex align-items-center">
                <i class="fas fa-graduation-cap text-primary"></i>
                <span>Result <strong>Admin</strong></span>
            </h4>
        </div>
        <div class="sidebar-content">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.results.index') }}" class="{{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                <i class="fas fa-database"></i> <span>Manage Results</span>
            </a>
            <a href="{{ route('admin.template.index') }}" class="{{ request()->routeIs('admin.template.*') ? 'active' : '' }}">
                <i class="fas fa-magic"></i> <span>Result Designer</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-sliders-h"></i> <span>System Settings</span>
            </a>
            <div class="px-4 mt-4 mb-2 small text-uppercase text-white-50 fw-bold sidebar-heading">
                <span>Account</span>
            </div>
            <a href="{{ route('admin.logout') }}">
                <i class="fas fa-power-off"></i> <span>Logout</span>
            </a>
        </div>
    </div>
    @endauth

    <div class="@auth main-wrapper @else container @endauth">
        @auth
        <!-- Top Navbar -->
        <header class="top-navbar no-print">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="ms-auto d-flex align-items-center">
                <button class="btn border-0 me-3 shadow-none" id="darkModeToggle" title="Toggle Dark Mode">
                    <i class="fas fa-moon fs-5 text-secondary" id="darkModeIcon"></i>
                </button>
                <div class="dropdown">
                    <button class="btn border-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <span class="fw-bold d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 rounded-3 mt-2">
                        <li>
                            <a class="dropdown-item py-2 rounded-2" href="{{ route('admin.logout') }}">
                                <i class="fas fa-sign-out-alt me-2 text-danger"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>


        @endauth

        <main class="p-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function() {
            // Sidebar Toggle Logic
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'collapsed') {
                $('body').addClass('sidebar-collapsed');
            }

            $('#sidebarToggle').on('click', function() {
                if ($(window).width() > 992) {
                    $('body').toggleClass('sidebar-collapsed');
                    if ($('body').hasClass('sidebar-collapsed')) {
                        localStorage.setItem('sidebarState', 'collapsed');
                    } else {
                        localStorage.setItem('sidebarState', 'expanded');
                    }
                } else {
                    $('body').toggleClass('mobile-sidebar-active');
                }
            });

            // Close mobile sidebar when clicking the overlay
            $('#sidebarOverlay').on('click', function() {
                $('body').removeClass('mobile-sidebar-active');
            });
            
            // Fallback: Close mobile sidebar when clicking outside (on very small screens if overlay misses)
            $(document).on('click', function(e) {
                if ($(window).width() <= 992) {
                    if (!$(e.target).closest('.sidebar').length && !$(e.target).closest('#sidebarToggle').length && !$(e.target).closest('#sidebarOverlay').length) {
                        $('body').removeClass('mobile-sidebar-active');
                    }
                }
            });
            
            // Dark Mode Logic (Admin Only)
            if ($('body').hasClass('admin-body')) {
                const darkMode = localStorage.getItem('adminDarkMode');
                if (darkMode === 'enabled') {
                    $('body').addClass('dark-mode');
                    $('#darkModeIcon').removeClass('fa-moon').addClass('fa-sun');
                }

                $('#darkModeToggle').on('click', function() {
                    $('body').toggleClass('dark-mode');
                    if ($('body').hasClass('dark-mode')) {
                        localStorage.setItem('adminDarkMode', 'enabled');
                        $('#darkModeIcon').removeClass('fa-moon').addClass('fa-sun');
                    } else {
                        localStorage.setItem('adminDarkMode', 'disabled');
                        $('#darkModeIcon').removeClass('fa-sun').addClass('fa-moon');
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
