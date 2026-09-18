@extends('layouts.public')

@section('title', 'HITAM Hostel Portal — Residential Accommodations & Student Living')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-tag">
                    <span class="status-dot-pulse"></span>
                    <span>Official Residential Life Portal</span>
                </div>
                <h1 class="hero-title">
                    Safe, Disciplined & Modern Hostel Living at HITAM
                </h1>
                <p class="hero-subtitle">
                    Fostering an enriching collegiate living environment with dedicated residential blocks, hygienic dining, high-speed connectivity, and 24/7 security.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('login') }}" class="btn btn-hitam-accent d-inline-flex align-items-center gap-2">
                        <span>Resident Login</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('public.hostels.boys') }}" class="btn btn-hitam-outline-light">
                        Explore Hostels
                    </a>
                </div>

                <!-- Key Quick Metrics -->
                <div class="row mt-5 pt-3 g-3 text-start">
                    <div class="col-4">
                        <div class="hero-stat-card">
                            <div class="h3 fw-bold text-white mb-0">2+1</div>
                            <div class="small" style="color: var(--hitam-primary-light);">Hostel Blocks</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="hero-stat-card">
                            <div class="h3 fw-bold text-white mb-0">24/7</div>
                            <div class="small" style="color: var(--hitam-primary-light);">CCTV & Security</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="hero-stat-card">
                            <div class="h3 fw-bold text-white mb-0">100%</div>
                            <div class="small" style="color: var(--hitam-primary-light);">Purified RO Water</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mt-4 mt-lg-0">
                <!-- Resident Portal Quick Access Card -->
                <div class="card border-0 shadow-lg hero-floating-card" style="background: rgba(13, 40, 24, 0.45); backdrop-filter: blur(12px); border: 1px solid rgba(165, 214, 167, 0.25) !important; border-radius: 12px;">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge px-2 py-1 fw-bold" style="background-color: var(--hitam-primary-accent); color: #0D2818;">QUICK ACCESS</span>
                            <span class="small" style="color: var(--hitam-primary-light);">Internal Services</span>
                        </div>
                        <h4 class="fw-bold mb-2">Student & Staff Portal</h4>
                        <p class="small mb-4" style="color: #D1E7D3;">
                            Access your room details, submit leave applications, raise maintenance complaints, and view warden notices.
                        </p>
                        
                        <div class="list-group list-group-flush bg-transparent mb-4">
                            <a href="{{ route('login') }}" class="list-group-item list-group-item-action bg-transparent text-white border-white border-opacity-10 px-0 py-2 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-door-open me-2" style="color: var(--hitam-primary-accent);"></i>Room Allocation Details</span>
                                <i class="bi bi-chevron-right small opacity-75"></i>
                            </a>
                            <a href="{{ route('login') }}" class="list-group-item list-group-item-action bg-transparent text-white border-white border-opacity-10 px-0 py-2 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-calendar2-check me-2" style="color: var(--hitam-primary-accent);"></i>Leave & Outing Requests</span>
                                <i class="bi bi-chevron-right small opacity-75"></i>
                            </a>
                            <a href="{{ route('login') }}" class="list-group-item list-group-item-action bg-transparent text-white border-white border-opacity-10 px-0 py-2 d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-tools me-2" style="color: var(--hitam-primary-accent);"></i>Maintenance & Complaints</span>
                                <i class="bi bi-chevron-right small opacity-75"></i>
                            </a>
                        </div>

                        <a href="{{ route('login') }}" class="btn btn-light fw-bold w-100 py-2 text-forest">
                            Sign In to Portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Residential Hostels Overview Section -->
