<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Main Gate Security Monitor — HITAM Hostels')</title>
    
    <link rel="icon" type="image/jpeg" href="{{ asset('images/hitam-logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --sec-sidebar-width: 270px;
        }
        body {
            background-color: #0F172A;
            color: #F1F5F9;
            font-family: 'Inter', sans-serif;
        }
        .sec-layout {
            display: flex;
            min-height: 100vh;
        }
        .sec-sidebar {
            width: var(--sec-sidebar-width);
            background: #1E293B;
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
        .sec-content {
            margin-left: var(--sec-sidebar-width);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: #0B1120;
        }
        .sec-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 500;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.2s ease;
        }
        .sec-nav-item:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.06);
        }
        .sec-nav-item.active {
            color: #0B1120;
            background: #10B981;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        }
        .sec-topbar {
            background: #1E293B;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1010;
        }
        @media (max-width: 991.98px) {
            .sec-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sec-sidebar.show {
                transform: translateX(0);
            }
            .sec-content {
                margin-left: 0;
            }
        }
        .sec-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1015;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(2px);
        }
        .sec-backdrop.show {
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
    <div class="sec-backdrop" id="secBackdrop"></div>

    <div class="sec-layout">
        <!-- Sidebar -->
        <aside class="sec-sidebar" id="secSidebar">
            <div class="p-3 border-bottom border-white border-opacity-10 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/hitam-logo.jpg') }}" alt="HITAM" class="rounded bg-white p-1" style="height: 38px;">
                    <div>
                        <div class="fw-bold text-white small lh-sm">HITAM Security</div>
                        <div class="text-success small fw-semibold" style="font-size: 0.725rem;"><i class="bi bi-shield-check me-1"></i>Main Gate Control</div>
                    </div>
                </div>
                <button class="btn btn-sm btn-link text-white-50 d-lg-none p-1" id="secCloseBtn" aria-label="Close Sidebar">
                    <i class="bi bi-x-lg fs-5"></i>
                </button>
            </div>

            <!-- Gate Officer Badge -->
            <div class="p-3 mx-3 my-3 rounded-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-emerald text-white fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #10B981;">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-bold text-white small text-truncate">{{ Auth::user()->name ?? 'Main Gate Desk' }}</div>
                        <div class="text-white-50" style="font-size: 0.72rem;">Gate Officer • Level 3</div>
                    </div>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-grow-1 overflow-y-auto py-2">
                <div class="px-3 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Live Gate Desk</div>
                <a href="{{ route('security.dashboard') }}" class="sec-nav-item {{ request()->routeIs('security.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-camera-video-fill"></i> Gate Monitor & Scanner
                </a>
                <a href="{{ route('security.visitors') }}" class="sec-nav-item {{ request()->routeIs('security.visitors*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Daily Visitors Log
                </a>

                <div class="px-3 pt-3 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Quick Links</div>
                <a href="{{ route('home') }}" class="sec-nav-item">
                    <i class="bi bi-globe"></i> Public Website
                </a>
            </nav>

            <div class="p-3 border-top border-white border-opacity-10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 text-white">
                        <i class="bi bi-box-arrow-right me-1"></i> Sign Out Gate Desk
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="sec-content">
            <header class="sec-topbar">
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <button class="btn btn-dark d-lg-none" id="secToggle" aria-label="Toggle Menu">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">@yield('page_title', 'Main Gate Control Monitor')</h5>
                        <small class="text-white-50 d-none d-sm-inline">Campus Entry/Exit Outpass Verification & Logging</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 small d-inline-flex align-items-center">
                        <span class="spinner-grow spinner-grow-sm me-2 text-success" role="status"></span>
                        Gate Station Online
                    </span>
                </div>
            </header>

            <main class="p-3 p-sm-4 p-lg-5 flex-grow-1">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-4 rounded-3 border-0 text-dark" style="background: #A7F3D0;" role="alert">
                        <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 mb-4 rounded-3 border-0 text-white" style="background: #EF4444;" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('security_content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('secSidebar');
        const backdrop = document.getElementById('secBackdrop');
        const toggle = document.getElementById('secToggle');
        const closeBtn = document.getElementById('secCloseBtn');

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
