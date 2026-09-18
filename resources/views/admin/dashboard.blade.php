@extends('layouts.admin')

@section('title', 'Admin Overview — HITAM Hostel Management')
@section('page_title', 'Hostel Administrative Dashboard')

@section('admin_content')
<!-- Key Top Metrics -->
<div class="row g-3 g-md-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Total Occupancy</span>
                <i class="bi bi-people text-forest fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">734 / 770</h3>
            <div class="small text-success"><i class="bi bi-arrow-up-right me-1"></i>95.3% Bed Occupancy</div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Pending Outpasses</span>
                <i class="bi bi-calendar2-check text-warning fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">12</h3>
            <div class="small text-warning"><i class="bi bi-clock-history me-1"></i>Requires Warden Sign-off</div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Open Maintenance</span>
                <i class="bi bi-tools text-danger fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">5</h3>
            <div class="small text-muted"><i class="bi bi-check2-all me-1"></i>3 Electrician, 2 Plumber</div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Available Beds</span>
                <i class="bi bi-door-open text-primary fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">36</h3>
            <div class="small text-primary"><i class="bi bi-building me-1"></i>22 Boys, 14 Girls</div>
        </div>
    </div>
</div>

<!-- Pending Outpass Approvals Table -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Pending Leave & Outpass Queue</h5>
                    <small class="text-muted">Requires parent phone verification & warden clearance</small>
                </div>
                <span class="badge bg-warning text-dark px-3 py-2">12 Actions Pending</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student / Roll</th>
                            <th>Hostel & Room</th>
                            <th>Leave Type & Destination</th>
                            <th>Dates / Duration</th>
                            <th>Parent Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">Rahul Sharma</div>
                                <small class="text-muted">23HT1A0501 • CSE</small>
                            </td>
                            <td>Boys Hostel • B-204</td>
                            <td>
                                <span class="badge bg-light text-forest border">Weekend Visit</span>
                                <small class="d-block text-muted">Warangal, Telangana</small>
                            </td>
                            <td>20 Sep &rarr; 22 Sep 2026</td>
                            <td>+91 91234 56789</td>
                            <td>
                                <button class="btn btn-sm btn-success me-1" onclick="alert('Outpass Approved! SMS sent to parent.');"><i class="bi bi-check-lg"></i> Approve</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="alert('Outpass Rejected.');"><i class="bi bi-x-lg"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">Kavya Reddy</div>
                                <small class="text-muted">22HT1A0412 • ECE</small>
                            </td>
                            <td>Girls Hostel • G-108</td>
                            <td>
                                <span class="badge bg-light text-primary border">Medical Consultation</span>
                                <small class="d-block text-muted">Apollo Clinic, Kompally</small>
                            </td>
                            <td>Today, 03:00 PM &rarr; 07:00 PM</td>
                            <td>+91 98480 12345</td>
                            <td>
                                <button class="btn btn-sm btn-success me-1" onclick="alert('Outpass Approved! SMS sent to parent.');"><i class="bi bi-check-lg"></i> Approve</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="alert('Outpass Rejected.');"><i class="bi bi-x-lg"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">Sai Teja</div>
                                <small class="text-muted">24HT1A1208 • IT</small>
                            </td>
                            <td>Boys Hostel • A-302</td>
                            <td>
                                <span class="badge bg-light text-warning-emphasis border">Academic Hackathon</span>
                                <small class="d-block text-muted">IIT Hyderabad</small>
                            </td>
                            <td>21 Sep &rarr; 23 Sep 2026</td>
                            <td>+91 94400 98765</td>
                            <td>
                                <button class="btn btn-sm btn-success me-1" onclick="alert('Outpass Approved! SMS sent to parent.');"><i class="bi bi-check-lg"></i> Approve</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="alert('Outpass Rejected.');"><i class="bi bi-x-lg"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