<section class="py-5">
    <div class="container py-4">
        <div class="row justify-content-between align-items-end mb-4">
            <div class="col-lg-7">
                <span class="hitam-section-badge">Accommodations</span>
                <h2 class="hitam-section-title">Hostel Residences</h2>
                <p class="hitam-section-sub">
                    HITAM provides designated residential facilities designed to support focused academic growth, personal wellbeing, and active student life.
                </p>
            </div>
            <div class="col-lg-3 text-lg-end mt-2 mt-lg-0">
                <a href="{{ route('public.about') }}" class="btn btn-hitam-outline-green">View Guidelines</a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Boys Hostel -->
            <div class="col-md-4">
                <div class="card hitam-card h-100 card-accent-top">
                    <img src="{{ asset('images/hostel-building.jpg') }}" class="card-img-top" alt="Boys Hostel" style="height: 180px; object-fit: cover;">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge-status badge-active">
                                <i class="bi bi-record-fill text-forest"></i> Active
                            </span>
                            <span class="text-muted small fw-semibold"><i class="bi bi-building me-1"></i>Block A & B</span>
                        </div>
                        <h4 class="fw-bold mb-2 text-forest">Boys Hostel</h4>
                        <p class="text-secondary small mb-4 flex-grow-1">
                            Equipped with ventilated double and triple-sharing rooms, study desks, wardrobe storage, high-speed Wi-Fi, and recreational spaces.
                        </p>
                        <ul class="list-unstyled small text-secondary mb-4">
                            <li class="mb-2"><i class="bi bi-check2 text-forest me-2 fw-bold"></i>Resident Warden & Supervisor</li>
                            <li class="mb-2"><i class="bi bi-check2 text-forest me-2 fw-bold"></i>Solar Water Heating System</li>
                            <li class="mb-2"><i class="bi bi-check2 text-forest me-2 fw-bold"></i>Gymnasium & Indoor Games</li>
                        </ul>
                        <a href="{{ route('public.hostels.boys') }}" class="btn btn-hitam-outline-green w-100 fw-semibold text-start d-flex justify-content-between align-items-center">
                            <span>Hostel Details & Layout</span>
                            <i class="bi bi-arrow-right text-forest"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Girls Hostel -->
            <div class="col-md-4">
                <div class="card hitam-card h-100 card-accent-top">
                    <img src="{{ asset('images/student-room.jpg') }}" class="card-img-top" alt="Girls Hostel" style="height: 180px; object-fit: cover;">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge-status badge-active">
                                <i class="bi bi-record-fill text-forest"></i> Active
                            </span>
                            <span class="text-muted small fw-semibold"><i class="bi bi-shield-lock me-1"></i>High Security</span>
                        </div>
                        <h4 class="fw-bold mb-2 text-forest">Girls Hostel</h4>
                        <p class="text-secondary small mb-4 flex-grow-1">
                            A secure, comfortable residential living complex with dedicated biometric access, warden supervision, and quiet study zones.
                        </p>
                        <ul class="list-unstyled small text-secondary mb-4">
                            <li class="mb-2"><i class="bi bi-check2 text-forest me-2 fw-bold"></i>Dedicated Female Wardens & Staff</li>
                            <li class="mb-2"><i class="bi bi-check2 text-forest me-2 fw-bold"></i>24/7 Monitored Campus Perimeter</li>
                            <li class="mb-2"><i class="bi bi-check2 text-forest me-2 fw-bold"></i>Attached Dining Facility</li>
                        </ul>
                        <a href="{{ route('public.hostels.girls') }}" class="btn btn-hitam-outline-green w-100 fw-semibold text-start d-flex justify-content-between align-items-center">
                            <span>Hostel Details & Layout</span>
                            <i class="bi bi-arrow-right text-forest"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- New Boys Hostel -->
            <div class="col-md-4">
                <div class="card hitam-card h-100 card-accent-top opacity-85">
                    <img src="{{ asset('images/dining-hall.jpg') }}" class="card-img-top" alt="New Boys Hostel" style="height: 180px; object-fit: cover; filter: grayscale(30%);">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge-status badge-coming-soon">
                                <i class="bi bi-hourglass-split text-warning"></i> Coming Soon
                            </span>
                            <span class="text-muted small fw-semibold"><i class="bi bi-cone-striped me-1"></i>Phase 2</span>
                        </div>
                        <h4 class="fw-bold mb-2 text-forest">New Boys Hostel</h4>
                        <p class="text-secondary small mb-4 flex-grow-1">
                            A modern expansion designed with upgraded room layouts, enhanced common spaces, and sustainable eco-friendly architecture.
                        </p>
                        <ul class="list-unstyled small text-secondary mb-4">
                            <li class="mb-2"><i class="bi bi-check2 text-muted me-2"></i>Expanded Capacity & Amenities</li>
                            <li class="mb-2"><i class="bi bi-check2 text-muted me-2"></i>Modern Study Lounges</li>
                            <li class="mb-2"><i class="bi bi-check2 text-muted me-2"></i>Dynamic DB-Driven Activation</li>
                        </ul>
                        <a href="{{ route('public.hostels.new-boys') }}" class="btn btn-outline-secondary w-100 fw-semibold text-start d-flex justify-content-between align-items-center disabled">
                            <span>Under Construction</span>
                            <i class="bi bi-clock text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Facilities & Amenities Section -->
