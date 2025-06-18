<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hotel Management System')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons CDN as fallback -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --border-radius: 6px;
            --border-radius-sm: 4px;
            --border-radius-lg: 8px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.5;
            margin: 0;
            padding: 0;
            font-size: 16px; /* Increased from 14px */
        }

        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 200px; /* Increased from 160px */
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: 50px; /* Increased from 40px */
        }

        .sidebar-header {
            padding: 0.8rem; /* Increased padding */
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            min-height: 50px; /* Increased from 40px */
            flex-shrink: 0;
        }

        .sidebar-logo {
            width: 28px; /* Increased from 22px */
            height: 28px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem; /* Increased from 0.8rem */
            color: white;
        }

        .sidebar-title {
            font-weight: 700;
            font-size: 1rem; /* Increased from 0.8rem */
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.3s ease;
            color: white;
        }

        .sidebar.collapsed .sidebar-title {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-nav {
            padding: 0.6rem 0; /* Increased padding */
            flex: 1;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 1.2rem; /* Increased from 0.8rem */
        }

        .nav-section-title {
            font-size: 0.75rem; /* Increased from 0.6rem */
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8); /* Better contrast */
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0 0.8rem 0.6rem 0.8rem; /* Increased margins */
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .nav-section-title {
            opacity: 0;
            height: 0;
            margin: 0;
            overflow: hidden;
        }

        .nav-item {
            margin: 0.2rem 0.6rem; /* Increased margins */
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.8rem; /* Increased gap */
            padding: 0.6rem 0.8rem; /* Increased padding */
            color: rgba(255, 255, 255, 0.9); /* Better contrast */
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem; /* Increased from 0.75rem */
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15); /* More visible hover */
            color: white;
            text-decoration: none;
            transform: translateX(2px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2); /* More visible active state */
            color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .nav-icon {
            width: 18px; /* Increased from 14px */
            height: 18px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem; /* Increased from 0.8rem */
            color: white;
        }

        .nav-text {
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.3s ease;
            color: white;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.8rem; /* Increased padding */
            flex-shrink: 0;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.6rem; /* Increased gap */
            padding: 0.6rem; /* Increased padding */
            border-radius: 4px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .sidebar-user:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .sidebar-user-avatar {
            width: 24px; /* Increased from 20px */
            height: 24px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.8rem; /* Increased from 0.7rem */
            flex-shrink: 0;
        }

        .sidebar-user-info {
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .sidebar-user-info {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-user-name {
            font-weight: 600;
            font-size: 0.85rem; /* Increased from 0.7rem */
            color: white;
            line-height: 1.2;
        }

        .sidebar-user-role {
            font-size: 0.75rem; /* Increased from 0.6rem */
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.2;
        }

        .main-content {
            flex: 1;
            margin-left: 200px; /* Increased from 160px */
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - 200px); /* Adjusted */
        }

        .sidebar.collapsed + .main-content {
            margin-left: 50px; /* Increased from 40px */
            width: calc(100% - 50px);
        }

        .topbar {
            background: var(--bg-primary);
            padding: 0.8rem 1rem; /* Increased padding */
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
            min-height: 50px; /* Increased from 40px */
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.8rem; /* Increased gap */
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.1rem; /* Increased from 0.9rem */
            cursor: pointer;
            padding: 0.4rem; /* Increased padding */
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .sidebar-toggle:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 0.9rem; /* Increased from 0.75rem */
        }

        .breadcrumb-item {
            color: var(--text-secondary);
        }

        .breadcrumb-item.active {
            color: var(--text-primary);
            font-weight: 500;
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
            gap: 0.6rem;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.6rem; /* Increased padding */
            background: var(--bg-secondary);
            border-radius: 15px;
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
            width: 22px; /* Increased from 18px */
            height: 22px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.75rem; /* Increased from 0.6rem */
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.8rem; /* Increased from 0.7rem */
            color: var(--text-primary);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.7rem; /* Increased from 0.6rem */
            color: var(--text-secondary);
            line-height: 1.2;
        }

        .content-area {
            flex: 1;
            padding: 1rem; /* Increased from 0.6rem */
            max-width: 100%;
            overflow-x: hidden;
        }

        .page-header {
            margin-bottom: 1.2rem; /* Increased from 0.8rem */
        }

        .page-title {
            font-size: 1.5rem; /* Increased from 1.25rem */
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.3rem 0;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem; /* Increased from 0.8rem */
            margin: 0;
        }

        /* Cards */
        .card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
            margin-bottom: 1rem; /* Increased from 0.6rem */
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            padding: 0.8rem; /* Increased from 0.5rem */
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-secondary);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .card-body {
            padding: 0.8rem; /* Increased from 0.5rem */
        }

        .card-title {
            font-size: 1rem; /* Increased from 0.85rem */
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        /* Buttons */
        .btn {
            border-radius: var(--border-radius-sm);
            font-weight: 500;
            padding: 0.5rem 0.8rem; /* Increased padding */
            transition: all 0.2s ease;
            border: none;
            font-size: 0.9rem; /* Increased from 0.75rem */
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem; /* Increased gap */
            justify-content: center;
        }

        .btn-sm {
            padding: 0.4rem 0.6rem; /* Increased padding */
            font-size: 0.8rem; /* Increased from 0.7rem */
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
        }

        /* Tables */
        .table {
            background: var(--bg-primary);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            font-size: 0.9rem; /* Increased from 0.75rem */
        }

        .table thead th {
            background: var(--bg-secondary);
            border: none;
            font-weight: 600;
            color: var(--text-primary);
            padding: 0.8rem; /* Increased from 0.5rem */
            font-size: 0.85rem; /* Increased from 0.7rem */
        }

        .table tbody td {
            padding: 0.8rem; /* Increased from 0.5rem */
            border-color: var(--border-color);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--bg-secondary);
        }

        /* Form controls */
        .form-control, .form-select {
            font-size: 0.9rem; /* Increased from 0.75rem */
            padding: 0.6rem 0.8rem; /* Increased padding */
            border-radius: var(--border-radius-sm);
        }

        .form-label {
            font-size: 0.85rem; /* Increased from 0.7rem */
            font-weight: 500;
            margin-bottom: 0.4rem; /* Increased margin */
        }

        /* Stats cards */
        .stats-card {
            background: var(--bg-primary);
            border-radius: var(--border-radius);
            padding: 1rem; /* Increased from 0.6rem */
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .stats-value {
            font-size: 1.8rem; /* Increased from 1.4rem */
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .stats-label {
            font-size: 0.85rem; /* Increased from 0.7rem */
            color: var(--text-secondary);
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .content-area {
                padding: 0.8rem;
            }

            .topbar {
                padding: 0.6rem;
            }

            .page-title {
                font-size: 1.3rem;
            }
        }

        /* Compact row spacing */
        .row {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }

        .row > * {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            margin-bottom: 0.8rem;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--text-muted);
            border-radius: 2px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
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
                            <div class="nav-icon"><i class="bi bi-speedometer2"></i></div>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('front-desk.index') }}" class="nav-link {{ request()->routeIs('front-desk.*') ? 'active' : '' }}">
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

            <div class="sidebar-footer">
                <div class="dropdown">
                    <div class="sidebar-user" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="sidebar-user-avatar">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="sidebar-user-info">
                            <div class="sidebar-user-name">{{ auth()->user()->name ?? 'User' }}</div>
                            <div class="sidebar-user-role">Admin</div>
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
                    <!-- Only show user menu on mobile -->
                    <div class="dropdown d-md-none">
                        <div class="user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
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
    </div>

    <script src="{{ asset('asset/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            // Initialize sidebar state
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
            }
            
            // Toggle sidebar
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });

            // Mobile sidebar toggle
            if (window.innerWidth <= 768) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.toggle('show');
                });
                
                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
