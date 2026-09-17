@extends('layouts.public')

@section('title', 'Official Downloads, Forms & Handbooks — HITAM Hostels')
@section('meta_description', 'Download official HITAM hostel admission forms, room allotment applications, anti-ragging affidavits, leave forms, medical certificates, and mess rulebooks.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>Downloads</span>
        </div>
        <h1 class="page-hero-title">Forms & Document Downloads</h1>
        <p class="page-hero-sub">
            Access official PDF applications, procedural undertakings, rule handbooks, and medical clearance certificates issued by HITAM Residential Administration.
        </p>
    </div>
</section>

<!-- Documents Section -->
<section class="py-5">
    <div class="container py-2">
        <div class="row g-4">
            <div class="col-lg-8">
                <span class="hitam-section-badge">Documentation</span>
                <h2 class="hitam-section-title">Official Student Forms Repository</h2>
                <p class="text-secondary mb-4">
                    Download and complete the required forms. Printed forms along with necessary signatures can be submitted at the Chief Warden's Office during working hours.
                </p>

                <!-- Document List Card -->
                <div class="hitam-card overflow-hidden">
                    <!-- Doc 1 -->
                    <div class="notice-row">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle text-success p-3 rounded text-center" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-file-earmark-pdf fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-forest mb-1">Hostel Admission & Room Allotment Application Form 2026-27</h6>
                                <div class="small text-muted">Primary application form for new incoming first-year & lateral entry scholars. (File size: 340 KB)</div>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-hitam-outline-green text-nowrap"><i class="bi bi-download me-1"></i> Download</a>
                    </div>

                    <!-- Doc 2 -->
                    <div class="notice-row">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle text-success p-3 rounded text-center" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-file-earmark-pdf fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-forest mb-1">Mandatory Anti-Ragging Student & Parent Undertaking (Affidavit)</h6>
                                <div class="small text-muted">Statutory compliance affidavit to be submitted at the time of hostel registration. (File size: 190 KB)</div>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-hitam-outline-green text-nowrap"><i class="bi bi-download me-1"></i> Download</a>
                    </div>

                    <!-- Doc 3 -->
                    <div class="notice-row">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle text-success p-3 rounded text-center" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-file-earmark-pdf fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-forest mb-1">Residential Code of Conduct & Rulebook 2026 Edition</h6>
                                <div class="small text-muted">Comprehensive rulebook outlining gate timings, disciplinary measures, and penalties. (File size: 620 KB)</div>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-hitam-outline-green text-nowrap"><i class="bi bi-download me-1"></i> Download</a>
                    </div>

                    <!-- Doc 4 -->
                    <div class="notice-row">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle text-success p-3 rounded text-center" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-file-earmark-pdf fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-forest mb-1">Medical Fitness & Chronic Health Declaration Certificate</h6>
                                <div class="small text-muted">To be certified by a registered Medical Practitioner (MBBS) before room occupancy. (File size: 210 KB)</div>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-hitam-outline-green text-nowrap"><i class="bi bi-download me-1"></i> Download</a>
                    </div>

                    <!-- Doc 5 -->
                    <div class="notice-row">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle text-success p-3 rounded text-center" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-file-earmark-pdf fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-forest mb-1">Physical Leave & Extended Vacation Outpass Requisition Slip</h6>
                                <div class="small text-muted">Paper backup slip for offline verification when internet service is offline. (File size: 140 KB)</div>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-hitam-outline-green text-nowrap"><i class="bi bi-download me-1"></i> Download</a>
                    </div>

                    <!-- Doc 6 -->
                    <div class="notice-row">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success-subtle text-success p-3 rounded text-center" style="width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-file-earmark-pdf fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-forest mb-1">Caution Deposit Refund & No-Dues Clearance Proforma</h6>
                                <div class="small text-muted">Mandatory clearance form for graduating students and hostellers vacating rooms. (File size: 280 KB)</div>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-hitam-outline-green text-nowrap"><i class="bi bi-download me-1"></i> Download</a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="hitam-card p-4 mb-4">
                    <h5 class="fw-bold text-forest mb-3">Submission Checklist</h5>
                    <ul class="list-unstyled small text-secondary mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>2 Passport size recent color photographs</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>College Admission Allotment Order copy</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Aadhaar Card copy (Student & Parent)</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Hostel Fee payment bank receipt</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Signed Anti-Ragging Undertaking</li>
                    </ul>
                </div>

                <div class="hitam-card p-4 bg-light border-0">
                    <h5 class="fw-bold text-forest mb-2">Prefer Digital Workflow?</h5>
                    <p class="small text-muted mb-3">
                        Enrolled students can submit outpasses, room complaints, and maintenance tickets online without paper forms.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-hitam-green w-100">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Student Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
