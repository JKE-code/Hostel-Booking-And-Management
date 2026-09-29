@extends('layouts.admin')

@section('title', 'Security Gate Monitor Inspection — Root Administrator — HITAM Hostels')
@section('page_title', 'Security Gate Monitor — Operational Inspection Mode')

@section('admin_content')
<!-- Inspection Alert Banner -->
<div class="alert border-0 rounded-4 p-3 mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); color: #FFFFFF;">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle bg-info-subtle text-info p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; font-size: 1.25rem;">
            <i class="bi bi-shield-check"></i>
        </div>
        <div>
            <div class="fw-bold fs-6">Live Inspection Mode: Main Campus Security Gate Desk (Level 3)</div>
            <small class="text-white-50">Real-time gate pass QR verification, scholar outside tracker, and campus visitor desk.</small>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light fw-semibold">
            <i class="bi bi-arrow-left me-1"></i>Return to Estate Overview
        </a>
    </div>
</div>

<!-- Operational KPI Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Currently Outside</span>
            <div class="fs-3 fw-bold text-danger mt-1">{{ $stats['currently_outside'] }}</div>
            <small class="text-danger"><i class="bi bi-broadcast me-1"></i>Past Main Gate Barrier</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Today's Approved Passes</span>
            <div class="fs-3 fw-bold text-success mt-1">{{ $stats['today_approved_passes'] }}</div>
            <small class="text-success"><i class="bi bi-patch-check-fill me-1"></i>Authorized by Warden</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Active Campus Visitors</span>
            <div class="fs-3 fw-bold text-primary mt-1">{{ $stats['active_visitors'] }}</div>
            <small class="text-primary"><i class="bi bi-person-lines-fill me-1"></i>Checked-In at Gate</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Total Gate Entries Today</span>
            <div class="fs-3 fw-bold text-dark mt-1">{{ $stats['total_visitors_today'] }}</div>
            <small class="text-muted"><i class="bi bi-card-checklist me-1"></i>Visitor Logbook Entries</small>
        </div>
    </div>
</div>

<!-- Fast Outpass QR / Roll Number Verification Tool -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-qr-code-scan text-success me-2"></i>Outpass Verification Simulator</h6>
            <small class="text-muted">Type Roll Number (e.g. 23HT1A0501) or Verification Token to test gate validation</small>
        </div>
        <span class="badge bg-success-subtle text-success border border-success px-3 py-1">Gate Scanner Tool</span>
    </div>

    <form action="{{ route('admin.inspect.security') }}" method="GET" class="mb-3">
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control border-start-0" placeholder="Scan QR Code or Type Roll Number (e.g. 23HT1A0501)..." value="{{ request('search') }}">
            <button class="btn btn-dark px-4 fw-semibold" type="submit">Verify Pass</button>
        </div>
    </form>

    @if($scannedPass)
    <div class="p-3 rounded-4 border border-success bg-success-subtle bg-opacity-25 mt-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success text-white px-3 py-2 fw-bold">VALID GATE PASS</span>
                <span class="text-muted small font-monospace">{{ $scannedPass->qr_verification_code }}</span>
            </div>
            <span class="badge bg-light text-dark border text-uppercase">{{ str_replace('_', ' ', $scannedPass->leave_type) }}</span>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="small text-muted">Scholar Name:</div>
                <div class="fw-bold text-dark">{{ $scannedPass->student->name }} ({{ $scannedPass->student->roll_number }})</div>
            </div>
            <div class="col-md-4">
                <div class="small text-muted">Destination & Reason:</div>
                <div class="fw-medium text-dark">{{ $scannedPass->destination }} • {{ $scannedPass->reason }}</div>
            </div>
            <div class="col-md-4">
                <div class="small text-muted">Valid Window:</div>
                <div class="fw-medium text-dark">{{ \Carbon\Carbon::parse($scannedPass->out_datetime)->format('M d, h:i A') }} to {{ \Carbon\Carbon::parse($scannedPass->in_datetime)->format('M d, h:i A') }}</div>
            </div>
        </div>
    </div>
    @elseif(request('search'))
    <div class="alert alert-warning border-0 rounded-3 mt-3 mb-0">
        <i class="bi bi-exclamation-triangle me-2"></i>No approved outpass found matching "<strong>{{ request('search') }}</strong>". Please ensure student has an approved pass for today.
    </div>
    @endif
</div>

<div class="row g-4">
    <!-- Students Currently Outside Campus -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Scholars Currently Outside Campus</h6>
                    <small class="text-muted">Live movement tracker recorded at Main Campus Gate</small>
                </div>
                <a href="{{ route('admin.leaves', ['status' => 'outside']) }}" class="btn btn-sm btn-outline-dark">View All Outside</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Destination</th>
                            <th>Out Timestamp</th>
                            <th>Expected In</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($currentlyOutside as $out)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $out->student->name ?? 'Student' }}</div>
                                <small class="text-muted">{{ $out->student->roll_number ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="text-dark">{{ $out->destination }}</div>
                                <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $out->leave_type)) }}</small>
                            </td>
                            <td>
                                <span class="text-danger fw-medium">{{ $out->out_datetime ? \Carbon\Carbon::parse($out->out_datetime)->format('M d, h:i A') : 'Pending Gate Scan' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $out->in_datetime ? \Carbon\Carbon::parse($out->in_datetime)->format('M d, h:i A') : 'N/A' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No students currently logged outside campus boundaries.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Campus Visitors -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-people-fill text-info me-2"></i>Campus Visitor Logs</h6>
                    <small class="text-muted">Recent entries registered at Main Gate Desk</small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Visitor</th>
                            <th>Purpose / Meeting</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentVisitors as $v)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $v->visitor_name }}</div>
                                <small class="text-muted">{{ $v->phone }}</small>
                            </td>
                            <td>
                                <div class="text-dark">{{ $v->purpose }}</div>
                                <small class="text-muted">Student: {{ $v->student->name ?? 'General Visit' }}</small>
                            </td>
                            <td>
                                @if($v->status === 'checked_in')
                                    <span class="badge bg-success-subtle text-success">On Campus</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Checked Out</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">No visitors logged today.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
