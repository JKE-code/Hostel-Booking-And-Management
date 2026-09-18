<header>
    <!-- Institutional Utility Topbar -->
    <div class="hitam-topbar">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <a href="https://maps.google.com/?q=Hyderabad+Institute+of+Technology+and+Management+HITAM+Gowdavelly+Medchal+Hyderabad+Telangana+501401" target="_blank" rel="noopener noreferrer" class="topbar-item-link" title="Open HITAM Campus in Google Maps">
                    <i class="bi bi-geo-alt-fill text-accent"></i>
                    <span>Medchal, Hyderabad, Telangana</span>
                    <i class="bi bi-box-arrow-up-right ms-1 opacity-75" style="font-size: 0.7rem;"></i>
                </a>
                <span class="d-none d-md-inline text-white-50 opacity-50">|</span>
                <a href="tel:+919248009871" class="d-none d-md-inline topbar-item-link" title="Call Helpline">
                    <i class="bi bi-telephone-fill text-accent"></i>
                    <span>+91 92480 09871</span>
                </a>
            </div>
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <a href="{{ route('public.notices') }}" class="d-none d-sm-inline topbar-item-link">
                    <i class="bi bi-megaphone-fill text-accent"></i>
                    <span>Announcements</span>
                </a>
                <a href="{{ route('public.contact') }}" class="topbar-item-link">
                    <i class="bi bi-envelope-fill text-accent"></i>
                    <span>Contact</span>
                </a>
                <a href="{{ route('login') }}" class="topbar-pill ms-1">
                    <i class="bi bi-person-fill"></i>
                    <span>Portal Login</span>
                </a>
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
                    <div class="hitam-brand-sub d-none d-sm-block">Hyderabad Institute of Technology & Management</div>
                    <div class="hitam-brand-sub d-sm-none">HITAM Hyderabad</div>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#hitamNav" aria-controls="hitamNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="hitamNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2 my-3 my-lg-0">
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
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2 p-2" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(12px); border: 1px solid rgba(226, 232, 240, 0.8) !important;">
                            <li><a class="dropdown-item py-2 rounded-2 fw-semibold" href="{{ route('public.hostels.boys') }}"><i class="bi bi-building me-2 text-forest"></i>Boys Hostel</a></li>
                            <li><a class="dropdown-item py-2 rounded-2 fw-semibold" href="{{ route('public.hostels.girls') }}"><i class="bi bi-building me-2 text-forest"></i>Girls Hostel</a></li>
                            <li><hr class="dropdown-divider opacity-10"></li>
                            <li><a class="dropdown-item py-2 rounded-2 fw-semibold" href="{{ route('public.hostels.new-boys') }}"><i class="bi bi-cone-striped me-2 text-warning"></i>New Boys Hostel <span class="badge bg-warning-subtle text-warning-emphasis ms-1">Upcoming</span></a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link hitam-nav-link dropdown-toggle {{ request()->routeIs('public.facilities', 'public.mess', 'public.events', 'public.gallery') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Campus Life
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2 p-2" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(12px); border: 1px solid rgba(226, 232, 240, 0.8) !important;">
                            <li><a class="dropdown-item py-2 rounded-2 fw-semibold" href="{{ route('public.facilities') }}"><i class="bi bi-grid me-2 text-forest"></i>Facilities & Amenities</a></li>
                            <li><a class="dropdown-item py-2 rounded-2 fw-semibold" href="{{ route('public.mess') }}"><i class="bi bi-cup-hot me-2 text-forest"></i>Mess & Dining</a></li>
                            <li><a class="dropdown-item py-2 rounded-2 fw-semibold" href="{{ route('public.events') }}"><i class="bi bi-calendar-event me-2 text-forest"></i>Events & Activities</a></li>
                            <li><a class="dropdown-item py-2 rounded-2 fw-semibold" href="{{ route('public.gallery') }}"><i class="bi bi-images me-2 text-forest"></i>Photo Gallery</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link hitam-nav-link dropdown-toggle {{ request()->routeIs('public.notices*', 'public.rules', 'public.downloads', 'public.faq') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Information
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2 p-2" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(12px); border: 1px solid rgba(226, 232, 240, 0.8) !important;">
                            <li><a class="dropdown-item py-2 rounded-2 fw-semibold" href="{{ route('public.notices') }}"><i class="bi bi-bell me-2 text-forest"></i>Warden Notices</a></li>
                            <li><a class="dropdown-item py-2 rounded-2 fw-semibold" href="{{ route('public.rules') }}"><i class="bi bi-book me-2 text-forest"></i>Code of Conduct & Rules</a></li>
                            <li><a class="dropdown-item py-2 rounded-2 fw-semibold" href="{{ route('public.downloads') }}"><i class="bi bi-file-earmark-arrow-down me-2 text-forest"></i>Application Downloads</a></li>
                            <li><a class="dropdown-item py-2 rounded-2 fw-semibold" href="{{ route('public.faq') }}"><i class="bi bi-question-circle me-2 text-forest"></i>Residential FAQ</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link hitam-nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-hitam-nav-btn" href="{{ route('login') }}">
                            <span>Sign In</span>
                            <i class="bi bi-box-arrow-in-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
