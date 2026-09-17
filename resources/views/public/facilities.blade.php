@extends('layouts.public')

@section('title', 'Hostel Facilities & Infrastructure — HITAM')
@section('meta_description', 'Discover the full suite of facilities at HITAM hostels: high-speed Wi-Fi, modern study halls, gym, sports courts, laundromat, purified RO drinking water, and medical care.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>Facilities</span>
        </div>
        <h1 class="page-hero-title">Hostel Facilities & Infrastructure</h1>
        <p class="page-hero-sub">
            Engineered to cater to every dimension of student welfare — academic focus, physical fitness, hygienic living, and uninterrupted comfort.
        </p>
    </div>
</section>

<!-- Facilities Grid -->
<section class="py-5">
    <div class="container py-2">
        <div class="text-center mb-5">
            <span class="hitam-section-badge">Student Comfort</span>
            <h2 class="hitam-section-title">World-Class Amenities on Campus</h2>
            <p class="hitam-section-sub mx-auto">
                Every facility is systematically maintained by dedicated engineering teams to ensure 99.9% uptime and zero disruption to your daily study routine.
            </p>
        </div>

        <div class="row g-4">
            <!-- Facility 1: High-Speed Internet -->
            <div class="col-md-6 col-lg-4">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-wifi"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-2">Gigabit Campus Wi-Fi</h5>
                    <p class="text-muted small mb-3">
                        Dual high-bandwidth fiber optic leased lines providing seamless Wi-Fi connectivity across all hostel blocks, common rooms, and outdoor courtyards.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check2 text-success me-1"></i>High-speed secure firewalled network</li>
                        <li><i class="bi bi-check2 text-success me-1"></i>Optimized for online coursework & coding</li>
                    </ul>
                </div>
            </div>

            <!-- Facility 2: 24/7 Power & Generator Backup -->
            <div class="col-md-6 col-lg-4">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-2">Uninterrupted Power Backup</h5>
                    <p class="text-muted small mb-3">
                        Heavy-duty automated diesel generators coupled with central UPS systems guarantee zero disruption to power in study areas and common corridors.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check2 text-success me-1"></i>Automatic switch-over within 10 seconds</li>
                        <li><i class="bi bi-check2 text-success me-1"></i>Dedicated study hall lighting backup</li>
                    </ul>
                </div>
            </div>

            <!-- Facility 3: 100% RO Purified Drinking Water -->
            <div class="col-md-6 col-lg-4">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-droplet-half"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-2">Commercial RO Water Plant</h5>
                    <p class="text-muted small mb-3">
                        Multi-stage reverse osmosis water treatment units with UV and ozonization dispensers stationed conveniently on every residential floor.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check2 text-success me-1"></i>Regular certified microbiological lab testing</li>
                        <li><i class="bi bi-check2 text-success me-1"></i>Cold and normal temperature dispensers</li>
                    </ul>
                </div>
            </div>

            <!-- Facility 4: Solar Hot Water Systems -->
            <div class="col-md-6 col-lg-4">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-sun"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-2">Solar Thermal Water Heating</h5>
                    <p class="text-muted small mb-3">
                        Rooftop high-capacity solar thermal collector panels supplying continuous hot water to resident bathrooms during early mornings and winter seasons.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check2 text-success me-1"></i>Eco-friendly green energy design</li>
                        <li><i class="bi bi-check2 text-success me-1"></i>Electric backup heating for overcast days</li>
                    </ul>
                </div>
            </div>

            <!-- Facility 5: Fitness Gym & Recreation -->
            <div class="col-md-6 col-lg-4">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-2">Student Gym & Indoor Sports</h5>
                    <p class="text-muted small mb-3">
                        Dedicated fitness spaces equipped with dumbbells, multi-gym stations, treadmills, alongside indoor games including table tennis, carrom, and chess.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check2 text-success me-1"></i>Separate gym hours for boys and girls</li>
                        <li><i class="bi bi-check2 text-success me-1"></i>Outdoor basketball & volleyball courts</li>
                    </ul>
                </div>
            </div>

            <!-- Facility 6: Health Center & Dispensary -->
            <div class="col-md-6 col-lg-4">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-hospital"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-2">Dispensary & 24/7 Ambulance</h5>
                    <p class="text-muted small mb-3">
                        Resident medical room staffed with qualified nursing personnel, stocked with essential medications, and an ambulance on standby for emergency transport.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check2 text-success me-1"></i>Doctor visits 3 days a week</li>
                        <li><i class="bi bi-check2 text-success me-1"></i>Tie-up with multi-specialty hospitals</li>
                    </ul>
                </div>
            </div>

            <!-- Facility 7: Laundry & Ironing Service -->
            <div class="col-md-6 col-lg-4">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-basket3"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-2">Automated Laundry Service</h5>
                    <p class="text-muted small mb-3">
                        Commercial washer and steam ironing services operated on a scheduled pickup cycle so residents can focus entirely on studies.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check2 text-success me-1"></i>Bi-weekly pickup and return cycle</li>
                        <li><i class="bi bi-check2 text-success me-1"></i>Dedicated tagging to prevent mix-ups</li>
                    </ul>
                </div>
            </div>

            <!-- Facility 8: 24/7 Monitored Security -->
            <div class="col-md-6 col-lg-4">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-2">24/7 Campus Security & CCTV</h5>
                    <p class="text-muted small mb-3">
                        Professional security guards deployed at perimeter checkpoints, turnstiles, and dormitory entrances, backed by round-the-clock IP CCTV monitoring.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check2 text-success me-1"></i>Strict visitor verification protocol</li>
                        <li><i class="bi bi-check2 text-success me-1"></i>Nightly security patrol rounds</li>
                    </ul>
                </div>
            </div>

            <!-- Facility 9: Quiet Study Reading Halls -->
            <div class="col-md-6 col-lg-4">
                <div class="facility-item">
                    <div class="facility-icon-wrap">
                        <i class="bi bi-book"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-2">Quiet Study Reading Halls</h5>
                    <p class="text-muted small mb-3">
                        Airy, well-lit study rooms with individual power ports and quiet zone discipline, open till late night during semester and university exams.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li><i class="bi bi-check2 text-success me-1"></i>Extended night hours during exams</li>
                        <li><i class="bi bi-check2 text-success me-1"></i>Reference textbooks & magazines</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Maintenance SLA Callout -->
<section class="py-5 bg-white border-top">
    <div class="container py-2">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h3 class="fw-bold text-forest mb-2">Rapid Maintenance Complaint Resolution</h3>
                <p class="text-secondary mb-0">
                    Residents can submit plumbing, electrical, carpentry, or internet issues directly through the online student portal. Our on-campus estate maintenance team resolves tickets within a 24-hour turnaround window.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('login') }}" class="btn btn-hitam-green">
                    <i class="bi bi-tools me-1"></i> Student Portal Helpdesk
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
