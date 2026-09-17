@extends('layouts.public')

@section('title', 'Girls Hostel — Secure, Modern & Empowering Residential Life | HITAM')
@section('meta_description', 'Discover the Girls Hostel at HITAM Hyderabad. High-security residential premises with biometric access, female resident wardens, gym, library corner, and nutritious dining.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>Hostels</span>
            <span>/</span>
            <span>Girls Hostel</span>
        </div>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-hero-title">Girls Residential Block</h1>
                <p class="page-hero-sub">
                    A safe, compassionate, and contemporary residential space offering female scholars maximum security, dedicated wellness zones, and serene study ambiances.
                </p>
            </div>
            <div class="badge-status badge-active px-3 py-2 fs-6">
                <i class="bi bi-shield-check"></i> High-Security Monitored Block
            </div>
        </div>
    </div>
</section>

<!-- Overview & Key Facts -->
<section class="py-5">
    <div class="container py-2">
        <div class="row g-4">
            <div class="col-lg-8">
                <span class="hitam-section-badge">Welfare & Security</span>
                <h2 class="hitam-section-title">An Empowering Living Experience</h2>
                <p class="text-secondary mb-3">
                    The HITAM Girls Hostel is designed with safety and comfort as paramount values. Situated in a private, tranquil enclave of the campus, the facility is secured 24/7 with round-the-clock female security personnel, continuous CCTV surveillance, and biometric turnstiles.
                </p>
                <p class="text-secondary mb-4">
                    Female resident wardens reside within the premises to offer immediate assistance, medical first aid, and parental care. The hostel also features an indoor recreation lounge, personal fitness equipment, and a quiet reading gallery.
                </p>

                <!-- Key Security Features -->
                <h4 class="fw-bold text-forest mb-3">Dedicated Safety Safeguards</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-fingerprint text-success fs-4"></i>
                                <h6 class="fw-bold mb-0">Biometric Gate Logging</h6>
                            </div>
                            <p class="small text-muted mb-0">Strict entry and exit records synchronized automatically with the central administrative portal.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-person-fill-check text-success fs-4"></i>
                                <h6 class="fw-bold mb-0">Female Resident Wardens</h6>
                            </div>
                            <p class="small text-muted mb-0">Experienced faculty members staying full-time inside the residential block for pastoral care.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-camera-video text-success fs-4"></i>
                                <h6 class="fw-bold mb-0">Perimeter CCTV Surveillance</h6>
                            </div>
                            <p class="small text-muted mb-0">Corridors, entry lobbies, and campus pathways continuously monitored by campus security control.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-telephone-inbound text-success fs-4"></i>
                                <h6 class="fw-bold mb-0">Parent SMS Outpass Intimation</h6>
                            </div>
                            <p class="small text-muted mb-0">Automated SMS notifications sent to registered parents upon leave approval and hostel departure.</p>
                        </div>
                    </div>
                </div>

                <!-- Room Configuration -->
                <h4 class="fw-bold text-forest mb-3">Available Accommodations</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="hitam-card p-4 h-100">
                            <span class="badge bg-light text-forest border mb-2">Premium Tier</span>
                            <h5 class="fw-bold mb-1">2-Sharing Room (Attached Bath)</h5>
                            <p class="text-muted small mb-3">Spacious dual-occupancy layout with attached modern bathroom and personal vanity wardrobe.</p>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>2 Ergonomic Beds with Mattresses</li>
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>Dual Study Desks with Softboard</li>
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>Solar Hot Water Geyser</li>
                                <li><i class="bi bi-check2 text-success me-1"></i>Spacious Balcony Ventilation</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="hitam-card p-4 h-100">
                            <span class="badge bg-light text-forest border mb-2">Standard Tier</span>
                            <h5 class="fw-bold mb-1">3-Sharing Room (Standard)</h5>
                            <p class="text-muted small mb-3">Well-designed triple occupancy room with individual storage lockers and study area.</p>
                            <ul class="list-unstyled small text-secondary mb-0">
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>3 Individual Beds</li>
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>Personal Lockers & Wardrobes</li>
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i>Spacious Common Restrooms</li>
                                <li><i class="bi bi-check2 text-success me-1"></i>24/7 Running Water</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Routine and Curfew -->
                <div class="card border-0 shadow-sm p-4 bg-white rounded-3 border">
                    <h5 class="fw-bold text-forest mb-3"><i class="bi bi-clock-history me-2 text-accent-green"></i>In-Time & Daily Protocols</h5>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Hostel Gate Opens</span>
                                <span class="fw-semibold">06:00 AM</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Mandatory Evening In-Time</span>
                                <span class="fw-semibold text-danger">06:30 PM (Campus Gate)</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Hostel Block In-Time</span>
                                <span class="fw-semibold text-danger">07:00 PM</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex justify-content-between border-bottom pb-2">
                                <span class="text-muted">Biometric Attendance</span>
                                <span class="fw-semibold text-primary">07:15 PM - 08:00 PM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="hitam-card p-4 mb-4">
                    <h5 class="fw-bold text-forest mb-3">Hostel Details</h5>
                    <ul class="list-unstyled small mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Capacity</span>
                            <span class="fw-bold text-forest">320+ Residents</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Security Staff</span>
                            <span class="fw-bold text-forest">100% Female Security</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Medical Nurse</span>
                            <span class="fw-bold text-forest">Available On-Call</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Warden Residence</span>
                            <span class="fw-bold text-forest">Ground Floor Wing</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Warden Contact</span>
                            <span class="fw-bold text-forest">+91 92480 09875</span>
                        </li>
                    </ul>
                </div>

                <div class="hitam-card p-4 bg-light border-0">
                    <h5 class="fw-bold text-forest mb-2">Leave & Outpass Protocol</h5>
                    <p class="small text-muted mb-3">
                        Residents require parental telephonic confirmation and digital warden approval before weekend or holiday leave.
                    </p>
                    <a href="{{ route('public.rules') }}" class="btn btn-hitam-green w-100 mb-2">
                        <i class="bi bi-shield-check me-1"></i> View Leave Rules
                    </a>
                    <a href="{{ route('public.contact') }}" class="btn btn-hitam-outline-green w-100">
                        Contact Girls Warden
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
