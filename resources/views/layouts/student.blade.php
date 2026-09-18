<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Portal — HITAM Hostels')</title>
    
    <link rel="icon" type="image/jpeg" href="{{ asset('images/hitam-logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    
    <style>
        :root {
            --portal-sidebar-width: 260px;
        }
        body {
            background-color: #F8FAFC;
        }
        .portal-layout {
            display: flex;
            min-height: 100vh;
        }
        .portal-sidebar {
            width: var(--portal-sidebar-width);
            background: #021B0F;
            color: #E2E8F0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1020;
            border-right: 1px solid rgba(16, 185, 129, 0.15);
        }
        .portal-content {
            margin-left: var(--portal-sidebar-width);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .portal-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 18px;
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 8px;
            margin: 3px 12px;
            transition: all 0.2s ease;
        }
        .portal-nav-item:hover {
            color: #FFFFFF;
            background: rgba(16, 185, 129, 0.12);
        }
        .portal-nav-item.active {
            color: #FFFFFF;
            background: linear-gradient(135deg, #10B981 0%, #047857 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
            font-weight: 600;
        }
        .portal-nav-item i {
            font-size: 1.1rem;
        }
        .portal-topbar {
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
            .portal-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .portal-sidebar.show {
                transform: translateX(0);
            }
            .portal-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Drawer Backdrop Overlay -->
    <div class="portal-backdrop" id="portalBackdrop"></div>

    <div class="portal-layout">
        <!-- Sidebar Navigation -->
        <aside class="portal-sidebar" id="portalSidebar">
            <!-- Sidebar Header -->
            <div class="p-3 border-bottom border-white border-opacity-10 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/hitam-logo.jpg') }}" alt="HITAM" class="rounded bg-white p-1" style="height: 38px;">
                    <div>
                        <div class="fw-bold text-white small lh-sm">HITAM Hostels</div>
                        <div class="text-success small" style="font-size: 0.725rem;"><i class="bi bi-circle-fill me-1" style="font-size: 0.55rem;"></i>Resident Portal</div>
                    </div>
                </div>
                <button class="btn btn-sm btn-link text-white-50 d-lg-none p-1" id="sidebarCloseBtn" aria-label="Close Sidebar">
                    <i class="bi bi-x-lg fs-5"></i>
                </button>
            </div>

            <!-- Student Mini Badge -->
            <div class="p-3 mx-3 my-3 rounded-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                        RS
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-bold text-white text-truncate small">Rahul Sharma</div>
                        <div class="text-white-50 small" style="font-size: 0.75rem;">Boys Hostel • B-204</div>
                    </div>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="flex-grow-1 overflow-y-auto py-2">
                <div class="px-3 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Core Services</div>
                <a href="{{ route('student.dashboard') }}" class="portal-nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('student.room') }}" class="portal-nav-item {{ request()->routeIs('student.room') ? 'active' : '' }}">
                    <i class="bi bi-door-open"></i> My Room & Bed
                </a>
                <a href="{{ route('student.leaves') }}" class="portal-nav-item {{ request()->routeIs('student.leaves') ? 'active' : '' }}">
                    <i class="bi bi-calendar2-check"></i> Leave & Outpass
                </a>
                <a href="{{ route('student.complaints') }}" class="portal-nav-item {{ request()->routeIs('student.complaints') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i> Maintenance Tickets
                </a>
                <a href="{{ route('student.mess') }}" class="portal-nav-item {{ request()->routeIs('student.mess') ? 'active' : '' }}">
                    <i class="bi bi-cup-hot"></i> Today's Mess
                </a>

                <div class="px-3 pt-3 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Account</div>
                <a href="{{ route('student.profile') }}" class="portal-nav-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i> Resident Profile
                </a>
                <a href="{{ route('home') }}" class="portal-nav-item">
                    <i class="bi bi-globe"></i> Public Website
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-3 border-top border-white border-opacity-10">
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </a>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="portal-content">
            <!-- Topbar -->
            <header class="portal-topbar">
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <button class="btn btn-light d-lg-none" id="sidebarToggle" aria-label="Toggle Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">@yield('page_title', 'Student Resident Dashboard')</h5>
                        <small class="text-muted d-none d-sm-inline">Academic Year 2026-27 • Semester I</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small d-none d-sm-inline-flex align-items-center">
                        <i class="bi bi-shield-check me-1"></i> Biometric Verified
                    </span>
                    <a href="{{ route('public.notices') }}" class="btn btn-light rounded-circle p-2 position-relative text-muted" title="Circulars">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </a>
                </div>
            </header>

            <!-- Body Content -->
            <main class="p-3 p-sm-4 p-lg-5 flex-grow-1">
                @yield('student_content')
            </main>
        </div>
    </div>

    <!-- Mobile Bottom Navigation Bar (Visible only on mobile devices) -->
    <nav class="portal-bottom-nav" id="studentBottomNav">
        <a href="{{ route('student.dashboard') }}" class="portal-bottom-nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('student.room') }}" class="portal-bottom-nav-item {{ request()->routeIs('student.room') ? 'active' : '' }}">
            <i class="bi bi-door-open"></i>
            <span>Room</span>
        </a>
        <a href="{{ route('student.leaves') }}" class="portal-bottom-nav-item {{ request()->routeIs('student.leaves') ? 'active' : '' }}">
            <i class="bi bi-calendar2-check"></i>
            <span>Outpass</span>
        </a>
        <a href="{{ route('student.complaints') }}" class="portal-bottom-nav-item {{ request()->routeIs('student.complaints') ? 'active' : '' }}">
            <i class="bi bi-tools"></i>
            <span>Tickets</span>
        </a>
        <a href="{{ route('student.profile') }}" class="portal-bottom-nav-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span>Profile</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('portalSidebar');
            const backdrop = document.getElementById('portalBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('sidebarCloseBtn');

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
