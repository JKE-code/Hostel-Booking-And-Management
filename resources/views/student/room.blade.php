@extends('layouts.student')

@section('title', 'My Room & Allocation — Rahul Sharma')
@section('page_title', 'My Room & Allocation')

@section('student_content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle mb-1">Boys Hostel • Block B</span>
                    <h4 class="fw-bold text-forest mb-0">Room B-204 (Second Floor)</h4>
                </div>
                <span class="badge bg-light text-forest border p-2">Double Sharing (Tier 1)</span>
            </div>

            <!-- Room Photo Banner Slot -->
            <div class="rounded-3 overflow-hidden position-relative mb-4" style="height: 240px; background: url('{{ asset('images/student-room.jpg') }}') center/cover no-repeat;">
                <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(to top, rgba(2,27,15,0.85) 0%, transparent 100%);">
                    <span class="badge bg-success text-white">Window View</span>
                    <h5 class="text-white fw-bold mb-0">Furnished Double Occupancy Suite</h5>
                </div>
            </div>

            <h5 class="fw-bold text-dark mb-3">Room Inventory & Fixtures</h5>
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3 text-center">
                        <i class="bi bi-lamp fs-4 text-forest d-block mb-1"></i>
                        <span class="small fw-semibold text-dark">2 Study Desks</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3 text-center">
                        <i class="bi bi-box fs-4 text-forest d-block mb-1"></i>
                        <span class="small fw-semibold text-dark">2 Steel Lockers</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3 text-center">
                        <i class="bi bi-sun fs-4 text-forest d-block mb-1"></i>
                        <span class="small fw-semibold text-dark">Solar Hot Water</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 bg-light rounded-3 text-center">
                        <i class="bi bi-wifi fs-4 text-forest d-block mb-1"></i>
                        <span class="small fw-semibold text-dark">Gigabit Wi-Fi</span>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold text-dark mb-3">Current Roommates</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light">
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-success text-white">Bed 01 (You)</span>
                            <span class="text-muted small">Window Side</span>
                        </div>
                        <h6 class="fw-bold text-dark mt-2 mb-0">Rahul Sharma</h6>
                        <small class="text-muted">B.Tech CSE • 2nd Year</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light">
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-secondary text-white">Bed 02</span>
                            <span class="text-muted small">Door Side</span>
                        </div>
                        <h6 class="fw-bold text-dark mt-2 mb-0">Aditya Kumar</h6>
                        <small class="text-muted">B.Tech ECE • 2nd Year</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Allocation Summary -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold text-dark mb-3">Allocation Summary</h5>
            <ul class="list-unstyled small mb-0">
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Allotment Date</span>
                    <span class="fw-bold text-dark">01 Aug 2026</span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Academic Term</span>
                    <span class="fw-bold text-dark">2026 – 2027</span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Caution Deposit</span>
                    <span class="fw-bold text-success">Paid (No Dues)</span>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Biometric Status</span>
                    <span class="fw-bold text-success"><i class="bi bi-check-circle-fill me-1"></i>Active</span>
                </li>
                <li class="d-flex justify-content-between py-2">
                    <span class="text-muted">Floor Supervisor</span>
                    <span class="fw-bold text-dark">Mr. K. Sreenivasulu</span>
                </li>
            </ul>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
            <h6 class="fw-bold text-forest mb-2">Request Room Transfer?</h6>
            <p class="small text-muted mb-3">
                Inter-room shifting applications are accepted during the first two weeks of semester resumption.
            </p>
            <button class="btn btn-outline-secondary btn-sm w-100" onclick="alert('Room transfer requests open in next academic round.');">
                Apply for Transfer
            </button>
        </div>
    </div>
</div>
@endsection
