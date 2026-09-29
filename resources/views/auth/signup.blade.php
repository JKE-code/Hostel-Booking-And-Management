@extends('layouts.public')

@section('title', 'Student 2-Way Verification & Onboarding — HITAM Hostel Portal')
@section('meta_description', 'Two-way verified onboarding for registered HITAM hostel scholars. Verify warden pre-whitelist, validate OTP, and configure your secure portal access.')

@section('content')
<section class="py-5" style="background: radial-gradient(circle at top right, #0F172A, #064E3B 60%, #022c22 100%); min-height: 88vh; display: flex; align-items: center;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="card border-0 shadow-2xl rounded-4 p-4 p-md-5" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px);">
                    
                    <!-- Wizard Header & Steps -->
                    <div class="text-center mb-4">
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 mb-2 fw-semibold">
                            <i class="bi bi-shield-check me-1"></i> 2-Way Security Verification
                        </span>
                        <h3 class="fw-bold text-forest mb-1">Student Resident Activation</h3>
                        <p class="text-secondary small mb-0">
                            Pre-registered student scholars verify their institutional admission via OTP before configuring portal credentials.
                        </p>
                    </div>

                    <!-- Progress Step Indicator -->
                    <div class="position-relative mb-4 pb-2">
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $step == 1 ? '33%' : ($step == 2 ? '66%' : '100%') }};"></div>
                        </div>
                        <div class="d-flex justify-content-between position-relative" style="margin-top: -14px;">
                            <div class="text-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ $step >= 1 ? 'bg-success text-white' : 'bg-white text-muted border' }}" style="width: 28px; height: 28px; font-size: 0.8rem; font-weight: 700;">
                                    @if($step > 1) <i class="bi bi-check-lg"></i> @else 1 @endif
                                </div>
                                <span class="small fw-semibold mt-1 d-block {{ $step == 1 ? 'text-forest' : 'text-muted' }}" style="font-size: 0.75rem;">Whitelist Lookup</span>
                            </div>
                            <div class="text-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ $step >= 2 ? 'bg-success text-white' : 'bg-white text-muted border' }}" style="width: 28px; height: 28px; font-size: 0.8rem; font-weight: 700;">
                                    @if($step > 2) <i class="bi bi-check-lg"></i> @else 2 @endif
                                </div>
                                <span class="small fw-semibold mt-1 d-block {{ $step == 2 ? 'text-forest' : 'text-muted' }}" style="font-size: 0.75rem;">6-Digit OTP</span>
                            </div>
                            <div class="text-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ $step == 3 ? 'bg-success text-white' : 'bg-white text-muted border' }}" style="width: 28px; height: 28px; font-size: 0.8rem; font-weight: 700;">
                                    3
                                </div>
                                <span class="small fw-semibold mt-1 d-block {{ $step == 3 ? 'text-forest' : 'text-muted' }}" style="font-size: 0.75rem;">Password Setup</span>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show small py-2 px-3 mb-4 rounded-3" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show small py-2 px-3 mb-4 rounded-3" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                            <a href="{{ route('login') }}" class="alert-link ms-2">Click here to Sign In</a>
                            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show small py-2 px-3 mb-4 rounded-3" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-x-circle-fill fs-5 me-2 flex-shrink-0"></i>
                                <div>
                                    <div class="fw-bold">{{ $errors->first() }}</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- ================= STEP 1: WHITELIST LOOKUP ================= -->
                    @if($step == 1)
                        <div class="p-3 bg-light rounded-3 mb-4 border border-secondary-subtle">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle text-primary fs-5 mt-1"></i>
                                <div class="small text-secondary">
                                    <strong>How 2-Way Verification Works:</strong> Your email or phone must first be registered/whitelisted in the hostel registry by the <strong>Hostel Warden</strong>. If present, an OTP is dispatched.
                                </div>
                            </div>
                        </div>

                        <!-- Demo Test Helper -->
                        <div class="card mb-4 border-warning border-opacity-50 rounded-3" style="background: #FFFBEB;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small"><i class="bi bi-flask text-warning me-1"></i>Demo Pre-Whitelisted Scholar Available</span>
                                    <span class="badge bg-warning text-dark">Ready for testing</span>
                                </div>
                                <p class="small text-muted mb-2" style="font-size: 0.78rem;">
                                    The Warden pre-registered scholar <strong>Aditya Varma</strong> (Roll: <code>25HT1A0599</code>, CSE Year 1). Click below to autofill:
                                </p>
                                <button type="button" class="btn btn-sm btn-outline-dark fw-semibold py-1 px-3" onclick="document.getElementById('identifierInput').value = 'aditya.varma@student.hitam.org'">
                                    Use: aditya.varma@student.hitam.org
                                </button>
                            </div>
                        </div>

                        <form action="{{ route('signup.request-otp') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">Institutional Email or Registered Mobile Number</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white text-muted"><i class="bi bi-person-bounding-box"></i></span>
                                    <input type="text" name="identifier" id="identifierInput" class="form-control" placeholder="e.g. aditya.varma@student.hitam.org or 9876543210" value="{{ old('identifier') }}" required autofocus>
                                </div>
                                <div class="form-text text-muted small">
                                    Matches against official hostel enrollment records managed by HITAM Resident Wardens.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-hitam-green w-100 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <span>Verify Admission & Request 6-Digit OTP</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </form>

                    <!-- ================= STEP 2: OTP VERIFICATION ================= -->
                    @elseif($step == 2)
                        <div class="card mb-4 border-success border-opacity-50 rounded-3 p-3" style="background: #F0FDF4;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-success mb-1">Scholar Whitelisted</span>
                                    <h6 class="fw-bold text-dark mb-0">{{ $student?->name ?? 'HITAM Resident Scholar' }}</h6>
                                    <div class="text-muted small">Roll: {{ $student?->roll_number }} • {{ $student?->department }} Dept (Year {{ $student?->year_of_study }})</div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-light text-forest border px-2 py-1">{{ $identifier }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Flashed OTP Notice for Effortless Testing -->
                        @if(session('demo_otp'))
                            <div class="alert alert-warning border-warning d-flex align-items-center justify-content-between p-3 mb-4 rounded-3" style="background: #FFFBEB;">
                                <div>
                                    <div class="fw-bold text-dark small"><i class="bi bi-phone-vibrate me-1 text-warning"></i> Simulated SMS / Email Gateway Delivery</div>
                                    <div class="text-muted small">Your generated OTP code is: <strong class="fs-5 text-dark ms-1 font-monospace">{{ session('demo_otp') }}</strong></div>
                                </div>
                                <button type="button" class="btn btn-sm btn-dark" onclick="document.getElementById('otpInput').value = '{{ session('demo_otp') }}'">
                                    Auto-fill OTP
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('signup.verify-otp') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">Enter 6-Digit Verification Code</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white text-muted"><i class="bi bi-key-fill"></i></span>
                                    <input type="text" name="otp" id="otpInput" class="form-control text-center font-monospace fs-4 tracking-wider" placeholder="••••••" maxlength="6" pattern="\d{6}" required autofocus>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted small">Valid for 15 minutes</span>
                                    <a href="{{ route('signup.reset') }}" class="text-danger small text-decoration-none">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Change Email / Start Over
                                    </a>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-hitam-green w-100 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <span>Confirm OTP Code</span>
                                <i class="bi bi-shield-check"></i>
                            </button>
                        </form>

                    <!-- ================= STEP 3: PASSWORD SETUP ================= -->
                    @elseif($step == 3)
                        <div class="card mb-4 border-success border-opacity-50 rounded-3 p-3" style="background: #F0FDF4;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-success text-white p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check-lg fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">Identity Verified: {{ $student?->name }}</div>
                                    <div class="text-muted small">Account: {{ $student?->email }} • Admission Roll: {{ $student?->roll_number }}</div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('signup.complete') }}" method="POST">
                            @csrf
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark small">Create Portal Password *</label>
                                    <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required minlength="8">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark small">Confirm Password *</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type password" required minlength="8">
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded-3 mb-4 small text-muted">
                                <i class="bi bi-shield-lock-fill text-success me-1"></i>
                                Your password is automatically hashed with <strong>BCrypt (12 cost rounds)</strong> matching the institutional security policy.
                            </div>

                            <button type="submit" class="btn btn-hitam-green w-100 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <span>Activate Account & Enter Resident Portal</span>
                                <i class="bi bi-check-circle-fill"></i>
                            </button>
                        </form>
                    @endif

                    <div class="text-center mt-4 pt-3 border-top">
                        <span class="small text-muted">Already configured your account?</span>
                        <a href="{{ route('login') }}" class="small fw-bold text-forest text-decoration-none ms-1">Sign In to Portal</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