<section class="py-5 bg-white border-top border-bottom" style="border-color: var(--hitam-border) !important;">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="hitam-section-badge">Infrastructure</span>
            <h2 class="hitam-section-title">Hostel Facilities & Amenities</h2>
            <p class="hitam-section-sub mx-auto">
                Comprehensive on-campus utilities ensuring students have uninterrupted focus on learning and well-balanced living.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4 col-lg-3">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-wifi"></i>
                    </div>
                    <h5 class="fw-bold mb-2 fs-6 text-forest">High-Speed Wi-Fi</h5>
                    <p class="text-secondary small mb-0">High-bandwidth campus-wide network with controlled access for academics and projects.</p>
                </div>
            </div>

            <div class="col-md-4 col-lg-3">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <h5 class="fw-bold mb-2 fs-6 text-forest">Hygienic Mess & Dining</h5>
                    <p class="text-secondary small mb-0">Nutritious multi-cuisine meal plans prepared under certified hygiene and health standards.</p>
                </div>
            </div>

            <div class="col-md-4 col-lg-3">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold mb-2 fs-6 text-forest">24/7 Security & CCTV</h5>
                    <p class="text-secondary small mb-0">Round-the-clock security personnel, entry logging, and complete campus perimeter monitoring.</p>
                </div>
            </div>

            <div class="col-md-4 col-lg-3">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-droplet"></i>
                    </div>
                    <h5 class="fw-bold mb-2 fs-6 text-forest">RO Drinking Water</h5>
                    <p class="text-secondary small mb-0">Dedicated commercial RO purification units installed across all residential floors.</p>
                </div>
            </div>

            <div class="col-md-4 col-lg-3">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <h5 class="fw-bold mb-2 fs-6 text-forest">Power Backup</h5>
                    <p class="text-secondary small mb-0">Full generator support for continuous power across student rooms, study halls, and dining areas.</p>
                </div>
            </div>

            <div class="col-md-4 col-lg-3">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-book"></i>
                    </div>
                    <h5 class="fw-bold mb-2 fs-6 text-forest">Study Lounges</h5>
                    <p class="text-secondary small mb-0">Quiet, dedicated halls for group discussions, peer study sessions, and exam preparation.</p>
                </div>
            </div>

            <div class="col-md-4 col-lg-3">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-activity"></i>
                    </div>
                    <h5 class="fw-bold mb-2 fs-6 text-forest">First Aid & Medical</h5>
                    <p class="text-secondary small mb-0">On-campus medical assistance room with visiting doctors and 24/7 emergency vehicle availability.</p>
                </div>
            </div>

            <div class="col-md-4 col-lg-3">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-dribbble"></i>
                    </div>
                    <h5 class="fw-bold mb-2 fs-6 text-forest">Sports & Recreation</h5>
                    <p class="text-secondary small mb-0">Indoor games room, table tennis, badminton court, and access to campus athletic grounds.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('public.facilities') }}" class="btn btn-outline-secondary btn-sm px-4">
                View All Residential Facilities & Specifications <i class="bi bi-chevron-right ms-1 text-forest"></i>
            </a>
        </div>
    </div>
</section>

