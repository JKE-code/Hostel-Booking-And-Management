@extends('layouts.public')

@section('title', 'About Hostels & Residential Life — HITAM')
@section('meta_description', 'Learn about Hyderabad Institute of Technology and Management (HITAM) residential life, leadership, student welfare, green campus philosophy and pastoral care.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>About Us</span>
        </div>
        <h1 class="page-hero-title">About HITAM Residential Living</h1>
        <p class="page-hero-sub">
            A nurturing collegiate environment that harmonizes academic rigor, personal discipline, cultural bonding, and eco-conscious living amidst our lush green campus.
        </p>
    </div>
</section>

<!-- Vision & Mission Overview -->
<section class="py-5">
    <div class="container py-2">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="hitam-section-badge">Institutional Ethos</span>
                <h2 class="hitam-section-title">Home Away From Home</h2>
                <p class="text-secondary mb-3">
                    HITAM Hostel provides an inclusive, secure, and technologically advanced living ecosystem situated within our celebrated green-certified educational campus at Medchal, Hyderabad.
                </p>
                <p class="text-secondary mb-4">
                    Residential living at HITAM is designed to instill lifelong values of community harmony, teamwork, and leadership while giving scholars comfortable living quarters, dedicated quiet study hours, nutritious culinary offerings, and round-the-clock pastoral support.
                </p>
                
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-white border rounded">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-shield-check text-success fs-5"></i>
                                <span class="fw-bold">Safety First</span>
                            </div>
                            <div class="small text-muted">24/7 security personnel, biometric gate logging, and high-resolution CCTV.</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white border rounded">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-tree text-success fs-5"></i>
                                <span class="fw-bold">Green Campus</span>
                            </div>
                            <div class="small text-muted">Eco-friendly infrastructure, solar water heaters, and natural ventilation.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 12px; background: var(--hitam-primary-bg); border: 1px solid var(--hitam-primary-light) !important;">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold text-forest mb-3"><i class="bi bi-quote me-2 text-accent-green"></i>Warden's Welcome Message</h4>
                        <p class="text-secondary fst-italic mb-4" style="line-height: 1.8;">
                            "Our mandate at HITAM Hostels extends far beyond providing beds and meals. We are mentors, guardians, and advocates for every young mind that walks through our gates. We prioritize an environment where every scholar feels safe, heard, healthy, and challenged to achieve academic excellence."
                        </p>
                        <div class="d-flex align-items-center gap-3 pt-3 border-top border-success-subtle">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                CW
                            </div>
                            <div>
                                <div class="fw-bold text-forest">Prof. V. Ramanjaneyulu</div>
                                <div class="small text-muted">Chief Warden & Dean of Student Affairs</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Administrative & Pastoral Hierarchy -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container py-2">
        <div class="text-center mb-5">
            <span class="hitam-section-badge">Leadership</span>
            <h2 class="hitam-section-title">Hostel Administrative Committee</h2>
            <p class="hitam-section-sub mx-auto">
                Our dedicated wardens, resident caretakers, and student counselors oversee welfare, discipline, and daily comfort.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="hitam-card p-4 h-100 text-center">
                    <div class="mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center border" style="width: 72px; height: 72px;">
                        <i class="bi bi-person-badge fs-2 text-forest"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-1">Prof. V. Ramanjaneyulu</h5>
                    <div class="small text-accent-green fw-semibold mb-2">Chief Warden</div>
                    <p class="small text-muted mb-3">Overall administration, institutional policy implementation, and high-level student welfare.</p>
                    <div class="small text-secondary"><i class="bi bi-envelope me-1"></i> chiefwarden@hitam.org</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="hitam-card p-4 h-100 text-center">
                    <div class="mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center border" style="width: 72px; height: 72px;">
                        <i class="bi bi-person-workspace fs-2 text-forest"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-1">Mr. K. Sreenivasulu</h5>
                    <div class="small text-accent-green fw-semibold mb-2">Resident Warden (Boys Hostel)</div>
                    <p class="small text-muted mb-3">Boys block oversight, evening attendance, room allotments, and discipline maintenance.</p>
                    <div class="small text-secondary"><i class="bi bi-telephone me-1"></i> +91 92480 09874</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="hitam-card p-4 h-100 text-center">
                    <div class="mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center border" style="width: 72px; height: 72px;">
                        <i class="bi bi-person-hearts fs-2 text-forest"></i>
                    </div>
                    <h5 class="fw-bold text-forest mb-1">Dr. S. Padmavathi</h5>
                    <div class="small text-accent-green fw-semibold mb-2">Resident Warden (Girls Hostel)</div>
                    <p class="small text-muted mb-3">Girls block oversight, wellness support, resident safety protocols, and student council guidance.</p>
                    <div class="small text-secondary"><i class="bi bi-telephone me-1"></i> +91 92480 09875</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Key Pillars -->
<section class="py-5">
    <div class="container py-2">
        <div class="text-center mb-5">
            <span class="hitam-section-badge">Core Pillars</span>
            <h2 class="hitam-section-title">Standards We Uphold</h2>
            <p class="hitam-section-sub mx-auto">Guided by HITAM's doing engineering philosophy, student residency emphasizes independence and responsibility.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="facility-item text-center">
                    <div class="facility-icon-wrap mx-auto">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Strict Anti-Ragging</h5>
                    <p class="small text-muted mb-0">Zero tolerance policy aligned with Supreme Court directives and UGC norms.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="facility-item text-center">
                    <div class="facility-icon-wrap mx-auto">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Hygienic Dining</h5>
                    <p class="small text-muted mb-0">Nutritionally balanced multi-cuisine diet curated with student mess committee feedback.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="facility-item text-center">
                    <div class="facility-icon-wrap mx-auto">
                        <i class="bi bi-wifi"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Gigabit Network</h5>
                    <p class="small text-muted mb-0">High-speed Wi-Fi access in common rooms and study halls for continuous learning.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="facility-item text-center">
                    <div class="facility-icon-wrap mx-auto">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Medical Readiness</h5>
                    <p class="small text-muted mb-0">On-campus dispensary, medical practitioner visits, and 24/7 emergency vehicle.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-light border-top text-center">
    <div class="container py-2">
        <h3 class="fw-bold text-forest mb-3">Explore Accommodations & Blocks</h3>
        <p class="text-secondary mb-4 mx-auto" style="max-width: 550px;">
            Review our Boys and Girls residential wings, check available sharing capacities, and download the allotment prospectus.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('public.hostels.boys') }}" class="btn btn-hitam-green">View Boys Hostel</a>
            <a href="{{ route('public.hostels.girls') }}" class="btn btn-hitam-outline-green">View Girls Hostel</a>
        </div>
    </div>
</section>
@endsection
