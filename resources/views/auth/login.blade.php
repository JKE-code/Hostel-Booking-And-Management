@extends('layouts.public')

@section('title', 'Portal Access & Sign In — HITAM Hostel Management')
@section('meta_description', 'Sign in to the HITAM Hostel Portal. Direct access for resident students, wardens, caretakers, and administrative staff.')

@section('content')
<section class="py-5" style="background: linear-gradient(135deg, #021B0F 0%, #064E3B 50%, #022c22 100%); min-height: 80vh; display: flex; align-items: center;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="card border-0 shadow-2xl rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px);">
                    <div class="row g-0">
                        <!-- Left Brand & Context Column -->
                        <div class="col-md-5 auth-brand-pane p-3 p-sm-4 p-lg-5 text-white d-flex flex-column justify-content-between" style="background: linear-gradient(165deg, #064E3B 0%, #022c22 100%);">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-3 mb-md-4">
                                    <img src="{{ asset('images/hitam-logo.jpg') }}" alt="HITAM" class="rounded-2 bg-white p-1" style="height: 42px;">
                                    <div>
                                        <div class="fw-bold fs-6 lh-1 text-white">HITAM HOSTELS</div>
                                        <small class="text-white-50 text-uppercase tracking-wider" style="font-size: 0.65rem;">Residential Services</small>
                                    </div>
                                </div>
                                <h3 class="fw-bold text-white mb-2 mb-md-3">Residential Life Portal</h3>
                                <p class="text-white-50 small mb-3 mb-md-4 d-none d-sm-block">
                                    Unified access gateway for registered hostel scholars, resident wardens, and campus estate administration.
                                </p>
                                
                                <div class="p-2 p-sm-3 rounded-3 mb-3" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15);">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-info-circle-fill text-warning"></i>
                                        <span class="fw-bold small text-white">Preview Access Mode</span>
                                    </div>
                                    <p class="small text-white-50 mb-0" style="font-size: 0.78rem;">
                                        Authentication gateways are decoupled during this preview phase. Click either direct portal button to explore the interface.
                                    </p>
                                </div>
                            </div>

                            <div class="pt-3 pt-md-4 border-top border-white border-opacity-10 small text-white-50 d-none d-sm-block">
                                <div><i class="bi bi-shield-check text-success me-1"></i> HITAM Security Monitored Gateway</div>
                            </div>
                        </div>

                        <!-- Right Login Form Column -->
                        <div class="col-md-7 auth-form-pane p-3 p-sm-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-bold text-forest mb-0">Sign In to Account</h4>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">V1 Live Preview</span>
                            </div>

                            <!-- Fast-Track Portal Switcher Tabs -->
                            <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded-3" id="loginTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-bold py-2 text-forest" id="student-tab" data-bs-toggle="pill" data-bs-target="#student-login" type="button" role="tab">
                                        <i class="bi bi-mortarboard me-1"></i> Student Resident
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold py-2 text-forest" id="admin-tab" data-bs-toggle="pill" data-bs-target="#admin-login" type="button" role="tab">
                                        <i class="bi bi-shield-lock me-1"></i> Warden & Admin
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="loginTabContent">
                                <!-- Student Login Tab -->
                                <div class="tab-pane fade show active" id="student-login" role="tabpanel">
                                    <form action="{{ route('student.dashboard') }}" method="GET">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-dark">Roll Number / Student ID</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                                <input type="text" class="form-control border-start-0" placeholder="e.g. 23HT1A0501" value="23HT1A0501">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <label class="form-label small fw-semibold text-dark">Password</label>
                                                <a href="#" class="small text-muted text-decoration-none">Forgot password?</a>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                                <input type="password" class="form-control border-start-0" value="password" placeholder="••••••••">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                                                <label class="form-check-label small text-muted" for="rememberMe">Remember me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-hitam-green w-100 py-2 fw-bold d-flex justify-content-center align-items-center gap-2 shadow-sm">
                                            <span>Enter Student Portal</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                    </form>
                                    <div class="text-center mt-3">
                                        <span class="small text-muted">New resident scholar?</span>
                                        <a href="{{ route('signup') }}" class="small fw-bold text-forest text-decoration-none ms-1">Register New Account</a>
                                    </div>
                                </div>

                                <!-- Admin Login Tab -->
                                <div class="tab-pane fade" id="admin-login" role="tabpanel">
                                    <form action="{{ route('admin.dashboard') }}" method="GET">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-dark">Staff / Warden Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                                <input type="email" class="form-control border-start-0" placeholder="warden@hitam.org" value="warden@hitam.org">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <label class="form-label small fw-semibold text-dark">Admin Password</label>
                                                <a href="#" class="small text-muted text-decoration-none">Reset credentials</a>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                                <input type="password" class="form-control border-start-0" value="adminpassword" placeholder="••••••••">
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="adminSecure" checked>
                                                <label class="form-check-label small text-muted" for="adminSecure">Enforce 2FA session verification</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold d-flex justify-content-center align-items-center gap-2 shadow-sm" style="background-color: #064E3B; border-color: #064E3B;">
                                            <span>Enter Warden Admin Panel</span>
                                            <i class="bi bi-shield-lock"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
