@extends('layouts.public')

@section('title', 'Hostel Events, Sports & Cultural Life — HITAM')
@section('meta_description', 'Discover vibrant residential life at HITAM: Hostel Premier League cricket, cultural nights, festival dinners, induction programs, and indoor recreation tournaments.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>Events & Student Life</span>
        </div>
        <h1 class="page-hero-title">Hostel Life, Events & Celebrations</h1>
        <p class="page-hero-sub">
            Residential living at HITAM fosters vibrant camaraderie through sports tournaments, traditional cultural celebrations, weekend talent evenings, and wellness workshops.
        </p>
    </div>
</section>

<!-- Upcoming & Annual Events Grid -->
<section class="py-5">
    <div class="container py-2">
        <div class="text-center mb-5">
            <span class="hitam-section-badge">Residential Culture</span>
            <h2 class="hitam-section-title">Campus Calendar & Celebrations</h2>
            <p class="hitam-section-sub mx-auto">
                Life inside HITAM Hostels creates memories that endure for a lifetime. Here is a glimpse of our annual collegiate events calendar.
            </p>
        </div>

        <div class="row g-4">
            <!-- Event 1: Hostel Premier League -->
            <div class="col-md-6 col-lg-4">
                <div class="hitam-card h-100 overflow-hidden">
                    <div class="p-4 border-bottom bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Sports Tournament</span>
                            <span class="small text-muted"><i class="bi bi-calendar3 me-1"></i> November 2026</span>
                        </div>
                        <h5 class="fw-bold text-forest mb-0">HITAM Hostel Premier League (HPL)</h5>
                    </div>
                    <div class="p-4">
                        <p class="small text-muted mb-3">
                            The flagship inter-wing cricket championship held under floodlights on the HITAM sports grounds. Teams representing diverse floors compete over two vibrant weekends.
                        </p>
                        <div class="d-flex align-items-center justify-content-between small text-secondary pt-2 border-top">
                            <span><i class="bi bi-trophy text-warning me-1"></i> Rolling Trophy</span>
                            <span><i class="bi bi-geo-alt text-danger me-1"></i> Main Sports Ground</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event 2: Hostel Day & Cultural Evening -->
            <div class="col-md-6 col-lg-4">
                <div class="hitam-card h-100 overflow-hidden">
                    <div class="p-4 border-bottom bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Cultural Gala</span>
                            <span class="small text-muted"><i class="bi bi-calendar3 me-1"></i> March 2027</span>
                        </div>
                        <h5 class="fw-bold text-forest mb-0">Annual Hostel Night & Cultural Fest</h5>
                    </div>
                    <div class="p-4">
                        <p class="small text-muted mb-3">
                            An evening of music, acoustic performances, traditional drama, stand-up comedy, and an elaborate multi-course outdoor banquet dinner with faculty and wardens.
                        </p>
                        <div class="d-flex align-items-center justify-content-between small text-secondary pt-2 border-top">
                            <span><i class="bi bi-music-note-beamed text-info me-1"></i> Live Band</span>
                            <span><i class="bi bi-geo-alt text-danger me-1"></i> Campus Amphitheatre</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event 3: Fresher's Residential Induction -->
            <div class="col-md-6 col-lg-4">
                <div class="hitam-card h-100 overflow-hidden">
                    <div class="p-4 border-bottom bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">Induction</span>
                            <span class="small text-muted"><i class="bi bi-calendar3 me-1"></i> October 2026</span>
                        </div>
                        <h5 class="fw-bold text-forest mb-0">Fresher's Orientation & Welcome Gala</h5>
                    </div>
                    <div class="p-4">
                        <p class="small text-muted mb-3">
                            A warm, welcoming ice-breaker session where new incoming residents are paired with senior academic mentors and introduced to hostel wardens and student leaders.
                        </p>
                        <div class="d-flex align-items-center justify-content-between small text-secondary pt-2 border-top">
                            <span><i class="bi bi-people text-primary me-1"></i> Mentorship Connect</span>
                            <span><i class="bi bi-geo-alt text-danger me-1"></i> Indoor Auditorium</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event 4: Traditional Festivals & Grand Dinners -->
            <div class="col-md-6 col-lg-4">
                <div class="hitam-card h-100 overflow-hidden">
                    <div class="p-4 border-bottom bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Festivity</span>
                            <span class="small text-muted"><i class="bi bi-calendar3 me-1"></i> Year-Round</span>
                        </div>
                        <h5 class="fw-bold text-forest mb-0">Pan-Indian Festive Celebrations</h5>
                    </div>
                    <div class="p-4">
                        <p class="small text-muted mb-3">
                            From Ganesh Utsav, Diwali lights, and Pongal/Sankranti kite festivals to Eid feasts and Christmas carols — our diverse student body celebrates unity in diversity.
                        </p>
                        <div class="d-flex align-items-center justify-content-between small text-secondary pt-2 border-top">
                            <span><i class="bi bi-stars text-warning me-1"></i> Festive Banquets</span>
                            <span><i class="bi bi-geo-alt text-danger me-1"></i> Dining Hall & Courtyard</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event 5: Badminton & Table Tennis Tournament -->
            <div class="col-md-6 col-lg-4">
                <div class="hitam-card h-100 overflow-hidden">
                    <div class="p-4 border-bottom bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Indoor Games</span>
                            <span class="small text-muted"><i class="bi bi-calendar3 me-1"></i> Bi-Annual</span>
                        </div>
                        <h5 class="fw-bold text-forest mb-0">Smash & Spin Indoor Championship</h5>
                    </div>
                    <div class="p-4">
                        <p class="small text-muted mb-3">
                            Fast-paced singles and doubles tournaments in table tennis, badminton, chess, and carrom organized across both boys and girls residential recreation clubs.
                        </p>
                        <div class="d-flex align-items-center justify-content-between small text-secondary pt-2 border-top">
                            <span><i class="bi bi-award text-success me-1"></i> Medals & Honors</span>
                            <span><i class="bi bi-geo-alt text-danger me-1"></i> Recreation Centre</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event 6: Mental Health & Wellness Seminars -->
            <div class="col-md-6 col-lg-4">
                <div class="hitam-card h-100 overflow-hidden">
                    <div class="p-4 border-bottom bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Wellness</span>
                            <span class="small text-muted"><i class="bi bi-calendar3 me-1"></i> Monthly</span>
                        </div>
                        <h5 class="fw-bold text-forest mb-0">Mindfulness, Yoga & Stress Buster Sessions</h5>
                    </div>
                    <div class="p-4">
                        <p class="small text-muted mb-3">
                            Guided morning meditation, yoga asanas, exam anxiety management workshops, and pastoral counseling sessions led by licensed campus wellness psychologists.
                        </p>
                        <div class="d-flex align-items-center justify-content-between small text-secondary pt-2 border-top">
                            <span><i class="bi bi-heart text-danger me-1"></i> Resident Well-being</span>
                            <span><i class="bi bi-geo-alt text-danger me-1"></i> Yoga Lawn</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
