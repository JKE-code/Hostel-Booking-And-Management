@extends('layouts.public')

@section('title', 'Sign In — HITAM Hostel Management Gateway')
@section('meta_description', 'Secure authentication gateway for HITAM Hostel residents, wardens, security gate officers, and root administration.')

@section('content')
<section class="py-5" style="background: radial-gradient(circle at 50% 20%, #064E3B 0%, #022C22 65%, #011C15 100%); min-height: 90vh; display: flex; align-items: center;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <!-- Main Authentication Card -->
                <div class="card border-0 shadow-2xl rounded-4 overflow-hidden" style="background: #FFFFFF; box-shadow: 0 25px 50px -12px rgba(2, 44, 34, 0.45);">
                    <!-- Brand Header Banner -->
                    <div class="p-4 text-white text-center position-relative" style="background: linear-gradient(135deg, #064E3B 0%, #022C22 100%);">
                        <div class="d-inline-flex align-items-center justify-content-center p-2 rounded-3 bg-white mb-2 shadow-sm" style="width: 56px; height: 56px;">
                            <img src="{{ asset('images/hitam-logo.jpg') }}" alt="HITAM" style="max-height: 40px; max-width: 40px;">
                        </div>
                        <h5 class="fw-bold text-white mb-0" style="letter-spacing: -0.02em;">HITAM Residential Portal</h5>
                        <small class="text-white-50" style="font-size: 0.8rem;">Hyderabad Institute of Technology and Management</small>
                    </div>

                    <!-- Sign In Body -->
                    <div class="p-4 p-sm-5">
                        <div class="mb-4 text-center">
                            <h4 class="fw-bold text-dark mb-1">Sign In</h4>
                            <p class="text-muted small mb-0">Enter your institutional email or scholar roll number</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show small py-2 px-3 mb-3 rounded-3" role="alert">
                                <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show small py-2 px-3 mb-3 rounded-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>{{ session('error') }}
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show small py-2 px-3 mb-3 rounded-3" role="alert">
                                <i class="bi bi-x-circle-fill me-2 text-danger"></i>{{ $errors->first() }}
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Clean Enterprise Form -->
                        <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
                            @csrf

                            <!-- Email or Roll Number Input -->
                            <div class="mb-3">
                                <label for="loginInput" class="form-label small fw-semibold text-dark mb-1">Institutional Email or Roll Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-color: #E2E8F0;">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control border-start-0 ps-0 py-2 @error('login') is-invalid @enderror" 
                                           id="loginInput" 
                                           name="login" 
                                           value="{{ old('login') }}" 
                                           placeholder="e.g. roll number or email" 
                                           required 
                                           autofocus 
                                           style="border-color: #E2E8F0; font-size: 0.95rem;">
                                </div>
                            </div>

                            <!-- Password Input with Toggle -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="passwordInput" class="form-label small fw-semibold text-dark mb-0">Password</label>
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none fw-medium" style="color: #064E3B; font-size: 0.8rem;">
                                        Forgot Password?
                                    </a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-color: #E2E8F0;">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0 border-end-0 ps-0 py-2" 
                                           id="passwordInput" 
                                           name="password" 
                                           placeholder="Enter your account password" 
                                           required 
                                           style="border-color: #E2E8F0; font-size: 0.95rem;">
                                    <button class="btn bg-light border border-start-0 text-muted" 
                                            type="button" 
                                            id="togglePasswordBtn" 
                                            onclick="togglePasswordVisibility()" 
                                            style="border-color: #E2E8F0;"
                                            title="Toggle password visibility">
                                        <i class="bi bi-eye" id="passwordIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Remember Me & Assistance -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }} style="accent-color: #064E3B;">
                                    <label class="form-check-label small text-muted" for="rememberMe" style="font-size: 0.85rem;">
                                        Stay signed in
                                    </label>
                                </div>
                                <span class="small text-muted" style="font-size: 0.78rem;">
                                    <i class="bi bi-shield-check text-success me-1"></i>Protected by Brevo & BCrypt
                                </span>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn w-100 py-2 fw-semibold text-white d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm" style="background: #064E3B; transition: all 0.2s ease;">
                                <span>Sign In to Workspace</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </form>

                        <!-- Automatic Role Routing Guidance -->
                        <div class="mt-4 pt-3 border-top text-center">
                            <p class="small text-muted mb-0" style="font-size: 0.8rem;">
                                <i class="bi bi-shield-lock-fill text-muted me-1"></i>
                                Universal Gateway automatically directs you to your authorized portal (Student, Warden, Gate Security, or Administration).
                            </p>
                        </div>

                        <!-- Discreet Demo Accounts Collapsible (For local testing without cluttering interface) -->
                        <div class="mt-3 text-center">
                            <button class="btn btn-sm btn-link text-decoration-none text-muted p-0" type="button" data-bs-toggle="collapse" data-bs-target="#demoAccountsCollapse" style="font-size: 0.75rem;">
                                <i class="bi bi-code-slash me-1"></i>Development Testing Credentials
                            </button>

                            <div class="collapse mt-2" id="demoAccountsCollapse">
                                <div class="p-2 rounded-3 text-start small border bg-light" style="font-size: 0.75rem;">
                                    <div class="d-flex justify-content-between py-1 border-bottom">
                                        <span class="text-muted">Master Admin:</span>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-dark fw-semibold" onclick="fillCredentials('admin@hitam.org', 'admin@hitam123')">admin@hitam.org</button>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom">
                                        <span class="text-muted">Hostel Warden:</span>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-dark fw-semibold" onclick="fillCredentials('warden.boys@hitam.org', 'warden@hitam123')">warden.boys@hitam.org</button>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom">
                                        <span class="text-muted">Security Desk:</span>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-dark fw-semibold" onclick="fillCredentials('security@hitam.org', 'security@hitam123')">security@hitam.org</button>
                                    </div>
                                    <div class="d-flex justify-content-between py-1">
                                        <span class="text-muted">Student Resident:</span>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-dark fw-semibold" onclick="fillCredentials('rahul.sharma@student.hitam.org', 'student@hitam123')">rahul.sharma@student.hitam.org</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Support Note -->
                <div class="text-center mt-3 small text-white-50" style="font-size: 0.75rem;">
                    &copy; {{ date('Y') }} Hyderabad Institute of Technology and Management (HITAM). Residential Affairs.
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('passwordInput');
        const passwordIcon = document.getElementById('passwordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('bi-eye');
            passwordIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('bi-eye-slash');
            passwordIcon.classList.add('bi-eye');
        }
    }

    function fillCredentials(login, password) {
        document.getElementById('loginInput').value = login;
        document.getElementById('passwordInput').value = password;
    }
</script>
@endsection
