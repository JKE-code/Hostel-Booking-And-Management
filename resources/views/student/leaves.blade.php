@extends('layouts.student')

@section('title', 'Leave & Outpass Management — Rahul Sharma')
@section('page_title', 'Outpass & Leave Requests')

@section('student_content')
<div class="row g-4">
    <!-- Left: Apply for Outpass Form -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-calendar2-plus text-forest me-2"></i>New Outpass Request</h5>
            <p class="text-secondary small mb-4">
                Requests are digitally validated with your parent or guardian prior to warden endorsement.
            </p>

            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Leave outpass submitted successfully! In production, this stores in MySQL and notifies the warden.');">
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Leave Type *</label>
                    <select class="form-select" required>
                        <option value="weekend" selected>Weekend Home Visit</option>
                        <option value="holiday">Festival / Semester Vacation</option>
                        <option value="medical">Medical Emergency / Doctor Visit</option>
                        <option value="academic">Off-Campus Project / Hackathon</option>
                        <option value="emergency">Family Event / Personal Emergency</option>
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-dark">Departure Date *</label>
                        <input type="date" class="form-control" value="2026-09-20" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold text-dark">Expected Return *</label>
                        <input type="date" class="form-control" value="2026-09-22" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Travel Destination City / Address *</label>
                    <input type="text" class="form-control" placeholder="e.g. Warangal, Telangana" value="H.No 4-12, Subedari, Warangal" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark">Reason for Leave *</label>
                    <textarea class="form-control" rows="3" placeholder="Explain the purpose of travel..." required>Visiting home for family weekend and local festival celebration.</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-dark">Parent / Guardian Verification Contact *</label>
                    <input type="tel" class="form-control" value="+91 91234 56789" required>
                    <small class="text-muted" style="font-size: 0.725rem;">An automated SMS confirmation is dispatched to this number.</small>
                </div>

                <button type="submit" class="btn btn-hitam-green w-100 py-2 fw-bold d-flex justify-content-center align-items-center gap-2">
                    <i class="bi bi-send"></i> Submit Outpass Request
                </button>
            </form>
        </div>
    </div>

    <!-- Right: Leave History & Past Outpasses -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-forest me-2"></i>Outpass Request History</h5>
                <span class="badge bg-light text-forest border">Academic Year 2026</span>
            </div>

            <!-- Record 1 -->
            <div class="p-3 border rounded-3 mb-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle mb-1">Weekend Home Visit</span>
                        <h6 class="fw-bold text-dark mb-0">Warangal (Home Visit)</h6>
                    </div>
                    <span class="badge bg-success text-white px-2 py-1">Approved & Verified</span>
                </div>
                <div class="small text-muted mb-2">
                    <i class="bi bi-calendar-range me-1"></i> 12 Sep 2026, 05:00 PM &rarr; 14 Sep 2026, 07:30 PM
                </div>
                <div class="small text-secondary bg-light p-2 rounded">
                    <strong>Warden Note:</strong> Parental telephone verification confirmed by Mr. K. Sreenivasulu at 04:15 PM.
                </div>
            </div>

            <!-- Record 2 -->
            <div class="p-3 border rounded-3 mb-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle mb-1">Medical Consultation</span>
                        <h6 class="fw-bold text-dark mb-0">Kompally Diagnostic Center</h6>
                    </div>
                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Completed</span>
                </div>
                <div class="small text-muted mb-2">
                    <i class="bi bi-calendar-range me-1"></i> 28 Aug 2026, 02:00 PM &rarr; 28 Aug 2026, 06:30 PM
                </div>
                <div class="small text-secondary bg-light p-2 rounded">
                    <strong>Warden Note:</strong> Returned on schedule at 06:18 PM. Biometric punch verified.
                </div>
            </div>

            <!-- Record 3 -->
            <div class="p-3 border rounded-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle mb-1">Semester Break</span>
                        <h6 class="fw-bold text-dark mb-0">Summer Vacation Travel</h6>
                    </div>
                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Archived</span>
                </div>
                <div class="small text-muted">
                    <i class="bi bi-calendar-range me-1"></i> 01 Jun 2026 &rarr; 30 Jun 2026 (Annual Break)
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
