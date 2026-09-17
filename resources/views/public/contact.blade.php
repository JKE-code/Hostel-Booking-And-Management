@extends('layouts.public')

@section('title', 'Contact Us & Campus Location — HITAM Hostels')
@section('meta_description', 'Contact HITAM Hostel Administration, Chief Warden, Boys & Girls Hostel Resident Wardens, and Security Desk. Campus location, Medchal Hyderabad directions and inquiries.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>Contact Us</span>
        </div>
        <h1 class="page-hero-title">Contact Hostel Administration</h1>
        <p class="page-hero-sub">
            We are here to assist parents, current residents, and prospective scholars. Reach out to our residential wardens, helpdesk, or campus security office.
        </p>
    </div>
</section>

<!-- Contact Form & Directory -->
<section class="py-5">
    <div class="container py-2">
        <!-- Success Alert Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-5">
            <!-- Left: Contact Form -->
            <div class="col-lg-7">
                <div class="hitam-card p-4 p-md-5">
                    <span class="hitam-section-badge">Direct Inquiry</span>
                    <h3 class="fw-bold text-forest mb-2">Send an Inquiry to Warden's Office</h3>
                    <p class="text-secondary small mb-4">
                        Fill in your query below and our residential support staff will respond via email or phone within 24 business hours.
                    </p>

                    <form action="{{ route('public.contact.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold small text-dark">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required value="{{ old('name') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold small text-dark">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required value="{{ old('email') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold small text-dark">Phone / Mobile Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="+91 98765 43210" required value="{{ old('phone') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label fw-semibold small text-dark">Inquiry Category *</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="" selected disabled>Select Category</option>
                                    <option value="New Admission & Allotment">New Admission & Allotment</option>
                                    <option value="Hostel Fee & Payment Inquiry">Hostel Fee & Payment Inquiry</option>
                                    <option value="Mess & Dietary Question">Mess & Dietary Question</option>
                                    <option value="Parent Visit & Campus Access">Parent Visit & Campus Access</option>
                                    <option value="General Administration">General Administration</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label fw-semibold small text-dark">Your Message / Inquiry *</label>
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Detail your inquiry or specific requirement..." required>{{ old('message') }}</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-hitam-green w-100 py-2 fs-6">
                                    <i class="bi bi-send me-1"></i> Submit Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: Office Directory & Quick Contacts -->
            <div class="col-lg-5">
                <div class="hitam-card p-4 mb-4">
                    <h5 class="fw-bold text-forest mb-3">Key Administration Contacts</h5>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="fw-bold text-dark">Chief Warden & Dean of Student Affairs</div>
                        <div class="small text-muted mb-1">Prof. V. Ramanjaneyulu</div>
                        <div class="small text-secondary"><i class="bi bi-envelope me-1 text-forest"></i> chiefwarden@hitam.org</div>
                        <div class="small text-secondary"><i class="bi bi-telephone me-1 text-forest"></i> +91 92480 09871 (Ext: 104)</div>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <div class="fw-bold text-dark">Resident Warden (Boys Hostel)</div>
                        <div class="small text-muted mb-1">Mr. K. Sreenivasulu</div>
                        <div class="small text-secondary"><i class="bi bi-envelope me-1 text-forest"></i> boyswarden@hitam.org</div>
                        <div class="small text-secondary"><i class="bi bi-telephone me-1 text-forest"></i> +91 92480 09874</div>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <div class="fw-bold text-dark">Resident Warden (Girls Hostel)</div>
                        <div class="small text-muted mb-1">Dr. S. Padmavathi</div>
                        <div class="small text-secondary"><i class="bi bi-envelope me-1 text-forest"></i> girlswarden@hitam.org</div>
                        <div class="small text-secondary"><i class="bi bi-telephone me-1 text-forest"></i> +91 92480 09875</div>
                    </div>

                    <div>
                        <div class="fw-bold text-dark">24/7 Security Control & Medical Emergency Desk</div>
                        <div class="small text-muted mb-1">Main Gate Security Post</div>
                        <div class="small text-danger fw-semibold"><i class="bi bi-telephone-outbound me-1"></i> +91 92480 09879 / 08418-204066</div>
                    </div>
                </div>

                <div class="hitam-card p-4 bg-light border-0">
                    <h6 class="fw-bold text-forest mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Campus Physical Location</h6>
                    <p class="small text-muted mb-2">
                        Hyderabad Institute of Technology and Management (HITAM),<br>
                        Gowdavelly (Village), Medchal (Mandal),<br>
                        Medchal-Malkajgiri District, Hyderabad, Telangana — 501401.
                    </p>
                    <div class="small text-secondary">
                        <strong>Directions:</strong> Located just 25 minutes from Kompally, accessible via Outer Ring Road (ORR Exit 6) and Medchal highway.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
