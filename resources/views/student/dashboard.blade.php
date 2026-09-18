@extends('layouts.student')

@section('title', 'Resident Dashboard — Rahul Sharma | HITAM Hostels')
@section('page_title', 'Resident Student Dashboard')

@section('student_content')
<!-- Top Resident Welcome & Room Status Hero -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #064E3B 0%, #022c22 100%);">
            <div class="position-relative" style="z-index: 2;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-white text-forest fw-bold px-2 py-1">Active Resident</span>
                    <span class="text-white-50 small">Roll No: 23HT1A0501</span>
                </div>
                <h3 class="fw-bold mb-1">Good Morning, Rahul Sharma</h3>
                <p class="text-white-50 small mb-4" style="max-width: 500px;">
                    Boys Residential Block • Room B-204 (2-Sharing) • Bed 01
                </p>

                <div class="row g-3 pt-3 border-top border-white border-opacity-10 text-start student-hero-stat-row">
                    <div class="col-12 col-sm-4">
                        <div class="small text-white-50">Evening Gate In-Time</div>
                        <div class="fw-bold fs-6 text-white">08:30 PM</div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="small text-white-50">Biometric Roll Call</div>
                        <div class="fw-bold fs-6 text-success-emphasis text-white">09:00 PM – 09:30 PM</div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="small text-white-50">Active Leave Status</div>
                        <div class="fw-bold fs-6 text-white"><span class="badge bg-light text-forest">No Active Outpass</span></div>
                    </div>
                </div>
            </div>
            <div class="position-absolute end-0 bottom-0 opacity-10 p-3" style="font-size: 8rem; line-height: 1;">
                <i class="bi bi-building"></i>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-lightning-charge text-forest me-2"></i>Quick Actions</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('student.leaves') }}" class="btn btn-outline-success text-start d-flex justify-content-between align-items-center py-2 px-3 rounded-3">
                    <div>
                        <div class="fw-semibold text-forest">Apply for Outpass / Leave</div>
                        <small class="text-muted" style="font-size: 0.75rem;">Weekend or home travel permission</small>
                    </div>
                    <i class="bi bi-arrow-right text-forest"></i>
                </a>

                <a href="{{ route('student.complaints') }}" class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center py-2 px-3 rounded-3">
                    <div>
                        <div class="fw-semibold text-dark">Raise Room Maintenance</div>
                        <small class="text-muted" style="font-size: 0.75rem;">Plumbing, electrical, or Wi-Fi ticket</small>
                    </div>
                    <i class="bi bi-tools text-muted"></i>
                </a>

                <a href="{{ route('student.mess') }}" class="btn btn-outline-secondary text-start d-flex justify-content-between align-items-center py-2 px-3 rounded-3">
                    <div>
                        <div class="fw-semibold text-dark">View Today's Dining Menu</div>
                        <small class="text-muted" style="font-size: 0.75rem;">Breakfast, lunch, snacks & dinner</small>
                    </div>
                    <i class="bi bi-cup-hot text-muted"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Room Details & Active Tickets Row -->
<div class="row g-4 mb-4">
    <!-- Room & Roommate Information -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-door-open text-forest me-2"></i>My Room & Allocation</h5>
                <span class="badge bg-success-subtle text-success border border-success-subtle">Allocated</span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <div class="p-3 bg-light rounded-3">
                        <small class="text-muted d-block">Room Number</small>
                        <span class="fw-bold text-dark fs-5">B-204</span>
                        <span class="badge bg-white text-forest border ms-2 small">2nd Floor</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 bg-light rounded-3">
                        <small class="text-muted d-block">Allocated Bed</small>
                        <span class="fw-bold text-dark fs-5">Bed 01</span>
                        <span class="badge bg-white text-muted border ms-2 small">Window Side</span>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Roommate Details</h6>
            <div class="p-3 border rounded-3 d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-secondary-subtle text-secondary fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        AK
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Aditya Kumar</div>
                        <small class="text-muted">B.Tech ECE • 2nd Year (Bed 02)</small>
                    </div>
                </div>
                <span class="badge bg-light text-muted border">Resident</span>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-between gap-1 small text-muted pt-2 border-top">
                <span>Warden: Mr. K. Sreenivasulu</span>
                <span>Caretaker Contact: +91 92480 09874</span>
            </div>
        </div>
    </div>

    <!-- Recent Outpass & Tickets -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-forest me-2"></i>Recent Outpass Activity</h5>
                <a href="{{ route('student.leaves') }}" class="small fw-semibold text-forest text-decoration-none">View All</a>
            </div>

            <!-- Ticket 1 -->
            <div class="p-3 border rounded-3 mb-2 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <div>
                    <div class="fw-bold text-dark mb-1">Weekend Home Visit</div>
                    <small class="text-muted">12 Sep 2026 – 14 Sep 2026 • Destination: Warangal</small>
                </div>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Completed</span>
            </div>

            <!-- Ticket 2 -->
            <div class="p-3 border rounded-3 mb-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <div>
                    <div class="fw-bold text-dark mb-1">Medical Consultation Leave</div>
                    <small class="text-muted">28 Aug 2026 • 02:00 PM to 06:00 PM</small>
                </div>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Approved</span>
            </div>

            <h6 class="fw-bold text-dark mb-2 mt-4"><i class="bi bi-tools text-forest me-1"></i>Active Maintenance Ticket</h6>
            <div class="p-3 border rounded-3 bg-light d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <div>
                    <div class="fw-bold text-dark">Study Desk Light Socket Replacement</div>
                    <small class="text-muted">Ticket #TCK-2081 • Raised on 16 Sep 2026</small>
                </div>
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">In Progress</span>
            </div>
        </div>
    </div>
</div>
@endsection
