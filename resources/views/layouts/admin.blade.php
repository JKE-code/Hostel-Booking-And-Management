<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Root Administrator — HITAM Hostel Management')</title>
    
    <link rel="icon" type="image/jpeg" href="{{ asset('images/hitam-logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    
    <style>
        :root {
            --admin-sidebar-width: 270px;
            --hitam-primary-green: #064E3B;
            --hitam-dark-spruce: #022C22;
            --hitam-emerald: #10B981;
        }
        body {
            background-color: #F4F7F5;
            font-family: 'Inter', sans-serif;
            color: #0F172A;
        }
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background: #022C22;
            color: #E2E8F0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1020;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
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
            background: rgba(255, 255, 255, 0.08);
        }
        .admin-nav-item.active {
            color: #FFFFFF;
            background: #064E3B;
            font-weight: 600;
            border-left: 3px solid #10B981;
            box-shadow: 0 4px 12px rgba(2, 44, 34, 0.3);
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
    <div class="admin-backdrop" id="adminBackdrop"></div>

    <div class="admin-layout">
        <!-- Root Admin Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="p-3 border-bottom border-white border-opacity-10 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/hitam-logo.jpg') }}" alt="HITAM" class="rounded bg-white p-1" style="height: 38px;">
                    <div>
                        <div class="fw-bold text-white small lh-sm">HITAM Hostels</div>
                        <div class="text-warning small" style="font-size: 0.725rem;"><i class="bi bi-shield-shaded me-1"></i>Root Administrator</div>
                    </div>
                </div>
                <button class="btn btn-sm btn-link text-white-50 d-lg-none p-1" id="adminCloseBtn" aria-label="Close Sidebar">
                    <i class="bi bi-x-lg fs-5"></i>
                </button>
            </div>

            <!-- Profile Badge -->
            <div class="p-3 mx-3 my-3 rounded-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #064E3B; color: #FFFFFF; border: 1px solid #10B981;">
                        RA
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-bold text-white small text-truncate">{{ Auth::user()->name ?? 'System Administrator' }}</div>
                        <div class="small" style="font-size: 0.72rem; color: #A7F3D0;"><i class="bi bi-shield-check me-1"></i>Level 1 Master</div>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-grow-1 overflow-y-auto py-2">
                <div class="px-3 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Governance</div>
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Estate Overview
                </a>
                <a href="{{ route('admin.administrators') }}" class="admin-nav-item {{ request()->routeIs('admin.administrators*') ? 'active' : '' }}">
                    <i class="bi bi-shield-shaded"></i> Administrators
                </a>
                <a href="{{ route('admin.wardens') }}" class="admin-nav-item {{ request()->routeIs('admin.wardens*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Manage Wardens
                </a>
                <a href="{{ route('admin.security') }}" class="admin-nav-item {{ request()->routeIs('admin.security*') ? 'active' : '' }}">
                    <i class="bi bi-door-closed-fill"></i> Security Staff Desk
                </a>

                <div class="px-3 pt-3 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Campus Operations</div>
                <a href="{{ route('admin.allocations') }}" class="admin-nav-item {{ request()->routeIs('admin.allocations*') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap-fill"></i> Room Allotments
                </a>
                <a href="{{ route('admin.students') }}" class="admin-nav-item {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i> Resident Scholars
                </a>
                <a href="{{ route('admin.leaves') }}" class="admin-nav-item {{ request()->routeIs('admin.leaves*') ? 'active' : '' }}">
                    <i class="bi bi-card-checklist"></i> Outpass Oversight
                </a>
                <a href="{{ route('admin.complaints') }}" class="admin-nav-item {{ request()->routeIs('admin.complaints*') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i> Maintenance Desk
                </a>

                <div class="px-3 pt-3 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Inspect Lower Tiers</div>
                <a href="{{ route('admin.inspect.warden') }}" class="admin-nav-item {{ request()->routeIs('admin.inspect.warden*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Warden Console
                </a>
                <a href="{{ route('admin.inspect.security') }}" class="admin-nav-item {{ request()->routeIs('admin.inspect.security*') ? 'active' : '' }}">
                    <i class="bi bi-camera-video"></i> Security Gate Monitor
                </a>
                <a href="{{ route('admin.inspect.student') }}" class="admin-nav-item {{ request()->routeIs('admin.inspect.student*') ? 'active' : '' }}">
                    <i class="bi bi-mortarboard"></i> Student Portal View
                </a>
            </nav>

            <!-- Sign Out Form -->
            <div class="p-3 border-top border-white border-opacity-10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 text-white">
                        <i class="bi bi-box-arrow-right me-1"></i> Sign Out Master Account
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Pane -->
        <div class="admin-content">
            <header class="admin-topbar">
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <button class="btn btn-light d-lg-none" id="adminToggle" aria-label="Toggle Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">@yield('page_title', 'Root Administrator Console')</h5>
                        <small class="text-muted d-none d-sm-inline">Campus Estate & Access Control Administration</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 small d-none d-sm-inline-flex align-items-center">
                        <i class="bi bi-shield-fill-check me-1"></i> Level 1 Root Privileges
                    </span>
                </div>
            </header>

            <main class="p-3 p-sm-4 p-lg-5 flex-grow-1">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-4 rounded-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 mb-4 rounded-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('admin_content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminBackdrop');
        const toggle = document.getElementById('adminToggle');
        const closeBtn = document.getElementById('adminCloseBtn');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        }

        if (toggle) toggle.addEventListener('click', toggleSidebar);
        if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if (backdrop) backdrop.addEventListener('click', toggleSidebar);
    </script>
    <script src="{{ asset('js/session-timeout.js') }}"></script>
</body>
</html>
