<style>
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
        left: 62px; /* Sidebar width + border */
        top: unset;
        bottom: unset;
        transform: translateY(0);

        /* Correct placement - calculate with icon's position */
        top: calc(var(--user-icon-top, 0px) + 20px); /* 20px offset below the icon (adjusted for better visual) */
    }
</style>

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
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}" href="{{ route('rooms.index') }}">
                    <i class="bi bi-house-door-fill"></i> <span>Rooms</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('room-types.*') ? 'active' : '' }}" href="{{ route('room-types.index') }}">
                    <i class="bi bi-tags-fill"></i> <span>Room Types</span>
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
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('folios.*') ? 'active' : '' }}" href="{{ route('folios.index') }}">
                    <i class="bi bi-receipt"></i> <span>Bills</span>
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const sidebarUserLink = document.getElementById('sidebarUserDropdown'); // Renamed to avoid conflict
        const sidebarUser = sidebarUserLink.closest('.sidebar-user'); // Use the link element to find parent

        hamburgerBtn.addEventListener('click', () => {
            document.documentElement.classList.toggle('expanded-sidebar');
            const isExpanded = document.documentElement.classList.contains('expanded-sidebar');
            localStorage.setItem('sidebarExpanded', isExpanded);
        });

        // Handle the user dropdown toggle
        sidebarUserLink.addEventListener('click', (e) => {
            e.preventDefault();
            sidebarUser.classList.toggle('show');

            if (!document.documentElement.classList.contains('expanded-sidebar')) {
                const iconRect = sidebarUserLink.getBoundingClientRect(); // Get position of the icon/link
                // Set a CSS variable with the icon's top position for the dropdown's 'top' calculation
                document.documentElement.style.setProperty('--user-icon-top', `${iconRect.top}px`);
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!sidebarUser.contains(event.target) && sidebarUser.classList.contains('show')) {
                sidebarUser.classList.remove('show');
            }
        });
    });
</script>