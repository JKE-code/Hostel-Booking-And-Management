@extends('layouts.public')

@section('title', 'Frequently Asked Questions (FAQ) — HITAM Hostels')
@section('meta_description', 'Answers to common questions about HITAM hostel admissions, room allotment, fee structure, mess menu, gate curfews, Wi-Fi, laundry, and parent visits.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>FAQ</span>
        </div>
        <h1 class="page-hero-title">Frequently Asked Questions</h1>
        <p class="page-hero-sub">
            Find answers to commonly asked questions regarding admissions, room occupancy, safety regulations, dining plans, leave procedures, and campus amenities.
        </p>
    </div>
</section>

<!-- FAQ Accordion Section -->
<section class="py-5">
    <div class="container py-2">
        <div class="row g-5">
            <div class="col-lg-8">
                <span class="hitam-section-badge">Help & Support</span>
                <h2 class="hitam-section-title">Common Inquiries</h2>
                <p class="text-secondary mb-4">
                    If your question is not addressed here, feel free to submit an inquiry through our Contact page or call the Hostel Helpdesk.
                </p>

                <div class="accordion accordion-hitam" id="hostelFaqAccordion">
                    <!-- FAQ 1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                1. How is hostel room allotment conducted for first-year students?
                            </button>
                        </h2>
                        <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#hostelFaqAccordion">
                            <div class="accordion-body">
                                Hostel room allotments are issued on a first-come, first-served basis following the completion of academic admission at HITAM. Priority is given to outstation scholars arriving from distant districts and interstate regions. Students can select their preferred room configuration (2-sharing, 3-sharing, or 4-sharing) subject to availability.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                2. What are the gate curfew timings for Boys and Girls Hostels?
                            </button>
                        </h2>
                        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#hostelFaqAccordion">
                            <div class="accordion-body">
                                For the Girls Hostel, students must report inside the main campus gates by <strong>06:30 PM</strong> and inside the hostel block by <strong>07:00 PM</strong>. For the Boys Hostel, the block reporting time is <strong>08:30 PM</strong> sharp. Night biometric roll call is carried out between 08:30 PM and 09:30 PM daily. Unexcused absence triggers an automated notification to parents.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                3. What is the procedure for taking weekend leave or going home?
                            </button>
                        </h2>
                        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#hostelFaqAccordion">
                            <div class="accordion-body">
                                Residents must apply for leave digitally via the Student Portal at least 24 hours prior to departure. The residential warden verifies the request by contacting the registered parent or guardian. Once approved, the student logs out biometrically at the security gate turnstile.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                                4. Are electrical appliances allowed inside resident rooms?
                            </button>
                        </h2>
                        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#hostelFaqAccordion">
                            <div class="accordion-body">
                                Students are permitted to use laptops, mobile chargers, study lamps, and standard electric hair dryers. High-wattage heating appliances such as immersion rods, electric heaters, induction stoves, and hotplates are strictly prohibited to prevent fire hazards and power grid overload.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                                5. Can parents or visitors stay overnight at the hostel?
                            </button>
                        </h2>
                        <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#hostelFaqAccordion">
                            <div class="accordion-body">
                                No visitor, parent, or day scholar is allowed inside student dormitory rooms or allowed to stay overnight in resident blocks. For visiting parents from outstation, guest rooms on campus may be reserved in advance through the Chief Warden's Office, subject to availability.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 6 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading6">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
                                6. How is medical assistance handled during emergencies?
                            </button>
                        </h2>
                        <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6" data-bs-parent="#hostelFaqAccordion">
                            <div class="accordion-body">
                                The campus houses a First Aid Dispensary staffed with qualified nursing staff and visited regularly by doctors. In case of emergency during late hours, the residential warden coordinates with our 24/7 on-campus ambulance to immediately transfer the student to our affiliated multi-specialty hospital located 10 minutes away.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 7 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading7">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse7" aria-expanded="false" aria-controls="faqCollapse7">
                                7. Is laundry and ironing service included in the hostel fees?
                            </button>
                        </h2>
                        <div id="faqCollapse7" class="accordion-collapse collapse" aria-labelledby="faqHeading7" data-bs-parent="#hostelFaqAccordion">
                            <div class="accordion-body">
                                Yes, routine commercial laundry and steam pressing are included as part of the residential living package. Scheduled clothing pickups and deliveries are operated twice weekly for each hostel wing.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="hitam-card p-4 mb-4">
                    <h5 class="fw-bold text-forest mb-3">Still Have Questions?</h5>
                    <p class="small text-muted mb-4">
                        Our administrative admissions and pastoral team is available Monday through Saturday to answer specific queries.
                    </p>
                    <a href="{{ route('public.contact') }}" class="btn btn-hitam-green w-100 mb-2">
                        <i class="bi bi-envelope me-1"></i> Send Us an Inquiry
                    </a>
                    <a href="tel:+919248009871" class="btn btn-hitam-outline-green w-100">
                        <i class="bi bi-telephone me-1"></i> Call +91 92480 09871
                    </a>
                </div>

                <div class="hitam-card p-4 bg-light border-0">
                    <h6 class="fw-bold text-forest mb-2">Admissions Office Hours</h6>
                    <p class="small text-muted mb-0">
                        <strong>Mon – Sat:</strong> 08:30 AM – 05:30 PM<br>
                        <strong>Sunday:</strong> 09:30 AM – 01:30 PM (During admission seasons)
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
