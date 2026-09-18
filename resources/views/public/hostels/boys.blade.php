@extends('layouts.public')

@section('title', 'Boys Hostel — Facilities, Rooms & Amenities | HITAM')
@section('meta_description', 'Explore the Boys Hostel at HITAM Hyderabad. Equipped with 2-sharing, 3-sharing, and 4-sharing rooms, study zones, indoor games, high-speed Wi-Fi, and 24/7 security.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>Hostels</span>
            <span>/</span>
            <span>Boys Hostel</span>
        </div>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-hero-title">Boys Residential Block</h1>
                <p class="page-hero-sub">
                    A vibrant, well-ventilated, multi-storey accommodation designed for engineering scholars seeking focused study and healthy peer collaboration.
                </p>
            </div>
            <div class="badge-status badge-active px-3 py-2 fs-6">
                <i class="bi bi-check-circle-fill"></i> Operational & Admissions Open
            </div>
        </div>
    </div>
</section>

<!-- Overview & Key Facts -->
<section class="py-5">
    <div class="container py-2">
        <div class="row g-4">
            <div class="col-lg-8">
                <span class="hitam-section-badge">Residential Wing</span>
                <h2 class="hitam-section-title">Comfortable Living with Serious Academic Focus</h2>
                <p class="text-secondary mb-3">
                    The Boys Hostel at HITAM features well-lit, cross-ventilated rooms furnished with individual steel beds, study desks, ergonomic chairs, bookshelves, and secured cupboards for every resident.
                </p>
                <p class="text-secondary mb-4">
                    The block operates under the direct supervision of resident wardens and residential tutors. Regular quiet hours are observed every night to foster an atmosphere conducive to university coursework, GATE/GRE preparation, and research projects.
                </p>

                <!-- Room Categories Cards -->
                <h4 class="fw-bold text-forest mb-3">Room Configurations</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="hitam-card p-3 h-100">
                            <span class="badge bg-light text-forest border mb-2">Tier 1</span>
                            <h5 class="fw-bold mb-1">2-Sharing Room</h5>
                            <div class="text-muted small mb-3">Double occupancy with attached washroom & individual study corners.</div>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>2 Wooden / Steel Cots</li>
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>2 Study Tables & Chairs</li>
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>Dedicated Wardrobes</li>
                                <li><i class="bi bi-check2 text-success me-1"></i>Geyser & Hot Water</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="hitam-card p-3 h-100">
                            <span class="badge bg-light text-forest border mb-2">Tier 2</span>
                            <h5 class="fw-bold mb-1">3-Sharing Room</h5>
                            <div class="text-muted small mb-3">Triple occupancy room, popular among peer study groups.</div>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>3 Individual Cots</li>
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>Individual Storage Units</li>
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>Shared Study Desks</li>
                                <li><i class="bi bi-check2 text-success me-1"></i>Solar Hot Water</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="hitam-card p-3 h-100">
                            <span class="badge bg-light text-forest border mb-2">Tier 3</span>
                            <h5 class="fw-bold mb-1">4-Sharing Economy</h5>
                            <div class="text-muted small mb-3">Spacious quad-sharing room optimized for budget-conscious students.</div>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>4 Individual Cots</li>
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>High-capacity Lockers</li>
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>Ceiling Fans & Light</li>
                                <li><i class="bi bi-check2 text-success me-1"></i>Shared Floor Washrooms</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Daily Schedule & Gates -->
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3 border">
                    <h5 class="fw-bold text-forest mb-3"><i class="bi bi-clock-history me-2 text-accent-green"></i>Daily Routine & In-Time Protocol</h5>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Hostel Gate Opens</span>
                                <span class="fw-semibold">06:00 AM</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Breakfast Timings</span>
                                <span class="fw-semibold">07:30 AM - 08:45 AM</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Mandatory In-Time</span>
                                <span class="fw-semibold text-danger">08:30 PM</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Biometric Roll Call</span>
                                <span class="fw-semibold text-primary">09:00 PM - 09:30 PM</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Quiet Study Hours</span>
                                <span class="fw-semibold">10:00 PM - 06:00 AM</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Gate Closure</span>
                                <span class="fw-semibold text-danger">10:00 PM (Strict)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <div class="hitam-card p-4 mb-4">
                    <h5 class="fw-bold text-forest mb-3">Quick Specifications</h5>
                    <ul class="list-unstyled small mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Total Capacity</span>
                            <span class="fw-bold text-forest">450+ Beds</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Floors</span>
                            <span class="fw-bold text-forest">Ground + 4 Floors</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Wi-Fi Coverage</span>
                            <span class="fw-bold text-forest">100% (Common & Rooms)</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Power Backup</span>
                            <span class="fw-bold text-forest">24/7 Generator System</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Water Supply</span>
                            <span class="fw-bold text-forest">24/7 RO Purified Water</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Caretaker Contact</span>
                            <span class="fw-bold text-forest">+91 92480 09874</span>
                        </li>
                    </ul>
                </div>

                <div class="hitam-card p-4 bg-light border-0">
                    <h5 class="fw-bold text-forest mb-2">Need an Allotment?</h5>
                    <p class="small text-muted mb-3">
                        Room allotment is conducted on merit and distance criteria at the beginning of each academic session.
                    </p>
                    <a href="{{ route('public.downloads') }}" class="btn btn-hitam-green w-100 mb-2">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Download Allotment Form
                    </a>
                    <a href="{{ route('public.faq') }}" class="btn btn-hitam-outline-green w-100">
                        View Hostel FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Photo Strip: Visual Hostel Gallery --}}
