<div id="sidebar" class="d-flex flex-column">
    <!-- Modern Sidebar Header -->
    <div class="sidebar-header p-3 border-bottom">
        <div class="d-flex align-items-center">
            <div class="sidebar-logo me-3">
                <div class="logo-icon bg-gradient-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
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

    <!-- Modern Navigation -->
    <nav class="sidebar-nav flex-grow-1 p-3">
        <div class="nav-section mb-4">
            <div class="nav-section-title text-muted text-uppercase small fw-semibold mb-2 px-2">
                <span class="sidebar-text">Overview</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('front-desk.index') }}" class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('front-desk.*') ? 'active' : '' }}">
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
                    <a href="{{ route('reservations.index') }}" class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
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
                    <a href="{{ route('checkins.index') }}" class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('checkins.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-arrow-right-square"></i>
                        </div>
                        <span class="sidebar-text">Check-ins</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('guests.index') }}" class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('guests.*') ? 'active' : '' }}">
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
                    <a href="{{ route('rooms.index') }}" class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-house-door"></i>
                        </div>
                        <span class="sidebar-text">Rooms</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('room-types.index') }}" class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('room-types.*') ? 'active' : '' }}">
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
                    <a href="{{ route('folios.index') }}" class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('folios.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <span class="sidebar-text">Folios</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('reports.index') }}" class="nav-link d-flex align-items-center rounded-3 {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <div class="nav-icon me-3">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <span class="sidebar-text">Reports</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Modern Sidebar Footer -->
    <div class="sidebar-footer border-top p-3">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <div class="avatar me-3">
                    <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
                <div class="user-info flex-grow-1">
                    <div class="fw-semibold text-dark sidebar-text">{{ Auth::user()->name ?? 'User' }}</div>
                    <small class="text-muted sidebar-text">Administrator</small>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person me-2"></i>
                        Profile Settings
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Sign Out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
/* Modern Sidebar Styles */
#sidebar {
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    border-right: 1px solid var(--border-color);
    box-shadow: var(--shadow-lg);
}

.sidebar-header {
    background: rgba(248, 250, 252, 0.8);
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
    opacity: 0.8;
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
    background-color: rgba(37, 99, 235, 0.08);
    color: var(--primary-color);
    transform: translateX(2px);
}

.nav-link.active {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
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

.sidebar-footer {
    background: rgba(248, 250, 252, 0.8);
    backdrop-filter: blur(10px);
}

.dropdown-menu {
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    margin-top: 0.5rem;
}

.dropdown-item {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: var(--light-color);
    color: var(--primary-color);
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
