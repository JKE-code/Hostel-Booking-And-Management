@extends('layouts.public')

@section('title', 'Resident Scholar Registration — HITAM Hostel Portal')
@section('meta_description', 'Register a new hostel resident profile at Hyderabad Institute of Technology and Management.')

@section('content')
<section class="py-5" style="background: linear-gradient(135deg, #021B0F 0%, #064E3B 60%, #022c22 100%); min-height: 85vh; display: flex; align-items: center;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-2xl rounded-4 p-3 p-sm-4 p-md-5" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px);">
                    <div class="text-center mb-4">
                        <span class="hitam-section-badge mb-2">Admissions & Onboarding</span>
                        <h3 class="fw-bold text-forest mb-1">Resident Student Registration</h3>
                        <p class="text-secondary small">
                            Create your resident profile to manage room allocation, view mess schedules, and submit digital outpass requests.
                        </p>
                    </div>

                    <form action="{{ route('student.dashboard') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Full Name *</label>
                                <input type="text" class="form-control" placeholder="e.g. Rahul Sharma" value="Rahul Sharma" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">College Roll Number *</label>
                                <input type="text" class="form-control" placeholder="e.g. 23HT1A0501" value="23HT1A0501" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Institutional Email *</label>
                                <input type="email" class="form-control" placeholder="rahul@hitam.org" value="rahul.23@hitam.org" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Mobile Number *</label>
                                <input type="tel" class="form-control" placeholder="+91 98765 43210" value="+91 98765 43210" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Select Hostel Wing *</label>
                                <select class="form-select" required>
                                    <option value="boys" selected>Boys Residential Block</option>
                                    <option value="girls">Girls Residential Block</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Academic Year / Branch *</label>
                                <select class="form-select" required>
                                    <option value="cse" selected>B.Tech CSE - 2nd Year</option>
                                    <option value="ece">B.Tech ECE - 2nd Year</option>
                                    <option value="mech">B.Tech Mechanical - 2nd Year</option>
                                    <option value="first">B.Tech - 1st Year (All Branches)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Parent / Guardian Phone *</label>
                                <input type="tel" class="form-control" placeholder="+91 91234 56789" value="+91 91234 56789" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Blood Group</label>
                                <input type="text" class="form-control" placeholder="O+ / B+ / A+" value="O+">
                            </div>

                            <div class="col-12 mt-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="termsCheck" checked required>
                                    <label class="form-check-label small text-muted" for="termsCheck">
                                        I agree to abide by the <a href="{{ route('public.rules') }}" target="_blank" class="text-forest fw-semibold">HITAM Residential Code of Conduct</a> and zero-tolerance anti-ragging regulations.
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-hitam-green w-100 py-2 fw-bold d-flex justify-content-center align-items-center gap-2 shadow-sm">
                                    <span>Complete Registration & Enter Portal</span>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <span class="small text-muted">Already registered as a resident?</span>
                        <a href="{{ route('login') }}" class="small fw-bold text-forest text-decoration-none ms-1">Sign In to Existing Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
