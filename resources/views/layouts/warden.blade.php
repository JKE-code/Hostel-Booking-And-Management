<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Warden Operations Console — HITAM Hostels')</title>
    
    <link rel="icon" type="image/jpeg" href="{{ asset('images/hitam-logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    
    <style>
        :root {
            --warden-sidebar-width: 270px;
        }
        body {
            background-color: #F1F5F9;
            font-family: 'Inter', sans-serif;
        }
        .warden-layout {
            display: flex;
            min-height: 100vh;
        }
        .warden-sidebar {
            width: var(--warden-sidebar-width);
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
        .warden-content {
            margin-left: var(--warden-sidebar-width);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .warden-nav-item {
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
        .warden-nav-item:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.12);
        }
        .warden-nav-item.active {
            color: #064E3B;
            background: #FFFFFF;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .warden-topbar {
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
            .warden-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .warden-sidebar.show {
                transform: translateX(0);
            }
            .warden-content {
                margin-left: 0;
            }
        }
        .warden-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1015;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(2px);
        }
        .warden-backdrop.show {
            display: block;
        }
    </style>
</head>
<body>
    @if(Auth::check() && Auth::user()->role === 'admin')
        <div class="bg-warning text-dark py-1 px-3 d-flex justify-content-between align-items-center small fw-semibold" style="position: sticky; top: 0; z-index: 1050;">
            <div><i class="bi bi-shield-shaded me-1"></i>Super Administrator Inspection Mode (Level 1 Master)</div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-dark py-0" style="font-size: 0.75rem;">Return to Estate Overview &rarr;</a>
        </div>
    @endif
    <div class="warden-backdrop" id="wardenBackdrop"></div>

    <div class="warden-layout">
        <!-- Warden Sidebar -->
        <aside class="warden-sidebar" id="wardenSidebar">
            <div class="p-3 border-bottom border-white border-opacity-10 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/hitam-logo.jpg') }}" alt="HITAM" class="rounded bg-white p-1" style="height: 38px;">
                    <div>
                        <div class="fw-bold text-white small lh-sm">HITAM Hostels</div>
                        <div class="text-warning small" style="font-size: 0.725rem;"><i class="bi bi-building-check me-1"></i>Hostel Warden Desk</div>
                    </div>
                </div>
                <button class="btn btn-sm btn-link text-white-50 d-lg-none p-1" id="wardenCloseBtn" aria-label="Close Sidebar">
                    <i class="bi bi-x-lg fs-5"></i>
                </button>
            </div>

            <!-- Warden Profile Card -->
            <div class="p-3 mx-3 my-3 rounded-3" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-white text-forest fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        HW
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-bold text-white small text-truncate">{{ Auth::user()->name ?? 'Hostel Warden' }}</div>
                        <div class="text-white-50" style="font-size: 0.75rem;">Resident Warden • Level 2</div>
                    </div>
                </div>
            </div>

            <!-- Operations Nav -->
            <nav class="flex-grow-1 overflow-y-auto py-2">
                <div class="px-3 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Hostel Operations</div>
                <a href="{{ route('warden.dashboard') }}" class="warden-nav-item {{ request()->routeIs('warden.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('warden.students') }}" class="warden-nav-item {{ request()->routeIs('warden.students*') ? 'active' : '' }}">
                    <i class="bi bi-person-check-fill"></i> Student Whitelisting
                </a>
                <a href="{{ route('warden.outpasses') }}" class="warden-nav-item {{ request()->routeIs('warden.outpasses*') ? 'active' : '' }}">
                    <i class="bi bi-calendar2-check"></i> Outpass Approvals
                </a>
                <a href="{{ route('warden.complaints') }}" class="warden-nav-item {{ request()->routeIs('warden.complaints*') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i> Complaints Desk
                </a>
                <a href="{{ route('warden.allocations') }}" class="warden-nav-item {{ request()->routeIs('warden.allocations*') ? 'active' : '' }}">
                    <i class="bi bi-door-open"></i> Bed Allocations
                </a>

                <div class="px-3 pt-3 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Quick Links</div>
                <a href="{{ route('security.dashboard') }}" class="warden-nav-item">
                    <i class="bi bi-camera-video"></i> Security Gate Monitor
                </a>
                <a href="{{ route('student.dashboard') }}" class="warden-nav-item">
                    <i class="bi bi-mortarboard"></i> Student Portal View
                </a>
                <a href="{{ route('home') }}" class="warden-nav-item">
                    <i class="bi bi-globe"></i> Public Website
                </a>
            </nav>

            <div class="p-3 border-top border-white border-opacity-10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light w-100">
                        <i class="bi bi-box-arrow-right me-1"></i> Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Pane -->
        <div class="warden-content">
            <header class="warden-topbar">
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <button class="btn btn-light d-lg-none" id="wardenToggle" aria-label="Toggle Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">@yield('page_title', 'Hostel Warden Operations Console')</h5>
                        <small class="text-muted d-none d-sm-inline">Resident Welfare & Approvals Desk</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 small d-none d-sm-inline-flex align-items-center">
                        <i class="bi bi-person-badge me-1"></i> Level 2 Warden Operations
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

                @yield('warden_content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('wardenSidebar');
        const backdrop = document.getElementById('wardenBackdrop');
        const toggle = document.getElementById('wardenToggle');
        const closeBtn = document.getElementById('wardenCloseBtn');

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