<section class="py-5 bg-white border-top">
    <div class="container py-2">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div>
                <span class="hitam-section-badge">Visual Tour</span>
                <h2 class="hitam-section-title mb-0">Inside the Boys Hostel</h2>
            </div>
            <a href="{{ route('public.gallery') }}" class="btn btn-sm btn-hitam-outline-green mt-2 mt-sm-0">
                Full Gallery <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="photo-strip">
            <div class="photo-strip-item">
                <img src="{{ asset('images/hostel-building.jpg') }}" alt="Boys Hostel Exterior"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div class="img-placeholder" style="display:none;">
                    <i class="bi bi-building"></i>
                    <span class="img-ph-label">Hostel Exterior</span>
                </div>
            </div>
            <div class="photo-strip-item">
                <img src="{{ asset('images/student-room.jpg') }}" alt="Furnished Student Room"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div class="img-placeholder" style="display:none;">
                    <i class="bi bi-door-open"></i>
                    <span class="img-ph-label">Student Room</span>
                </div>
            </div>
            <div class="photo-strip-item">
                <img src="{{ asset('images/dining-hall.jpg') }}" alt="Hostel Dining Hall"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div class="img-placeholder" style="display:none;">
                    <i class="bi bi-cup-hot"></i>
                    <span class="img-ph-label">Dining Hall</span>
                </div>
            </div>
            <div class="photo-strip-item">
                <div class="img-placeholder h-100" style="min-height:unset;">
                    <i class="bi bi-dribbble"></i>
                    <span class="img-ph-label">Sports & Rec</span>
                    <span class="img-ph-sub">Photo coming soon</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Warden Quote --}}
<section class="py-4" style="background: linear-gradient(135deg, #042E16 0%, #064E3B 100%); color: white;">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-quote fs-1 text-success opacity-50 mt-n2 flex-shrink-0"></i>
                    <div>
                        <p class="fs-5 fw-light fst-italic mb-2" style="line-height:1.7;color:#D1FAE5;">
                            "We pride ourselves on creating a hostel environment that feels less like accommodation and more like a second home — where discipline nurtures discipline, and community builds character."
                        </p>
                        <div class="fw-bold text-white">Prof. V. Ramanjaneyulu</div>
                        <div class="small" style="color:#6EE7B7;">Chief Warden, HITAM Residential Campus</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('public.contact') }}" class="btn btn-hitam-accent">
                    <i class="bi bi-telephone me-1"></i> Contact Boys Hostel Warden
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
