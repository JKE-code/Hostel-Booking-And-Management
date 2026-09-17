<header>
    <!-- Institutional Utility Topbar -->
    <div class="hitam-topbar">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <span><i class="bi bi-geo-alt me-1 text-accent"></i> Medchal, Hyderabad, Telangana</span>
                <span class="d-none d-md-inline text-white-50">|</span>
                <span class="d-none d-md-inline"><i class="bi bi-telephone me-1 text-accent"></i> +91 92480 09871</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('public.notices') }}" class="d-none d-sm-inline">Announcements</a>
                <a href="{{ route('public.contact') }}">Contact Us</a>
                <a href="{{ route('login') }}" class="fw-semibold text-accent ms-1"><i class="bi bi-person-fill me-1"></i>Portal Login</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg hitam-navbar sticky-top">
        <div class="container">
            <a class="hitam-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/hitam-logo.jpg') }}" alt="HITAM Logo" class="hitam-brand-logo">
                <div class="hitam-brand-text">
                    <div class="hitam-brand-title">Hostel Portal</div>
                    <div class="hitam-brand-sub">Hyderabad Institute of Technology & Management</div>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#hitamNav" aria-controls="hitamNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="hitamNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1 my-3 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link hitam-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link hitam-nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link hitam-nav-link dropdown-toggle {{ request()->routeIs('public.hostels*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Hostels
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-light">
                            <li><a class="dropdown-item py-2" href="{{ route('public.hostels.boys') }}"><i class="bi bi-building me-2 text-forest"></i>Boys Hostel</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('public.hostels.girls') }}"><i class="bi bi-building me-2 text-forest"></i>Girls Hostel</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2" href="{{ route('public.hostels.new-boys') }}"><i class="bi bi-cone-striped me-2 text-warning"></i>New Boys Hostel <span class="badge bg-warning-subtle text-warning-emphasis ms-1">Upcoming</span></a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link hitam-nav-link {{ request()->routeIs('public.facilities') ? 'active' : '' }}" href="{{ route('public.facilities') }}">Facilities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link hitam-nav-link {{ request()->routeIs('public.mess') ? 'active' : '' }}" href="{{ route('public.mess') }}">Mess & Dining</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link hitam-nav-link {{ request()->routeIs('public.rules') ? 'active' : '' }}" href="{{ route('public.rules') }}">Rules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link hitam-nav-link {{ request()->routeIs('public.notices*') ? 'active' : '' }}" href="{{ route('public.notices') }}">Notices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link hitam-nav-link {{ request()->routeIs('public.events*') ? 'active' : '' }}" href="{{ route('public.events') }}">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link hitam-nav-link {{ request()->routeIs('public.gallery') ? 'active' : '' }}" href="{{ route('public.gallery') }}">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link hitam-nav-link {{ request()->routeIs('public.downloads') ? 'active' : '' }}" href="{{ route('public.downloads') }}">Downloads</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link hitam-nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-hitam-green w-100" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
