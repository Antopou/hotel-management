<style>
    /* General Sidebar Styles */
    #sidebar {
        background-color: #f8f9fa; /* Light grey background */
        border-right: 1px solid #e0e0e0; /* Slightly darker grey border */
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
        color: #495057; /* Darker grey for icon */
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
        color: #343a40; /* Dark text for logo */
    }

    .logo-container i {
        font-size: 2rem;
        margin-right: 0.5rem;
        color: #495057; /* Darker grey for logo icon */
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
        text-decoration: none;
        color: #495057; /* Dark grey for nav links */
        transition: background-color 0.2s ease, color 0.2s ease;
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

    #sidebar .nav-link:hover {
        background-color: #e9ecef; /* Slightly lighter grey on hover */
        color: #212529; /* Darker text on hover */
    }

    /* IMPORTANT: This rule ensures all active links are grey */
    #sidebar .nav-link.active,
    .nav-item.dropdown .nav-link.active-dropdown-parent,
    .dropdown-menu-custom .dropdown-item.active { /* Added .dropdown-item.active */
        background-color: #dee2e6; /* A bit darker grey for active */
        color: #212529; /* Dark text for active links */
    }

    .sidebar-user {
        padding: 0.5rem 1rem;
        border-top: 1px solid #e0e0e0;
        background-color: #f8f9fa;
        position: relative;
    }

    .sidebar-user-name {
        display: inline-block;
        transition: opacity 0.3s ease, max-width 0.3s ease;
        color: #495057; /* Dark grey for username */
    }

    html:not(.expanded-sidebar) .sidebar-user-name {
        opacity: 0;
        max-width: 0;
        overflow: hidden;
    }

    .sidebar-user a {
        color: #495057; /* Dark grey for user link icon */
    }

    .sidebar-user a:hover {
        color: #212529; /* Darker grey on hover */
    }

    .toggle-icon {
        cursor: pointer;
        font-size: 0.7rem;
        display: inline-block;
        margin-left: 0.5rem;
    }

    /* User Dropdown Customization */
    .dropdown-menu-custom {
        min-width: 200px;
        z-index: 1055;
        display: none;
        position: absolute;
        background-color: #fff;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0;
        border-radius: 0.25rem;
        padding: 0.5rem 0;
        left: 100%;
        top: auto;
        bottom: 0;
        margin-left: 0.5rem;
        transform: translateY(0);
        transition: all 0.3s ease-in-out;
        opacity: 0;
        pointer-events: none;
    }

    /* Show dropdown when toggled */
    .sidebar-user.show .dropdown-menu-custom {
        display: block;
        opacity: 1;
        pointer-events: auto;
    }

    /* Expanded sidebar: user dropdown at bottom */
    .expanded-sidebar .sidebar-user.show .dropdown-menu-custom {
        left: auto;
        right: 0;
        bottom: 100%;
        transform: translateY(-0.5rem);
    }

    /* Collapsed sidebar: user dropdown to the right */
    html:not(.expanded-sidebar) .sidebar-user.show .dropdown-menu-custom {
        left: 60px;
        top: var(--user-icon-top, 0px);
        bottom: auto;
        transform: translateY(0.5rem);
    }

    #sidebar .dropdown-menu-custom .dropdown-item {
        padding: 0.5rem 1rem 0.5rem 2.25rem;
        font-size: 1rem;
        color: #212529;
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    #sidebar .dropdown-menu-custom .dropdown-item:hover {
        background-color: #f0f0f0;
        color: #16181b;
    }

    #sidebar .dropdown-menu-custom .dropdown-item i {
        font-size: 1rem;
        margin-right: 0.5rem;
        color: #6c757d;
    }

    /* Report Dropdown Specific Styles */
    .nav-item.dropdown .dropdown-menu-custom {
        position: static;
        float: none;
        width: 100%;
        background-color: transparent;
        border: none;
        box-shadow: none;
        padding: 0;
        transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        display: block;
        pointer-events: none;
    }
    .nav-item.dropdown.show .dropdown-menu-custom {
        max-height: 200px;
        opacity: 1;
        pointer-events: auto;
    }
    .nav-item.dropdown .dropdown-item {
        padding-left: 2.5rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        color: #343a40;
    }
    .nav-item.dropdown .dropdown-item:hover {
        background-color: #f0f0f0;
    }

    /* --- This block is the fix for collapsed floating dropdown with text --- */
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom {
        position: fixed !important;
        left: 60px;  /* Align it right next to the 60px sidebar */
        min-width: 180px; /* Adjust min-width to accommodate text */
        width: auto; /* Allow content to dictate width */
        border-radius: 0.75rem;
        background: #fff;
        box-shadow: 0 6px 24px rgba(0,0,0,0.15);
        padding: 0.5rem 0;
        max-height: none;
        opacity: 1;
        display: none; /* Hidden by default, shown by JS */
        pointer-events: auto;
        z-index: 2000;
        overflow: visible;
    }
    html:not(.expanded-sidebar) .nav-item.dropdown.show .dropdown-menu-custom {
        display: block; /* Show when 'show' class is present */
    }
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item {
        display: flex;
        justify-content: flex-start; /* Align icon and text to the left */
        align-items: center;
        padding: 0.6rem 1rem; /* Adjust padding for spacing */
        font-size: 1rem; /* Standard font size for readability */
        color: #212529; /* Dark text for readability */
        background: transparent;
        border: none;
        transition: background 0.2s;
    }
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item span {
        display: inline-block; /* Ensure text is visible */
        max-width: none; /* Remove any max-width constraints */
        opacity: 1; /* Ensure text is fully opaque */
        overflow: visible; /* Ensure text is not clipped */
        white-space: nowrap; /* Prevent text wrapping */
        transition: none; /* No transition needed here */
    }
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item:hover,
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item.active {
        background: #e9ecef;
        color: #212529;
    }
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item i {
        margin-right: 0.75rem; /* Add margin between icon and text */
        font-size: 1.25rem; /* Adjust icon size to be slightly larger */
    }

    /* ========== SIDEBAR COLLAPSED DROPDOWN INLINE FIX ========== */

    /* When sidebar is collapsed, make the dropdown appear inline, not floating */
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom {
        position: static !important;
        left: 0 !important;
        top: auto !important;
        min-width: 0 !important;
        width: 100% !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
        display: block !important;
        pointer-events: auto !important;
        z-index: auto !important;
        overflow: visible !important;
        opacity: 1 !important;
        max-height: none !important;
        margin-left: 0 !important;
        transform: none !important;
    }

    /* In collapsed sidebar, dropdown items: hide text, show only icons, center them */
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item span {
        display: none !important;
    }
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item {
        justify-content: center !important;
        align-items: center !important;
        padding: 0.75rem 0 !important;
        width: 100% !important;
        font-size: 1.5rem !important;
        background: none !important;
        color: #495057 !important;
        border: none !important;
    }
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item i {
        margin-right: 0 !important;
        font-size: 1.5rem !important;
    }
    /* Highlight active dropdown item in collapsed mode */
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item.active {
        background: #dee2e6 !important;
        color: #212529 !important;
    }
    /* Remove shadow/float from the report dropdown in collapsed */
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom {
        box-shadow: none !important;
    }

    /* Collapsed sidebar: report dropdown is inline, not floating, only icons shown */
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom {
        position: static !important;
        left: 0 !important;
        top: auto !important;
        min-width: 0 !important;
        width: 100% !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
        display: none !important;        /* Hidden by default */
        pointer-events: auto !important;
        z-index: auto !important;
        overflow: visible !important;
        opacity: 1 !important;
        margin-left: 0 !important;
        transform: none !important;
    }

    html:not(.expanded-sidebar) .nav-item.dropdown.show .dropdown-menu-custom {
        display: block !important;      /* Show when parent has .show */
    }

    /* In collapsed sidebar, dropdown items: hide text, show only icons, center them */
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item span {
        display: none !important;
    }
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item {
        justify-content: center !important;
        align-items: center !important;
        padding: 0.75rem 0 !important;
        width: 100% !important;
        font-size: 1.5rem !important;
        background: none !important;
        color: #495057 !important;
        border: none !important;
    }
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item i {
        margin-right: 0 !important;
        font-size: 1.5rem !important;
    }
    html:not(.expanded-sidebar) .nav-item.dropdown .dropdown-menu-custom .dropdown-item.active {
        background: #dee2e6 !important;
        color: #212529 !important;
    }

    /* Make sure dropdown icons are smaller when expanded */
    .expanded-sidebar #sidebar .nav-item.dropdown .dropdown-menu-custom .dropdown-item i {
        font-size: 1.15rem !important;
        margin-right: 0.5rem !important;
        color: #212529 !important;
        vertical-align: middle !important;
    }

    /* Top-level nav icons bigger */
    #sidebar .nav-link i {
        font-size: 1.5rem;
    }

    /* Collapsed sidebar: show dropdown icons SMALLER, matching expanded style */
    html:not(.expanded-sidebar) #sidebar .nav-item.dropdown .dropdown-menu-custom .dropdown-item i {
        font-size: 1.15rem !important;       /* small icon */
        margin-right: 0 !important;       /* centered, no margin */
        color: #212529 !important;
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
            <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center gap-2 report-dropdown-toggle {{ request()->routeIs(['reservations.*', 'checkins.*']) ? 'active-dropdown-parent' : '' }}" href="#" id="reportDropdown" role="button">
                    <i class="bi bi-bar-chart-fill"></i> <span>Report</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="reportDropdown">
                    <li>
                        <a class="dropdown-item {{ request()->routeIs('reservations.*') ? 'active' : '' }}" href="{{ route('reservations.index') }}">
                            <i class="bi bi-calendar-check-fill me-2"></i> <span>Reservations</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->routeIs('checkins.*') ? 'active' : '' }}" href="{{ route('checkins.index') }}">
                            <i class="bi bi-arrow-right-square-fill me-2"></i> <span>Check-ins</span>
                        </a>
                    </li>
                </ul>
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
        const sidebarUserLink = document.getElementById('sidebarUserDropdown');
        const sidebarUser = sidebarUserLink.closest('.sidebar-user');

        hamburgerBtn.addEventListener('click', () => {
            document.documentElement.classList.toggle('expanded-sidebar');
            const isExpanded = document.documentElement.classList.contains('expanded-sidebar');
            localStorage.setItem('sidebarExpanded', isExpanded);
            // Close any open dropdowns when sidebar state changes
            document.querySelectorAll('.nav-item.dropdown.show').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
            document.querySelectorAll('.sidebar-user.show').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        });

        // Handle the user dropdown toggle
        sidebarUserLink.addEventListener('click', (e) => {
            e.preventDefault();
            // Close other dropdowns
            document.querySelectorAll('.nav-item.dropdown.show').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
            sidebarUser.classList.toggle('show');

            if (!document.documentElement.classList.contains('expanded-sidebar')) {
                const iconRect = sidebarUserLink.getBoundingClientRect();
                document.documentElement.style.setProperty('--user-icon-top', `${iconRect.top}px`);
            }
        });

        // Close user dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!sidebarUser.contains(event.target) && sidebarUser.classList.contains('show')) {
                sidebarUser.classList.remove('show');
            }
        });

        // Report dropdown toggle
        const reportDropdownItem = document.querySelector('.nav-item.dropdown');
        const reportDropdownToggle = document.getElementById('reportDropdown');
        const reportMenu = reportDropdownToggle?.nextElementSibling;

        function positionReportDropdownMenu() {
            if (
                !document.documentElement.classList.contains('expanded-sidebar')
                && reportDropdownToggle
                && reportMenu
            ) {
                const iconRect = reportDropdownToggle.getBoundingClientRect();
                reportMenu.style.top = iconRect.top + "px"; // Position based on the icon's top
            } else if (reportMenu) {
                reportMenu.style.top = ""; // reset in expanded mode
            }
        }

        if (reportDropdownToggle && reportMenu && reportDropdownItem) {
            reportDropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                // Close other dropdowns (specifically the user dropdown)
                document.querySelectorAll('.sidebar-user.show').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
                reportDropdownItem.classList.toggle('show');
                positionReportDropdownMenu(); // Call position function on click
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function outsideReportDropdown(ev) {
                // Ensure the click is not inside the report dropdown item itself
                if (!reportDropdownItem.contains(ev.target) && reportDropdownItem.classList.contains('show')) {
                    reportDropdownItem.classList.remove('show');
                }
            });
        }

        // Set initial sidebar state based on localStorage
        const sidebarExpanded = localStorage.getItem('sidebarExpanded');
        if (sidebarExpanded === 'true') {
            document.documentElement.classList.add('expanded-sidebar');
        }

        // Check if a sub-item of "Report" is active on page load
        const reservationsLink = document.querySelector('.dropdown-item[href="{{ route("reservations.index") }}"]');
        const checkinsLink = document.querySelector('.dropdown-item[href="{{ route("checkins.index") }}"]');

        // Check if any of the report sub-routes are active
        const isReportSubRouteActive = (reservationsLink && reservationsLink.classList.contains('active')) ||
                                       (checkinsLink && checkinsLink.classList.contains('active'));

        if (isReportSubRouteActive) {
            reportDropdownItem.classList.add('show'); // Expand the report dropdown
            // Ensure the main "Report" link also looks active if its children are active
            reportDropdownToggle.classList.add('active-dropdown-parent');
            positionReportDropdownMenu(); // Ensure correct positioning if active on load
        }
    });
</script>