@extends('layouts.public')

@section('title', 'New Boys Hostel Block (Upcoming) — Modern Eco-Smart Living | HITAM')
@section('meta_description', 'Preview the upcoming New Boys Hostel Block at HITAM campus. Featuring smart IoT energy controls, modern elevators, cafeteria terrace, gymnasium, and air-conditioned room options.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>Hostels</span>
            <span>/</span>
            <span>New Boys Hostel</span>
        </div>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-hero-title">New Boys Hostel (Under Development)</h1>
                <p class="page-hero-sub">
                    A next-generation residential complex blending sustainable green building architecture with state-of-the-art living amenities for 500+ undergraduate and postgraduate scholars.
                </p>
            </div>
            <div class="badge-status badge-coming-soon px-3 py-2 fs-6">
                <i class="bi bi-cone-striped"></i> Launching Next Academic Year
            </div>
        </div>
    </div>
</section>

<!-- Vision & Architectural Highlights -->
<section class="py-5">
    <div class="container py-2">
        <div class="row g-5 align-items-center">
            <div class="col-lg-7">
                <span class="hitam-section-badge">Infrastructure Expansion</span>
                <h2 class="hitam-section-title">Smart, Sustainable & Futuristic Living</h2>
                <p class="text-secondary mb-3">
                    As part of HITAM’s continuous expansion to support our burgeoning engineering community, the New Boys Hostel Block is being constructed to IGBC (Indian Green Building Council) Platinum standards.
                </p>
                <p class="text-secondary mb-4">
                    The facility is engineered with passive cooling facades, integrated rainwater harvesting, rooftop solar power arrays, high-speed elevators, and sound-insulated individual study cubicles.
                </p>

                <!-- Key Highlights Grid -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-white border rounded">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-snow text-success fs-5"></i>
                                <span class="fw-bold">Optional AC Rooms</span>
                            </div>
                            <div class="small text-muted">Energy-efficient inverter VRV air conditioning for select premium suites.</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white border rounded">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-building-up text-success fs-5"></i>
                                <span class="fw-bold">Dual Passenger Lifts</span>
                            </div>
                            <div class="small text-muted">Modern ARD-equipped automatic elevators serving all 6 floors seamlessly.</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white border rounded">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-cup-straw text-success fs-5"></i>
                                <span class="fw-bold">Terrace Café & Lounge</span>
                            </div>
                            <div class="small text-muted">Relaxing open-air recreational deck for peer socializing and group ideation.</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white border rounded">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-lightning-charge text-success fs-5"></i>
                                <span class="fw-bold">Solar Micro-Grid</span>
                            </div>
                            <div class="small text-muted">Rooftop 150 kW solar array supplying clean green energy to all residential wings.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4 bg-white border rounded-3">
                    <h5 class="fw-bold text-forest mb-3"><i class="bi bi-clock-history me-2 text-accent-green"></i>Development Roadmap</h5>
                    <div class="border-start border-2 border-success ps-3 my-3">
                        <div class="mb-4">
                            <span class="badge bg-success-subtle text-success border border-success-subtle mb-1">Completed</span>
                            <h6 class="fw-bold mb-1">Structural Frame & Civil Works</h6>
                            <p class="small text-muted mb-0">6-storey RCC framed structure completed with eco-brick masonry.</p>
                        </div>
                        <div class="mb-4">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-1">In Progress</span>
                            <h6 class="fw-bold mb-1">Electrical, HVAC & Plumbing</h6>
                            <p class="small text-muted mb-0">Installation of solar water heaters, elevator shafts, and fire protection network.</p>
                        </div>
                        <div>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle mb-1">Upcoming</span>
                            <h6 class="fw-bold mb-1">Furnishing & Allotment Launch</h6>
                            <p class="small text-muted mb-0">Final aesthetic interior fit-out, Wi-Fi roll-out, and inaugural batch room booking.</p>
                        </div>
                    </div>
                    <div class="pt-3 border-top text-center">
                        <a href="{{ route('public.notices') }}" class="btn btn-hitam-outline-green btn-sm w-100">
                            Check Latest Project Notices
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
