@extends('layouts.public')

@section('title', 'Set New Password — HITAM Hostel Management Gateway')
@section('meta_description', 'Verify 6-digit Brevo OTP and choose a new secure password for your HITAM Hostel account.')

@section('content')
<section class="py-5" style="background: radial-gradient(circle at 50% 20%, #064E3B 0%, #022C22 65%, #011C15 100%); min-height: 90vh; display: flex; align-items: center;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <!-- Main Reset Card -->
                <div class="card border-0 shadow-2xl rounded-4 overflow-hidden" style="background: #FFFFFF; box-shadow: 0 25px 50px -12px rgba(2, 44, 34, 0.45);">
                    <!-- Brand Header Banner -->
                    <div class="p-4 text-white text-center position-relative" style="background: linear-gradient(135deg, #064E3B 0%, #022C22 100%);">
                        <div class="d-inline-flex align-items-center justify-content-center p-2 rounded-3 bg-white mb-2 shadow-sm" style="width: 56px; height: 56px;">
                            <img src="{{ asset('images/hitam-logo.jpg') }}" alt="HITAM" style="max-height: 40px; max-width: 40px;">
                        </div>
                        <h5 class="fw-bold text-white mb-0" style="letter-spacing: -0.02em;">Authorize Password Change</h5>
                        <small class="text-white-50" style="font-size: 0.8rem;">Brevo 6-Digit One-Time Verification</small>
                    </div>

                    <!-- Reset Form Body -->
                    <div class="p-4 p-sm-5">
                        <div class="mb-4 text-center">
                            <h4 class="fw-bold text-dark mb-1">Verify Code & Reset</h4>
                            <p class="text-muted small mb-0">
                                Enter the 6-digit verification code sent to:
                                <br><span class="fw-semibold text-dark font-monospace">{{ substr($email, 0, 3) . '***@' . explode('@', $email)[1] }}</span>
                            </p>
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

                        @if(session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show small py-2 px-3 mb-3 rounded-3" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2 text-warning-emphasis"></i>{{ session('warning') }}
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show small py-2 px-3 mb-3 rounded-3" role="alert">
                                <i class="bi bi-x-circle-fill me-2 text-danger"></i>{{ $errors->first() }}
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf

                            <!-- 6-Digit OTP Input -->
                            <div class="mb-3">
                                <label for="otpCodeInput" class="form-label small fw-semibold text-dark mb-1">6-Digit Verification Code *</label>
                                <input type="text" 
                                       class="form-control text-center font-monospace py-2 @error('otp_code') is-invalid @enderror" 
                                       id="otpCodeInput" 
                                       name="otp_code" 
                                       maxlength="6" 
                                       placeholder="123456" 
                                       required 
                                       autofocus 
                                       style="border-color: #064E3B; font-size: 1.5rem; letter-spacing: 0.35rem; font-weight: 700; color: #064E3B; background: #F0FDF4;">
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-muted" style="font-size: 0.75rem;">Valid for 15 minutes</small>
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none" style="color: #064E3B; font-size: 0.75rem;">
                                        Resend Code?
                                    </a>
                                </div>
                            </div>

                            <!-- New Password Input -->
                            <div class="mb-3">
                                <label for="newPassword" class="form-label small fw-semibold text-dark mb-1">New Password (Min 8 Characters) *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-color: #E2E8F0;">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0 ps-0 py-2 @error('password') is-invalid @enderror" 
                                           id="newPassword" 
                                           name="password" 
                                           placeholder="Enter new strong password" 
                                           required 
                                           style="border-color: #E2E8F0; font-size: 0.95rem;">
                                </div>
                            </div>

                            <!-- Confirm Password Input -->
                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label small fw-semibold text-dark mb-1">Confirm New Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-color: #E2E8F0;">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control border-start-0 ps-0 py-2" 
                                           id="confirmPassword" 
                                           name="password_confirmation" 
                                           placeholder="Re-enter new password" 
                                           required 
                                           style="border-color: #E2E8F0; font-size: 0.95rem;">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn w-100 py-2 fw-semibold text-white d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm mb-3" style="background: #064E3B; transition: all 0.2s ease;">
                                <span>Update Password & Proceed</span>
                                <i class="bi bi-shield-check"></i>
                            </button>

                            <div class="text-center">
                                <a href="{{ route('login') }}" class="small text-decoration-none fw-medium text-muted" style="font-size: 0.85rem;">
                                    Cancel & Return to Sign In
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
