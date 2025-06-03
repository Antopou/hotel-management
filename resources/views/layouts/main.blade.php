<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hotel Management</title>

    <script>
        if (localStorage.getItem('sidebarExpanded') === 'true') {
            document.documentElement.classList.add('expanded-sidebar');
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@100..900&display=swap" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        #sidebar {
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            width: 60px;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            transition: width 0.3s ease;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            height: 100vh;
            z-index: 1030;
        }

        .expanded-sidebar #sidebar {
            width: 200px;
        }

        #content {
            flex-grow: 1;
            padding: 1rem;
            margin-left: 60px;
            transition: margin-left 0.3s ease;
        }

        .expanded-sidebar #content {
            margin-left: 200px;
        }

        html:not(.js-ready) #sidebar,
        html:not(.js-ready) #content {
            transition: none !important;
        }

        .expanded-sidebar #sidebar .sidebar-header {
            justify-content: space-between;
            padding: 1rem;
            flex-direction: row-reverse;
        }

        .expanded-sidebar #sidebar .sidebar-content {
            align-items: flex-start;
        }

        .expanded-sidebar #sidebar .logo-container {
            display: block;
        }

        .expanded-sidebar #sidebar .nav-link span,
        .expanded-sidebar .logo-container span {
            max-width: 150px;
            opacity: 1;
        }

        .sidebar-header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 0.5rem;
        }

        .sidebar-header > * {
            line-height: 1;
            display: flex;
            align-items: center;
        }

        #hamburger-btn {
            background: none;
            border: none;
            cursor: pointer;
            margin-top: 12px;
            font-size: 1.5rem;
        }

        #sidebar .sidebar-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }

        .logo-container {
            padding: 0;
            text-align: left;
            width: auto;
            display: none;
        }

        .logo-container a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
        }

        .logo-container i {
            font-size: 2rem;
            margin-right: 0.5rem;
        }

        .logo-container span {
            white-space: nowrap;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-width 0.3s ease, opacity 0.3s ease;
        }

        #sidebar .nav-link {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: flex-start;
            gap: 0.5rem;
            white-space: nowrap;
        }

        #sidebar .nav-link i {
            font-size: 1.5rem;
            transition: margin 0.2s ease;
        }

        #sidebar .nav-link span {
            display: inline-block;
            max-width: 0;
            opacity: 0;
            transform: translateX(0);
            transition: max-width 0.3s ease, opacity 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        #sidebar .nav-link.active {
            background-color: #e9ecef;
        }

        .custom-modal {
            max-width: 1000px;
        }

        .custom-modal .modal-content {
            height: 90vh;
            overflow-y: auto;
        }

        .card-hover:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
            transition: box-shadow 0.2s ease-in-out;
            transform: translateY(-2px);
        }
.sidebar-user {
    padding: 0.5rem 1rem;
    border-top: 1px solid #dee2e6;
    background-color: #f8f9fa;
    position: relative;
}

.sidebar-user-name {
    display: inline-block;
    transition: opacity 0.3s ease, max-width 0.3s ease;
}

html:not(.expanded-sidebar) .sidebar-user-name {
    opacity: 0;
    max-width: 0;
    overflow: hidden;
}

.toggle-icon {
    cursor: pointer;
    font-size: 0.7rem;
    display: inline-block;
    margin-left: 0.5rem;
}

.dropdown-menu-custom {
    min-width: 200px;
    z-index: 1055;
    display: none;
}

/* Show dropdown when toggled */
.sidebar-user.show .dropdown-menu-custom {
    display: block;
}

/* Expanded sidebar: show at bottom (logout area) */
.expanded-sidebar .sidebar-user.show .dropdown-menu-custom {
    position: absolute;
    bottom: 60px; /* Match the height of the sidebar-user */
    right: 0;
    left: auto;
    transform: translateY(0);
}

/* Collapsed sidebar: show fully below icon */
html:not(.expanded-sidebar) .sidebar-user.show .dropdown-menu-custom {
    position: fixed;
    left: 62px; /* Sidebar width */
    top: unset;
    bottom: unset;
    transform: translateY(0);
    
    /* Correct placement - calculate with icon's position */
    top: calc(var(--user-icon-top, 0) - 40px); /* 40px offset below the icon */
}

    </style>
</head>
<body>
    <div id="sidebar">
        <div class="sidebar-header">
            <button id="hamburger-btn" class="btn btn-outline-secondary">
                <i class="bi bi-list"></i>
            </button>
            <div class="logo-container">
                <a class="navbar-brand text-uppercase" href="{{ route('rooms.index') }}">
                    <strong><i class="bi bi-house-door-fill me-1"></i> <span>Hotel POS</span></strong>
                </a>
            </div>
        </div>
        <div class="sidebar-content d-flex flex-column flex-grow-1">
            <ul class="nav flex-column flex-grow-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}" href="{{ route('rooms.index') }}">
                        <i class="bi bi-house-door-fill"></i> <span>Rooms</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('room-types.*') ? 'active' : '' }}" href="{{ route('room-types.index') }}">
                        <i class="bi bi-key-fill"></i> <span>Room Types</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('guests.*') ? 'active' : '' }}" href="{{ route('guests.index') }}">
                        <i class="bi bi-people-fill"></i> <span>Guests</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}" href="{{ route('reservations.index') }}">
                        <i class="bi bi-calendar-check-fill"></i> <span>Reservations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('checkins.*') ? 'active' : '' }}" href="{{ route('checkins.index') }}">
                        <i class="bi bi-arrow-right-square-fill"></i> <span>Check-ins</span>
                    </a>
                </li>
            </ul>
            @auth
            <div class="sidebar-user dropdown w-100">
                <a href="#" class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 text-decoration-none text-dark"
                id="sidebarUserDropdown" role="button">
                    <i class="bi bi-person-circle fs-4"></i>
                    <span class="sidebar-user-name d-none d-md-inline">{{ Auth::user()->name }}</span>
                    <i class="bi bi-caret-down-fill toggle-icon"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm dropdown-menu-custom" id="customDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-fill me-2"></i> Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </div>

    <div id="content">
        <div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
            @if (session('success'))
                <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('success') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('error') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>

        @yield('content')
    </div>

    <script src="{{ asset('asset/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const hamburgerBtn = document.getElementById('hamburger-btn');

            requestAnimationFrame(() => {
                document.documentElement.classList.add('js-ready');
            });

            hamburgerBtn.addEventListener('click', () => {
                document.documentElement.classList.toggle('expanded-sidebar');
                const isExpanded = document.documentElement.classList.contains('expanded-sidebar');
                localStorage.setItem('sidebarExpanded', isExpanded);
            });

            const successToast = document.getElementById('successToast');
            const errorToast = document.getElementById('errorToast');
            if (successToast) new bootstrap.Toast(successToast, { delay: 3000 }).show();
            if (errorToast) new bootstrap.Toast(errorToast, { delay: 3000 }).show();
        });

        document.addEventListener("DOMContentLoaded", function () {
            const userLink = document.getElementById('sidebarUserDropdown');
            const sidebarUser = userLink.closest('.sidebar-user');
            const dropdownMenu = document.getElementById('customDropdown');

            userLink.addEventListener('click', (e) => {
                e.preventDefault();
                sidebarUser.classList.toggle('show');

                if (!document.documentElement.classList.contains('expanded-sidebar')) {
                    const iconRect = userLink.getBoundingClientRect();
                    document.documentElement.style.setProperty('--user-icon-top', `${iconRect.top}px`);
                }
            });
        });

    </script>

    @yield('scripts')
</body>
</html>
