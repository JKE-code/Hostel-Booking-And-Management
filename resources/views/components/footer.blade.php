<footer class="hitam-footer">
    <div class="container">
        <div class="row g-4">
            <!-- Brand Overview -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ asset('images/hitam-logo.jpg') }}" alt="HITAM Logo" class="hitam-brand-logo-footer">
                    <div>
                        <div class="text-white fw-bold fs-5 lh-sm">HITAM Hostels</div>
                        <div class="small" style="color: var(--hitam-primary-light);">Residential Life Administration</div>
                    </div>
                </div>
                <p class="small pe-lg-4 mb-3" style="color: #C8DEC9 !important;">
                    Providing a safe, supportive, and disciplined residential living experience for scholars of Hyderabad Institute of Technology and Management.
                </p>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge-footer-pill">
                        <i class="bi bi-patch-check-fill me-1 text-success"></i> Affiliated to JNTUH
                    </span>
                    <span class="badge-footer-pill">
                        <i class="bi bi-award-fill me-1 text-warning"></i> NAAC 'A+' Grade
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <a href="https://www.linkedin.com/school/hitam-hyderabad/" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="https://www.youtube.com/@HITAMHyderabad" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="YouTube">
                        <i class="bi bi-youtube"></i>
                    </a>
                    <a href="https://www.facebook.com/HITAMHyderabad/" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/hitamhyderabad/" target="_blank" rel="noopener noreferrer" class="footer-social-btn" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Navigation -->
            <div class="col-6 col-lg-2">
                <div class="hitam-footer-title">Navigation</div>
                <ul class="hitam-footer-links">
                    <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right small text-success"></i> Home</a></li>
                    <li><a href="{{ route('public.about') }}"><i class="bi bi-chevron-right small text-success"></i> About Hostels</a></li>
                    <li><a href="{{ route('public.facilities') }}"><i class="bi bi-chevron-right small text-success"></i> Facilities</a></li>
                    <li><a href="{{ route('public.mess') }}"><i class="bi bi-chevron-right small text-success"></i> Mess Menu</a></li>
                    <li><a href="{{ route('public.rules') }}"><i class="bi bi-chevron-right small text-success"></i> Code of Conduct</a></li>
                </ul>
            </div>

            <!-- Hostels & Resources -->
            <div class="col-6 col-lg-2">
                <div class="hitam-footer-title">Residences</div>
                <ul class="hitam-footer-links">
                    <li><a href="{{ route('public.hostels.boys') }}"><i class="bi bi-chevron-right small text-success"></i> Boys Hostel</a></li>
                    <li><a href="{{ route('public.hostels.girls') }}"><i class="bi bi-chevron-right small text-success"></i> Girls Hostel</a></li>
                    <li><a href="{{ route('public.hostels.new-boys') }}"><i class="bi bi-chevron-right small text-success"></i> New Boys Hostel</a></li>
                    <li><a href="{{ route('public.downloads') }}"><i class="bi bi-chevron-right small text-success"></i> Application Forms</a></li>
                    <li><a href="{{ route('public.faq') }}"><i class="bi bi-chevron-right small text-success"></i> Residential FAQ</a></li>
                </ul>
            </div>

            <!-- Contact & Helpdesk -->
            <div class="col-lg-4">
                <div class="hitam-footer-title">Contact & Helpdesk</div>
                
                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <div class="small text-white-50 fw-semibold mb-1">Campus Location</div>
                        <a href="https://maps.google.com/?q=Hyderabad+Institute+of+Technology+and+Management+Gowdavelly+Medchal+Hyderabad+Telangana+501401" target="_blank" rel="noopener noreferrer" class="footer-contact-link fw-semibold">
                            HITAM Campus, Gowdavelly, Medchal, Hyderabad, Telangana - 501401
                            <i class="bi bi-box-arrow-up-right small ms-1 text-success"></i>
                        </a>
                    </div>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div>
                        <div class="small text-white-50 fw-semibold mb-1">Helpline Phone</div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="tel:+919248009871" class="footer-contact-link">+91 92480 09871</a>
                            <span class="text-white-50">&bull;</span>
                            <a href="tel:08418204066" class="footer-contact-link">08418-204066</a>
                        </div>
                    </div>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <div class="small text-white-50 fw-semibold mb-1">Email Support</div>
                        <a href="mailto:hosteladmin@hitam.org" class="footer-contact-link">hosteladmin@hitam.org</a>
                    </div>
                </div>

                <div class="footer-contact-item mb-0">
                    <div class="footer-contact-icon">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div>
                        <div class="small text-white-50 fw-semibold mb-1">Office Working Hours</div>
                        <span class="text-white-50">08:30 AM - 05:30 PM (Mon - Sat)</span>
                    </div>
                </div>
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

