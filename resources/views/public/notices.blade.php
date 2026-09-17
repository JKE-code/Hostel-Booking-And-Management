@extends('layouts.public')

@section('title', 'Notices, Circulars & Announcements — HITAM Hostels')
@section('meta_description', 'Latest official circulars, hostel allotment notifications, holiday schedules, mess committee updates, and administrative announcements from HITAM Hostels.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>Notices</span>
        </div>
        <h1 class="page-hero-title">Circulars & Announcements</h1>
        <p class="page-hero-sub">
            Stay updated with official administrative notices, fee timelines, holiday gate guidelines, and hostel maintenance circulars.
        </p>
    </div>
</section>

<!-- Notices Board Section -->
<section class="py-5">
    <div class="container py-2">
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Filter Pills -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <button class="btn btn-sm btn-hitam-green">All Circulars</button>
                    <button class="btn btn-sm btn-hitam-outline-green">Hostel Allotment</button>
                    <button class="btn btn-sm btn-hitam-outline-green">Mess Committee</button>
                    <button class="btn btn-sm btn-hitam-outline-green">Holidays & Vacations</button>
                    <button class="btn btn-sm btn-hitam-outline-green">Maintenance</button>
                </div>

                <!-- Notices List Card -->
                <div class="hitam-card overflow-hidden">
                    <!-- Notice 1 -->
                    <div class="notice-row">
                        <div class="d-flex align-items-start gap-3">
                            <div class="notice-date-box">
                                <div class="notice-date-day">14</div>
                                <div class="notice-date-month">Sep</div>
                            </div>
                            <div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle mb-1">General Administrative</span>
                                <h5 class="fw-bold text-forest mb-1">
                                    Hostel Re-Registration & Room Renewal Guidelines for Academic Year 2026-27
                                </h5>
                                <p class="small text-muted mb-0">
                                    Current residential scholars in 2nd and 3rd year are requested to clear all mess dues and submit their preference forms before October 5.
                                </p>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-light border text-forest ms-2 text-nowrap"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
                    </div>

                    <!-- Notice 2 -->
                    <div class="notice-row">
                        <div class="d-flex align-items-start gap-3">
                            <div class="notice-date-box">
                                <div class="notice-date-day">10</div>
                                <div class="notice-date-month">Sep</div>
                            </div>
                            <div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-1">Mess Committee</span>
                                <h5 class="fw-bold text-forest mb-1">
                                    Special Feast Menu for Ganesh Chaturthi & Monthly Feedback Meeting
                                </h5>
                                <p class="small text-muted mb-0">
                                    The Student Mess Committee invites suggestions for the festive dinner scheduled for this weekend.
                                </p>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-light border text-forest ms-2 text-nowrap"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
                    </div>

                    <!-- Notice 3 -->
                    <div class="notice-row">
                        <div class="d-flex align-items-start gap-3">
                            <div class="notice-date-box">
                                <div class="notice-date-day">04</div>
                                <div class="notice-date-month">Sep</div>
                            </div>
                            <div>
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle mb-1">Maintenance Notice</span>
                                <h5 class="fw-bold text-forest mb-1">
                                    Scheduled Water Tank Cleaning & Solar Geyser Servicing (Boys Block A & B)
                                </h5>
                                <p class="small text-muted mb-0">
                                    Routine semi-annual water reservoir sanitization will be conducted on Saturday between 10:00 AM and 01:00 PM. Alternative storage is arranged.
                                </p>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-light border text-forest ms-2 text-nowrap"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
                    </div>

                    <!-- Notice 4 -->
                    <div class="notice-row">
                        <div class="d-flex align-items-start gap-3">
                            <div class="notice-date-box">
                                <div class="notice-date-day">28</div>
                                <div class="notice-date-month">Aug</div>
                            </div>
                            <div>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-1">Discipline & Welfare</span>
                                <h5 class="fw-bold text-forest mb-1">
                                    Compliance with Mandatory Night Biometric Attendance Timings
                                </h5>
                                <p class="small text-muted mb-0">
                                    All students are reminded that missing nightly biometric roll call without approved outpass attracts automated SMS notifications to parents.
                                </p>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-light border text-forest ms-2 text-nowrap"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
                    </div>

                    <!-- Notice 5 -->
                    <div class="notice-row">
                        <div class="d-flex align-items-start gap-3">
                            <div class="notice-date-box">
                                <div class="notice-date-day">20</div>
                                <div class="notice-date-month">Aug</div>
                            </div>
                            <div>
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle mb-1">Sports & Recreation</span>
                                <h5 class="fw-bold text-forest mb-1">
                                    Registration for HITAM Inter-Hostel Badminton & Table Tennis Tournament 2026
                                </h5>
                                <p class="small text-muted mb-0">
                                    Inter-block indoor sports tournament registrations are now open for all resident students. Trophies and certificates for winners.
                                </p>
                            </div>
                        </div>
                        <a href="#" class="btn btn-sm btn-light border text-forest ms-2 text-nowrap"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="hitam-card p-4 mb-4">
                    <h5 class="fw-bold text-forest mb-3">Notice Archives</h5>
                    <ul class="list-unstyled small mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <a href="#" class="text-decoration-none text-forest">September 2026</a>
                            <span class="badge bg-light text-muted">3 Notices</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <a href="#" class="text-decoration-none text-forest">August 2026</a>
                            <span class="badge bg-light text-muted">5 Notices</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <a href="#" class="text-decoration-none text-forest">July 2026</a>
                            <span class="badge bg-light text-muted">8 Notices</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <a href="#" class="text-decoration-none text-forest">June 2026</a>
                            <span class="badge bg-light text-muted">4 Notices</span>
                        </li>
                    </ul>
                </div>

                <div class="hitam-card p-4 bg-light border-0">
                    <h5 class="fw-bold text-forest mb-2">Notice SMS Alerts</h5>
                    <p class="small text-muted mb-3">
                        Emergency circulars, holiday gate advisories, and weather warnings are dispatched via SMS to all registered student and parent numbers.
                    </p>
                    <div class="small text-secondary">
                        <i class="bi bi-info-circle me-1 text-primary"></i> Keep your contact details updated on the Student Portal.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
