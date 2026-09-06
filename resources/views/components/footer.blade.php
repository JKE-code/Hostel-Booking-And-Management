<footer class="hitam-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ asset('images/hitam-logo.jpg') }}" alt="HITAM Logo" class="hitam-brand-logo-footer">
                    <div>
                        <div class="text-white fw-bold fs-5 lh-sm">HITAM Hostels</div>
                        <div class="small" style="color: var(--hitam-primary-light);">Residential Life Administration</div>
                    </div>
                </div>
                <p class="small pe-lg-4" style="color: #C8DEC9 !important;">
                    Providing a safe, supportive, and disciplined residential living experience for students of Hyderabad Institute of Technology and Management.
                </p>
                <div class="d-flex gap-3 mt-3 text-white-50">
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Affiliated to JNTUH</span>
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">NAAC 'A+' Grade</span>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <div class="hitam-footer-title">Navigation</div>
                <ul class="hitam-footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('public.about') }}">About Hostels</a></li>
                    <li><a href="{{ route('public.facilities') }}">Facilities</a></li>
                    <li><a href="{{ route('public.mess') }}">Mess Menu</a></li>
                    <li><a href="{{ route('public.rules') }}">Code of Conduct</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <div class="hitam-footer-title">Hostels</div>
                <ul class="hitam-footer-links">
                    <li><a href="{{ route('public.hostels.boys') }}">Boys Hostel</a></li>
                    <li><a href="{{ route('public.hostels.girls') }}">Girls Hostel</a></li>
                    <li><a href="{{ route('public.hostels.new-boys') }}">New Boys Hostel</a></li>
                    <li><a href="{{ route('public.downloads') }}">Application Forms</a></li>
                    <li><a href="{{ route('public.faq') }}">Residential FAQ</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <div class="hitam-footer-title">Contact & Helpdesk</div>
                <ul class="hitam-footer-links">
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-geo-alt text-warning mt-1"></i>
                        <span>HITAM Campus, Gowdavelly, Medchal, Hyderabad, Telangana - 501401</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-telephone text-warning"></i>
                        <span>+91 92480 09871, 08418-204066</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope text-warning"></i>
                        <span>hosteladmin@hitam.org</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-clock text-warning"></i>
                        <span>Office Hours: 08:30 AM - 05:30 PM (Mon - Sat)</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="hitam-footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>
                &copy; {{ date('Y') }} Hyderabad Institute of Technology and Management (HITAM). All rights reserved.
            </div>
            <div class="d-flex gap-3 text-white-50">
                <a href="{{ route('public.rules') }}" class="text-white-50 text-decoration-none">Rules & Policy</a>
                <span>&bull;</span>
                <a href="{{ route('login') }}" class="text-white-50 text-decoration-none">Admin & Student Login</a>
            </div>
        </div>
    </div>
</footer>
