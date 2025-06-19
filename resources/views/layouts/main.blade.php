<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hotel Management System')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a69bd;
            --primary-dark: #3a57a1;
            --primary-light: #6b8cd4;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
            --border-color: #e9ecef;
            --text-primary: #343a40;
            --text-secondary: #6c757d;
            --text-muted: #adb5bd;
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #e9ecef;
            --border-radius: 8px;
            --border-radius-sm: 6px;
            --border-radius-lg: 12px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 10px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            font-size: 16px;
        }
        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 220px;
            background: var(--light-color);
            color: var(--text-primary);
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
        }
        .sidebar.collapsed {
            width: 56px !important;
        }
        .sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        .sidebar.collapsed .sidebar-logo {
            margin: 0 auto;
        }
        .sidebar.collapsed .sidebar-title,
        .sidebar.collapsed .nav-section-title,
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .sidebar-user-info {
            opacity: 0 !important;
            width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
            display: none !important;
        }
        .sidebar.collapsed .nav-item {
            margin: 0.5rem 0 !important;
            display: flex;
            justify-content: center;
        }
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.7rem 0 !important;
            gap: 0 !important;
        }
        .sidebar.collapsed .nav-icon {
            margin: 0 !important;
            font-size: 1.35rem !important;
            width: 32px !important;
            height: 32px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.8rem;
            min-height: 60px;
            flex-shrink: 0;
            background-color: var(--bg-primary);
        }
        .sidebar-logo {
            width: 32px;
            height: 32px;
            background: var(--primary-color);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
            color: white;
        }
        .sidebar-title {
            font-weight: 700;
            font-size: 1.1rem;
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.3s ease;
            color: var(--text-primary);
        }
        .sidebar.collapsed .sidebar-title {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
            overflow: hidden;
        }
        .nav-section {
            margin-bottom: 1.5rem;
        }
        .nav-section-title {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin: 0 1rem 0.8rem 1rem;
            transition: opacity 0.3s ease;
        }
        .sidebar.collapsed .nav-section-title {
            opacity: 0;
            height: 0;
            margin: 0;
            overflow: hidden;
        }
        .nav-item {
            margin: 0.3rem 0.8rem;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .nav-link:hover {
            background: var(--bg-tertiary);
            color: var(--primary-color);
            text-decoration: none;
            transform: translateX(3px);
        }
        .nav-link.active {
            background: var(--primary-color);
            color: white;
            box-shadow: var(--shadow-sm);
        }
        .nav-link.active:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateX(0);
        }
        .nav-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: var(--text-secondary);
            transition: color 0.2s ease;
        }
        .nav-link:hover .nav-icon {
            color: var(--primary-color);
        }
        .nav-link.active .nav-icon {
            color: white;
        }
        .nav-text {
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.3s ease;
            color: var(--text-primary);
        }
        .nav-link.active .nav-text {
            color: white;
        }
        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        .main-content {
            flex: 1;
            margin-left: 220px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - 220px);
        }
        .sidebar.collapsed + .main-content {
            margin-left: 60px;
            width: calc(100% - 60px);
        }
        .topbar {
            background: var(--bg-primary);
            padding: 0.8rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
            min-height: 60px;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--border-radius-sm);
            transition: all 0.2s ease;
        }
        .sidebar-toggle:hover {
            background: var(--bg-tertiary);
            color: var(--primary-color);
        }
        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 0.95rem;
        }
        .breadcrumb-item {
            color: var(--text-secondary);
        }
        .breadcrumb-item.active {
            color: var(--text-primary);
            font-weight: 600;
        }
        .breadcrumb-item a {
            color: var(--text-secondary);
            text-decoration: none;
        }
        .breadcrumb-item a:hover {
            color: var(--primary-color);
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.5rem 0.8rem;
            background: var(--bg-secondary);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
        }
        .user-menu:hover {
            background: var(--bg-tertiary);
            text-decoration: none;
            color: inherit;
        }
        .user-avatar {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .user-name {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-primary);
            line-height: 1.3;
        }
        .user-role {
            font-size: 0.75rem;
            color: var(--text-secondary);
            line-height: 1.3;
        }
        .content-area {
            flex: 1;
            padding: 1.5rem;
            max-width: 100%;
            overflow-x: hidden;
            background-color: var(--bg-secondary);
        }
        .page-header {
            margin-bottom: 1.8rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
            background-color: var(--bg-primary);
            margin: -1.5rem -1.5rem 1.8rem -1.5rem;
            padding: 1.5rem;
        }
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.4rem 0;
        }
        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1.05rem;
            margin: 0;
        }
        .card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
            margin-bottom: 1.5rem;
        }
        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-tertiary);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            font-weight: 600;
            color: var(--text-primary);
        }
        .card-body {
            padding: 1.25rem;
        }
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }
        .btn {
            border-radius: var(--border-radius-sm);
            font-weight: 500;
            padding: 0.6rem 1rem;
            transition: all 0.2s ease;
            border: none;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }
        .btn-sm {
            padding: 0.5rem 0.8rem;
            font-size: 0.85rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            box-shadow: var(--shadow-sm);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            box-shadow: var(--shadow-md);
            color: white;
            transform: translateY(-1px);
        }
        .table {
            background: var(--bg-primary);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            font-size: 0.95rem;
        }
        .table thead th {
            background: var(--bg-tertiary);
            border: none;
            font-weight: 600;
            color: var(--text-primary);
            padding: 1rem 1.25rem;
            font-size: 0.9rem;
        }
        .table tbody td {
            padding: 1rem 1.25rem;
            border-color: var(--border-color);
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background: var(--bg-secondary);
        }
        .form-control, .form-select {
            font-size: 0.95rem;
            padding: 0.7rem 1rem;
            border-radius: var(--border-radius-sm);
            border: 1px solid var(--border-color);
        }
        .form-label {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        .stats-card {
            background: var(--bg-primary);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }
        .stats-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        .stats-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
            color: var(--primary-color);
        }
        .stats-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0;
        }
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 60px;
                transform: translateX(0);
            }
            .main-content {
                margin-left: 60px;
                width: calc(100% - 60px);
            }
            .sidebar .sidebar-title,
            .sidebar .nav-section-title,
            .sidebar .nav-text,
            .sidebar .sidebar-user-info {
                opacity: 0;
                width: 0;
                overflow: hidden;
            }
            .sidebar-toggle {
                display: none;
            }
            .topbar-left {
                gap: 0.5rem;
            }
            .user-menu .user-info {
                display: none;
            }
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 100vw !important;
                max-width: 100vw !important;
                border-radius: 0;
                box-shadow: var(--shadow-lg);
                z-index: 1050;
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.show,
            .expanded-sidebar .sidebar {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
                width: 100vw !important;
            }
            .content-area {
                padding: 0.75rem !important; /* Add a little padding for mobile readability */
            }
            body, html, .main-wrapper {
                overflow-x: hidden;
            }
            .sidebar-toggle {
                display: block;
            }
            .user-menu .user-info {
                display: flex;
            }
        }
        .row {
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }
        .row > * {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            margin-bottom: 1rem;
        }
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--text-muted);
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }
        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            display: none;
        }
        @media (max-width: 768px) {
            .sidebar-overlay {
                display: block;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.3);
                z-index: 1049;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s;
            }
            .expanded-sidebar .sidebar-overlay,
            .sidebar.show ~ .sidebar-overlay {
                opacity: 1;
                pointer-events: auto;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="main-wrapper">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="bi bi-building"></i>
                </div>
                <div class="sidebar-title">Hotel Manager</div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Overview</div>
                    <div class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-bar-chart-line"></i></div>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('front-desk.index') }}" target="_blank"
                           class="nav-link {{ request()->routeIs('front-desk.*') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-display"></i></div>
                            <span class="nav-text">Front Desk</span>
                        </a>
                    </div>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Operations</div>
                    <div class="nav-item">
                        <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-calendar-check"></i></div>
                            <span class="nav-text">Reservations</span>
                            @if(isset($pendingReservations) && $pendingReservations > 0)
                                <span class="badge bg-warning text-dark ms-auto nav-text">{{ $pendingReservations }}</span>
                            @endif
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('checkins.index') }}" class="nav-link {{ request()->routeIs('checkins.*') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-arrow-right-square"></i></div>
                            <span class="nav-text">Check-ins</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('guests.index') }}" class="nav-link {{ request()->routeIs('guests.*') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-people"></i></div>
                            <span class="nav-text">Guests</span>
                        </a>
                    </div>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Management</div>
                    <div class="nav-item">
                        <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-house-door"></i></div>
                            <span class="nav-text">Rooms</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('room-types.index') }}" class="nav-link {{ request()->routeIs('room-types.*') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-grid-3x3-gap"></i></div>
                            <span class="nav-text">Room Types</span>
                        </a>
                    </div>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Financial</div>
                    <div class="nav-item">
                        <a href="{{ route('folios.index') }}" class="nav-link {{ request()->routeIs('folios.*') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-receipt"></i></div>
                            <span class="nav-text">Folios</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('reports.revenue') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-graph-up-arrow"></i></div>
                            <span class="nav-text">Reports</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div class="main-content">
            <div class="topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                <div class="topbar-right">
                    <div class="dropdown d-md-block">
                        <div class="user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="user-info d-none d-md-block d-lg-block">
                                <div class="user-name">{{ auth()->user()->name ?? 'User' }}</div>
                                <div class="user-role">Admin</div>
                            </div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="content-area">
                @yield('content')
            </div>
        </div>
        <div class="sidebar-overlay"></div>
    </div>
    <script src="{{ asset('asset/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');
            const html = document.documentElement;

            function initializeSidebarState() {
                if (window.innerWidth > 992) {
                    const savedState = localStorage.getItem('sidebarCollapsed');
                    if (savedState === 'true') {
                        sidebar.classList.add('collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                    }
                    sidebar.classList.remove('show');
                    html.classList.remove('expanded-sidebar');
                } else {
                    sidebar.classList.add('collapsed');
                    sidebar.classList.remove('show');
                    html.classList.remove('expanded-sidebar');
                    localStorage.removeItem('sidebarCollapsed');
                }
            }
            initializeSidebarState();

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (window.innerWidth > 992) {
                        sidebar.classList.toggle('collapsed');
                        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                    } else {
                        sidebar.classList.toggle('show');
                        html.classList.toggle('expanded-sidebar');
                    }
                });
            }

            // Overlay click closes sidebar on mobile
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    html.classList.remove('expanded-sidebar');
                });
            }

            // Click outside sidebar closes it on mobile
            document.body.addEventListener('click', function(e) {
                if (
                    window.innerWidth <= 768 &&
                    sidebar.classList.contains('show') &&
                    !sidebar.contains(e.target) &&
                    !sidebarToggle.contains(e.target)
                ) {
                    sidebar.classList.remove('show');
                    html.classList.remove('expanded-sidebar');
                }
            });

            window.addEventListener('resize', initializeSidebarState);
        });
    </script>
    @stack('scripts')
</body>
</html>