<!-- Notices & Conduct Section -->
<section class="py-5">
    <div class="container py-4">
        <div class="row g-4">
            <!-- Announcements / Notices Preview -->
            <div class="col-lg-7">
                <div class="card hitam-card h-100">
                    <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center" style="border-color: var(--hitam-border) !important;">
                        <div>
                            <span class="hitam-section-badge mb-1">Official Circulars</span>
                            <h3 class="fs-5 fw-bold mb-0 text-forest">Latest Hostel Notices</h3>
                        </div>
                        <a href="{{ route('public.notices') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="notice-row">
                            <div class="d-flex align-items-center gap-3">
                                <div class="notice-date-box">
                                    <div class="notice-date-day">12</div>
                                    <div class="notice-date-month">Sep</div>
                                </div>
                                <div>
                                    <span class="badge small px-2 py-1 mb-1" style="background-color: var(--hitam-primary-bg); color: var(--hitam-primary-dark); border: 1px solid var(--hitam-primary-light);">All Hostels</span>
                                    <div class="fw-semibold text-dark">Schedule for Semester Room Inspections & Maintenance Check</div>
                                    <small class="text-muted">Issued by Chief Warden Office</small>
                                </div>
                            </div>
                            <a href="{{ route('public.notices') }}" class="btn btn-sm btn-light border"><i class="bi bi-chevron-right"></i></a>
                        </div>

                        <div class="notice-row">
                            <div class="d-flex align-items-center gap-3">
                                <div class="notice-date-box">
                                    <div class="notice-date-day">08</div>
                                    <div class="notice-date-month">Sep</div>
                                </div>
                                <div>
                                    <span class="badge small px-2 py-1 mb-1" style="background-color: var(--hitam-primary-bg); color: var(--hitam-primary-dark); border: 1px solid var(--hitam-primary-light);">Mess Committee</span>
                                    <div class="fw-semibold text-dark">Updated Weekend Mess Menu & Dining Timings Announcement</div>
                                    <small class="text-muted">Issued by Food & Hygiene Committee</small>
                                </div>
                            </div>
                            <a href="{{ route('public.notices') }}" class="btn btn-sm btn-light border"><i class="bi bi-chevron-right"></i></a>
                        </div>

                        <div class="notice-row">
                            <div class="d-flex align-items-center gap-3">
                                <div class="notice-date-box">
                                    <div class="notice-date-day">01</div>
                                    <div class="notice-date-month">Sep</div>
                                </div>
                                <div>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle small px-2 py-1 mb-1">Important</span>
                                    <div class="fw-semibold text-dark">Procedure for Autumn Break Outstation Leave Requests</div>
                                    <small class="text-muted">Online submissions required via Student Portal</small>
                                </div>
                            </div>
                            <a href="{{ route('public.notices') }}" class="btn btn-sm btn-light border"><i class="bi bi-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Code of Conduct & Contact Box -->
            <div class="col-lg-5">
                <div class="card hitam-card h-100 p-4">
                    <span class="hitam-section-badge">Discipline & Norms</span>
                    <h3 class="fs-5 fw-bold mb-3 text-forest">Code of Conduct Highlights</h3>
                    <p class="text-secondary small mb-3">
                        Residents are expected to maintain the highest standards of decorum, respect fellow students, and adhere to hostel guidelines:
                    </p>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="fs-5 text-forest"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <div class="fw-semibold small">Gate Timings</div>
                            <div class="text-secondary small">Campus gate closes at 08:30 PM. All residents must be inside their respective hostels.</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="fs-5 text-forest"><i class="bi bi-person-x"></i></div>
                        <div>
                            <div class="fw-semibold small">Anti-Ragging Policy</div>
                            <div class="text-secondary small">Zero tolerance policy for ragging. Strict legal and disciplinary actions per UGC/AICTE norms.</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="fs-5 text-forest"><i class="bi bi-shield-slash"></i></div>
                        <div>
                            <div class="fw-semibold small">Authorized Outings Only</div>
                            <div class="text-secondary small">All leaves and outstations must be pre-approved by the warden via the Student Portal.</div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('public.rules') }}" class="btn btn-outline-secondary w-50 fw-semibold btn-sm">Full Rulebook</a>
                        <a href="{{ route('public.contact') }}" class="btn btn-hitam-green w-50 fw-semibold btn-sm">Warden Helpdesk</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5" style="background-color: var(--hitam-forest-night); color: white; border-top: 1px solid rgba(165, 214, 167, 0.2);">
    <div class="container py-3">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold text-white mb-2">Are you a registered hostel resident?</h3>
                <p class="small mb-lg-0" style="color: #D1E7D3;">
                    Sign in with your student credentials to view room details, apply for leave, or submit a maintenance ticket.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('login') }}" class="btn btn-hitam-accent px-4 py-2">
                    Resident Portal Login <i class="bi bi-box-arrow-in-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
