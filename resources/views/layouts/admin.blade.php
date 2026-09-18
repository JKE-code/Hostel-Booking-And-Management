<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Warden & Estate Admin Panel — HITAM Hostels')</title>
    
    <link rel="icon" type="image/jpeg" href="{{ asset('images/hitam-logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    
    <style>
        :root {
            --admin-sidebar-width: 260px;
        }
        body {
            background-color: #F1F5F9;
        }
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background: #064E3B;
            color: #E2E8F0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1020;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        .admin-content {
            margin-left: var(--admin-sidebar-width);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .admin-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 18px;
            color: #A7F3D0;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 8px;
            margin: 3px 12px;
            transition: all 0.2s ease;
        }
        .admin-nav-item:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.12);
        }
        .admin-nav-item.active {
            color: #064E3B;
            background: #FFFFFF;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .admin-topbar {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1010;
        }
        /* ── Mobile sidebar drawer (self-contained, no portal.css dependency) ── */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-content {
                margin-left: 0;
            }
        }
        /* ── Mobile bottom navigation bar ── */
        .admin-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background: #064E3B;
            border-top: 1px solid rgba(255,255,255,0.12);
            height: 60px;
            padding: 0 4px;
        }
        .admin-bottom-nav a {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #A7F3D0;
            text-decoration: none;
            font-size: 0.65rem;
            gap: 2px;
            padding: 6px 4px;
            border-radius: 8px;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .admin-bottom-nav a i {
            font-size: 1.25rem;
        }
        .admin-bottom-nav a.active,
        .admin-bottom-nav a:hover {
            color: #FFFFFF;
            background: rgba(255,255,255,0.1);
        }
        @media (max-width: 767.98px) {
            .admin-bottom-nav {
                display: flex;
            }
            /* Prevent page content from hiding behind bottom nav */
            .admin-content main {
                padding-bottom: 72px !important;
            }
        }
        /* Backdrop overlay for mobile drawer */
        .admin-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1015;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(2px);
        }
        .admin-backdrop.show {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Mobile Admin Drawer Backdrop Overlay -->
    <div class="admin-backdrop" id="adminBackdrop"></div>

    <div class="admin-layout">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="p-3 border-bottom border-white border-opacity-10 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/hitam-logo.jpg') }}" alt="HITAM" class="rounded bg-white p-1" style="height: 38px;">
                    <div>
                        <div class="fw-bold text-white small lh-sm">HITAM Hostels</div>
                        <div class="text-warning small" style="font-size: 0.725rem;"><i class="bi bi-shield-lock me-1"></i>Warden Admin</div>
                    </div>
                </div>
                <button class="btn btn-sm btn-link text-white-50 d-lg-none p-1" id="adminCloseBtn" aria-label="Close Sidebar">
                    <i class="bi bi-x-lg fs-5"></i>
                </button>
            </div>

            <!-- Admin Profile Badge -->
            <div class="p-3 mx-3 my-3 rounded-3" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white text-forest fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        CW
                    </div>
                    <div>
                        <div class="fw-bold text-white small">Prof. Ramanjaneyulu</div>
                        <div class="text-white-50" style="font-size: 0.75rem;">Chief Warden & Admin</div>
                    </div>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="flex-grow-1 overflow-y-auto py-2">
                <div class="px-3 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Operations</div>
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Overview
                </a>
                <a href="{{ route('admin.allocations') }}" class="admin-nav-item {{ request()->routeIs('admin.allocations') ? 'active' : '' }}">
                    <i class="bi bi-door-open"></i> Room Allotments
                </a>
                <a href="{{ route('admin.leaves') }}" class="admin-nav-item {{ request()->routeIs('admin.leaves') ? 'active' : '' }}">
                    <i class="bi bi-calendar2-check"></i> Outpass Approvals
                </a>
                <a href="{{ route('admin.complaints') }}" class="admin-nav-item {{ request()->routeIs('admin.complaints') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i> Maintenance Tickets
                </a>

                <div class="px-3 pt-3 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Quick Links</div>
                <a href="{{ route('student.dashboard') }}" class="admin-nav-item">
                    <i class="bi bi-person-check"></i> View Student Portal
                </a>
                <a href="{{ route('home') }}" class="admin-nav-item">
                    <i class="bi bi-globe"></i> Public Website
                </a>
            </nav>

            <div class="p-3 border-top border-white border-opacity-10">
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light w-100">
                    <i class="bi bi-box-arrow-right me-1"></i> Sign Out
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-content">
            <header class="admin-topbar">
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <button class="btn btn-light d-lg-none" id="adminToggle" aria-label="Toggle Admin Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">@yield('page_title', 'Hostel Administrative Dashboard')</h5>
                        <small class="text-muted d-none d-sm-inline">Master Management & Welfare Console</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 small d-none d-sm-inline-flex align-items-center">
                        <i class="bi bi-cone-striped me-1"></i> Admin Privileges Active
                    </span>
                </div>
            </header>

            <main class="p-3 p-sm-4 p-lg-5 flex-grow-1">
                @yield('admin_content')
            </main>
        </div>
    </div>
    <!-- Mobile Admin Bottom Navigation -->
    <nav class="admin-bottom-nav" aria-label="Admin mobile navigation">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Overview</span>
        </a>
        <a href="{{ route('admin.allocations') }}" class="{{ request()->routeIs('admin.allocations') ? 'active' : '' }}">
            <i class="bi bi-door-open"></i>
            <span>Rooms</span>
        </a>
        <a href="{{ route('admin.leaves') }}" class="{{ request()->routeIs('admin.leaves') ? 'active' : '' }}">
            <i class="bi bi-calendar2-check"></i>
            <span>Outpass</span>
        </a>
        <a href="{{ route('admin.complaints') }}" class="{{ request()->routeIs('admin.complaints') ? 'active' : '' }}">
            <i class="bi bi-tools"></i>
            <span>Tickets</span>
        </a>
        <a href="{{ route('home') }}">
            <i class="bi bi-globe"></i>
            <span>Website</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminBackdrop');
            const toggleBtn = document.getElementById('adminToggle');
            const closeBtn = document.getElementById('adminCloseBtn');

            function openSidebar() {
                sidebar?.classList.add('show');
                backdrop?.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar?.classList.remove('show');
                backdrop?.classList.remove('show');
                document.body.style.overflow = '';
            }

            toggleBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            backdrop?.addEventListener('click', closeSidebar);
        });
    </script>
    @stack('scripts')
</body>
</html>
