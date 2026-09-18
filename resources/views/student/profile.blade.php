@extends('layouts.student')

@section('title', 'Resident Scholar Profile — Rahul Sharma')
@section('page_title', 'Resident Scholar Profile')

@section('student_content')
<div class="row g-4">
    <!-- Left: Profile Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold mx-auto mb-3" style="width: 80px; height: 80px; font-size: 1.8rem;">
                RS
            </div>
            <h4 class="fw-bold text-dark mb-1">Rahul Sharma</h4>
            <div class="badge bg-success-subtle text-success border border-success-subtle mb-2">Verified Resident Scholar</div>
            <p class="small text-muted mb-4">Roll Number: 23HT1A0501 • B.Tech CSE</p>

            <div class="text-start border-top pt-3">
                <div class="mb-2 small">
                    <span class="text-muted d-block">Hostel Wing</span>
                    <span class="fw-semibold text-dark">Boys Residential Block (Block B)</span>
                </div>
                <div class="mb-2 small">
                    <span class="text-muted d-block">Room & Bed</span>
                    <span class="fw-semibold text-dark">Room B-204 • Bed 01</span>
                </div>
                <div class="small">
                    <span class="text-muted d-block">Biometric Access ID</span>
                    <span class="fw-semibold text-dark">BIO-HT-88410</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Personal & Guardian Details -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person text-forest me-2"></i>Personal & Academic Information</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-0">Email Address</label>
                    <div class="fw-semibold text-dark">rahul.23@hitam.org</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-0">Phone Number</label>
                    <div class="fw-semibold text-dark">+91 98765 43210</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-0">Branch & Year</label>
                    <div class="fw-semibold text-dark">Computer Science & Engineering (2nd Year)</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-0">Blood Group</label>
                    <div class="fw-semibold text-dark">O+ Positive</div>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-people text-forest me-2"></i>Parent & Emergency Contact</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-0">Father / Guardian Name</label>
                    <div class="fw-semibold text-dark">Mr. Rajesh Sharma</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-0">Parent Mobile Number (Outpass Verification)</label>
                    <div class="fw-semibold text-success"><i class="bi bi-check-circle-fill me-1"></i>+91 91234 56789</div>
                </div>
                <div class="col-12">
                    <label class="form-label small text-muted mb-0">Permanent Home Address</label>
                    <div class="fw-semibold text-dark">H.No 4-12, Subedari Colony, Hanamkonda, Warangal, Telangana - 506001</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
