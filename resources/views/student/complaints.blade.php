@extends('layouts.student')

@section('title', 'Maintenance & Complaints — Rahul Sharma')
@section('page_title', 'Maintenance Tickets & Complaints')

@section('student_content')
<div class="row g-4">
    <!-- Left: Lodge Ticket Form -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-tools text-forest me-2"></i>Raise Maintenance Ticket</h5>
            <p class="text-secondary small mb-4">
                Our estate maintenance team resolves tickets under a strict 24-hour service level agreement (SLA).
            </p>

            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Maintenance ticket lodged! Assigned to campus electrician / plumber.');">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Category *</label>
                    <select class="form-select" required>
                        <option value="electrical" selected>Electrical & Lighting</option>
                        <option value="plumbing">Plumbing & Washroom</option>
                        <option value="carpentry">Carpentry, Cots & Locks</option>
                        <option value="internet">Wi-Fi & Network Access</option>
                        <option value="cleaning">Room Housekeeping & Dustbin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Issue Title *</label>
                    <input type="text" class="form-control" placeholder="e.g. Study lamp switch not working" value="Ceiling Fan regulator sparking" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Detailed Description *</label>
                    <textarea class="form-control" rows="3" placeholder="Describe the fault clearly..." required>The speed regulator of the ceiling fan in Room B-204 makes clicking sounds and sparks when turned to speed 3.</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-dark">Preferred Inspection Timing</label>
                    <select class="form-select">
                        <option value="morning">Morning (09:00 AM – 12:00 PM)</option>
                        <option value="afternoon" selected>Afternoon (02:00 PM – 05:00 PM)</option>
                        <option value="anytime">Anytime during working hours</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-hitam-green w-100 py-2 fw-bold d-flex justify-content-center align-items-center gap-2">
                    <i class="bi bi-file-earmark-plus"></i> Submit Maintenance Ticket
                </button>
            </form>
        </div>
    </div>

    <!-- Right: Ticket History -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-check text-forest me-2"></i>My Maintenance History</h5>
                <span class="badge bg-success-subtle text-success border border-success-subtle">SLA: 24 Hours</span>
            </div>

            <!-- Ticket 1: Active -->
            <div class="p-3 border rounded-3 mb-3 border-warning bg-warning-subtle bg-opacity-10">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2">
                    <div>
                        <span class="badge bg-warning text-dark fw-bold mb-1">Ticket #TCK-2081</span>
                        <h6 class="fw-bold text-dark mb-0">Study Desk Light Socket Replacement</h6>
                    </div>
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">In Progress</span>
                </div>
                <p class="small text-muted mb-2">
                    Power plug socket beside window bed has loose contacts.
                </p>
                <div class="small text-secondary bg-white p-2 rounded border">
                    <i class="bi bi-person-gear text-forest me-1"></i> <strong>Assigned Technician:</strong> Mr. Ramesh (Electrician) • ETA: Today 03:00 PM
                </div>
            </div>

            <!-- Ticket 2: Resolved -->
            <div class="p-3 border rounded-3 mb-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2">
                    <div>
                        <span class="badge bg-light text-muted border mb-1">Ticket #TCK-1940</span>
                        <h6 class="fw-bold text-dark mb-0">Bathroom Shower Valve Leakage</h6>
                    </div>
                    <span class="badge bg-success text-white">Resolved</span>
                </div>
                <p class="small text-muted mb-2">
                    Hot water valve washer was loose.
                </p>
                <div class="small text-secondary bg-light p-2 rounded">
                    <i class="bi bi-check-circle-fill text-success me-1"></i> Resolved within 6 hours by plumber team. Verified by resident.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
