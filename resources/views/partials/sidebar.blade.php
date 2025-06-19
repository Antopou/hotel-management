<div id="sidebar" class="d-flex flex-column">
    <div class="sidebar-header p-3 border-bottom">
        <div class="d-flex align-items-center">
            <div class="sidebar-logo me-3">
                <div class="logo-icon bg-gradient-primary text-white rounded-3 d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px;">
                    <i class="bi bi-building fs-5"></i>
                </div>
            </div>
            <div class="sidebar-brand flex-grow-1">
                <h6 class="mb-0 fw-bold text-dark">Hotel Manager</h6>
                <small class="text-muted">Professional Suite</small>
            </div>
            <button class="btn btn-sm btn-outline-secondary border-0 sidebar-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
        </div>
    </div>

    <nav class="sidebar-nav flex-grow-1 p-3">
        <div class="nav-section mb-4">
            <div class="nav-section-title text-muted text-uppercase small fw-semibold mb-2 px-2">
                <span class="sidebar-text">Overview</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('front-desk.index') }}" target="_blank"
                        class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('front-desk.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-display"></i>
                        </div>
                        <span class="sidebar-text">Front Desk</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section mb-4">
            <div class="nav-section-title text-muted text-uppercase small fw-semibold mb-2 px-2">
                <span class="sidebar-text">Operations</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a href="{{ route('reservations.index') }}"
                        class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <span class="sidebar-text">Reservations</span>
                        @if(isset($pendingReservations) && $pendingReservations > 0)
                            <span class="badge bg-warning text-dark ms-auto sidebar-text">{{ $pendingReservations }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('checkins.index') }}"
                        class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('checkins.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-arrow-right-square"></i>
                        </div>
                        <span class="sidebar-text">Check-ins</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('guests.index') }}"
                        class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('guests.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-people"></i>
                        </div>
                        <span class="sidebar-text">Guests</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section mb-4">
            <div class="nav-section-title text-muted text-uppercase small fw-semibold mb-2 px-2">
                <span class="sidebar-text">Management</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a href="{{ route('rooms.index') }}"
                        class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-house-door"></i>
                        </div>
                        <span class="sidebar-text">Rooms</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('room-types.index') }}"
                        class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('room-types.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </div>
                        <span class="sidebar-text">Room Types</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section mb-4">
            <div class="nav-section-title text-muted text-uppercase small fw-semibold mb-2 px-2">
                <span class="sidebar-text">Financial</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a href="{{ route('folios.index') }}"
                        class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('folios.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <span class="sidebar-text">Folios</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('reports.index') }}"
                        class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <span class="sidebar-text">Reports</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

</div>

<style>
    /* Modern Sidebar Styles */
    #sidebar {
        /* Even less bright, very subtle off-white/light gray */
        background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
        border-right: 1px solid var(--border-color);
        box-shadow: var(--shadow-lg);
    }

    .sidebar-header {
        /* Matching the softer tones with transparency */
        background: rgba(248, 249, 250, 0.8);
        backdrop-filter: blur(10px);
    }

    .sidebar-toggle {
        transition: all 0.2s ease;
    }

    .sidebar-toggle:hover {
        background-color: var(--light-color) !important;
        transform: scale(1.05);
    }

    .nav-section-title {
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        opacity: 0.7; /* Slightly reduce opacity for a softer feel */
    }

    .nav-link {
        color: var(--text-secondary);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        border: none;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .nav-link:hover {
        /* Even more subtle hover background */
        background-color: rgba(37, 99, 235, 0.04);
        color: var(--primary-color);
        transform: translateX(2px);
    }

    .nav-link.active {
        /* More muted and professional active link color */
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%); /* Gray tones */
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .nav-link.active:hover {
        color: white;
        transform: translateX(0);
    }

    .nav-icon {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
    }

    /* Collapsed Sidebar Styles */
    #sidebar:not(.expanded-sidebar #sidebar) .sidebar-text {
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
    }

    .expanded-sidebar #sidebar .sidebar-text {
        opacity: 1;
        transform: translateX(0);
        transition: all 0.3s ease 0.1s;
    }

    #sidebar:not(.expanded-sidebar #sidebar) .nav-section-title {
        display: none;
    }

    #sidebar:not(.expanded-sidebar #sidebar) .sidebar-brand {
        display: none;
    }

    #sidebar:not(.expanded-sidebar #sidebar) .user-info {
        display: none;
    }

    /* Badge Styles */
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
    }

    /* Responsive Sidebar */
    @media (max-width: 768px) {
        #sidebar {
            transform: translateX(-100%);
            z-index: 1051;
        }

        .expanded-sidebar #sidebar {
            transform: translateX(0);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .expanded-sidebar .sidebar-overlay {
            opacity: 1;
            visibility: visible;
        }
    }
</style>

<script>
    function toggleSidebar() {
        const html = document.documentElement;
        const isExpanded = html.classList.contains('expanded-sidebar');

        if (isExpanded) {
            html.classList.remove('expanded-sidebar');
            localStorage.setItem('sidebarExpanded', 'false');
        } else {
            html.classList.add('expanded-sidebar');
            localStorage.setItem('sidebarExpanded', 'true');
        }
    }

    // Auto-expand on hover for collapsed sidebar
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const html = document.documentElement;

        if (window.innerWidth > 768) {
            sidebar.addEventListener('mouseenter', function() {
                if (!html.classList.contains('expanded-sidebar')) {
                    html.classList.add('hover-expanded');
                }
            });

            sidebar.addEventListener('mouseleave', function() {
                html.classList.remove('hover-expanded');
            });
        }
    });

    // Mobile sidebar overlay
    if (window.innerWidth <= 768) {
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        overlay.onclick = function() {
            document.documentElement.classList.remove('expanded-sidebar');
        };
        document.body.appendChild(overlay);
    }
</script>