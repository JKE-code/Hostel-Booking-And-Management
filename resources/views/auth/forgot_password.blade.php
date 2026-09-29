@extends('layouts.public')

@section('title', 'Forgot Password — HITAM Hostel Management Gateway')
@section('meta_description', 'Recover your HITAM Hostel account password via Brevo 6-digit OTP verification.')

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
                        <h5 class="fw-bold text-white mb-0" style="letter-spacing: -0.02em;">Password Recovery</h5>
                        <small class="text-white-50" style="font-size: 0.8rem;">Brevo Secure 6-Digit OTP Verification</small>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 p-sm-5">
                        <div class="mb-4 text-center">
                            <h4 class="fw-bold text-dark mb-1">Forgot Password?</h4>
                            <p class="text-muted small mb-0">Enter your registered institutional email or scholar roll number to receive a 6-digit verification code.</p>
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

                        <!-- OTP Request Form -->
                        <form action="{{ route('password.send-otp') }}" method="POST">
                            @csrf

                            <!-- Email or Roll Number Input -->
                            <div class="mb-4">
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
                                           placeholder="e.g. 24HT1A0589 or name@hitam.org" 
                                           required 
                                           autofocus 
                                           style="border-color: #E2E8F0; font-size: 0.95rem;">
                                </div>
                                <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">
                                    <i class="bi bi-shield-check text-success me-1"></i>A 6-digit OTP will be dispatched to your registered institutional mailbox.
                                </small>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn w-100 py-2 fw-semibold text-white d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm mb-3" style="background: #064E3B; transition: all 0.2s ease;">
                                <span>Send Verification Code</span>
                                <i class="bi bi-send-fill"></i>
                            </button>

                            <div class="text-center">
                                <a href="{{ route('login') }}" class="small text-decoration-none fw-medium" style="color: #064E3B; font-size: 0.85rem;">
                                    <i class="bi bi-arrow-left me-1"></i>Back to Sign In
                                </a>
                            </div>
                        </form>

                        <div class="mt-4 pt-3 border-top text-center">
                            <p class="small text-muted mb-0" style="font-size: 0.78rem;">
                                <i class="bi bi-info-circle me-1"></i>
                                Only registered hostel residents, wardens, or staff can reset passwords. If your email is not found, please contact the hostel warden.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
