@extends('layouts.admin')

@section('title', 'Warden Console Inspection — Root Administrator — HITAM Hostels')
@section('page_title', 'Warden Console — Operational Inspection Mode')

@section('admin_content')
<!-- Inspection Alert Banner -->
<div class="alert border-0 rounded-4 p-3 mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3" style="background: linear-gradient(135deg, #064E3B 0%, #047857 100%); color: #FFFFFF;">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle bg-white text-forest p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; font-size: 1.25rem;">
            <i class="bi bi-building"></i>
        </div>
        <div>
            <div class="fw-bold fs-6">Live Inspection Mode: Hostel Warden Operations Console (Level 2)</div>
            <small class="text-white-50">Real-time simulation and administrative oversight of the resident warden workflow across all wings.</small>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light text-forest fw-semibold">
            <i class="bi bi-arrow-left me-1"></i>Return to Estate Overview
        </a>
    </div>
</div>

<!-- Operational KPI Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Pending Outpasses</span>
            <div class="fs-3 fw-bold text-warning mt-1">{{ $stats['pending_outpasses'] }}</div>
            <small class="text-warning"><i class="bi bi-hourglass-split me-1"></i>Awaiting Warden Approval</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Open Maintenance</span>
            <div class="fs-3 fw-bold text-danger mt-1">{{ $stats['open_complaints'] }}</div>
            <small class="text-danger"><i class="bi bi-tools me-1"></i>Active Repair Tickets</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Active Residents</span>
            <div class="fs-3 fw-bold text-success mt-1">{{ $stats['active_residents'] }}</div>
            <small class="text-success"><i class="bi bi-check2-circle me-1"></i>In-House Scholars</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Whitelisted Pending</span>
            <div class="fs-3 fw-bold text-dark mt-1">{{ $stats['whitelisted_pending'] }}</div>
            <small class="text-muted"><i class="bi bi-clock me-1"></i>Awaiting Student OTP</small>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Pending Outpass Approvals Queue -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-card-checklist text-warning me-2"></i>Outpass Review Queue</h6>
                    <small class="text-muted">Students currently requesting travel permission from warden</small>
                </div>
                <a href="{{ route('admin.leaves') }}" class="btn btn-sm btn-outline-dark">View All Leaves</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student Scholar</th>
                            <th>Type & Destination</th>
                            <th>Out-Time</th>
                            <th>Parent Call</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingOutpasses as $pass)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $pass->student->name ?? 'Student' }}</div>
                                <small class="text-muted">{{ $pass->student->roll_number ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border text-uppercase">{{ str_replace('_', ' ', $pass->leave_type) }}</span>
                                <div class="text-muted small mt-1">{{ $pass->destination }}</div>
                            </td>
                            <td>
                                <div>{{ $pass->out_datetime ? \Carbon\Carbon::parse($pass->out_datetime)->format('M d, h:i A') : 'Pending' }}</div>
                            </td>
                            <td>
                                @if($pass->parent_consent)
                                    <span class="badge bg-success-subtle text-success">Confirmed</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">Call Required</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No outpasses currently awaiting warden sign-off.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Whitelisted Scholars Pending Onboarding -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-person-plus text-success me-2"></i>Whitelisted by Warden</h6>
                    <small class="text-muted">Pre-approved students ready for 2-way signup verification</small>
                </div>
                <a href="{{ route('admin.students') }}" class="btn btn-sm btn-outline-dark">All Students</a>
            </div>

            <div class="list-group list-group-flush">
                @forelse($whitelistedPending as $student)
                <div class="list-group-item px-0 py-3 border-light">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold text-dark">{{ $student->name }}</div>
                            <small class="text-muted">{{ $student->email }} • {{ $student->roll_number }}</small>
                            <div class="small text-muted mt-1"><i class="bi bi-telephone me-1"></i>{{ $student->phone }}</div>
                        </div>
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                            Pending Signup
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">No students currently in pending whitelist status.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Open Maintenance Tickets -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-tools text-danger me-2"></i>Active Warden Maintenance Queue</h6>
            <small class="text-muted">Grievances filed by residents currently assigned to wardens for contractor dispatch</small>
        </div>
        <a href="{{ route('admin.complaints') }}" class="btn btn-sm btn-outline-dark">Maintenance Desk &rarr;</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light">
                <tr>
                    <th>Ticket ID</th>
                    <th>Category</th>
                    <th>Title & Details</th>
                    <th>Resident / Roll</th>
                    <th>Priority</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($openComplaints as $c)
                <tr>
                    <td class="fw-bold font-monospace">#TCK-{{ str_pad($c->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td><span class="badge bg-light text-dark border text-uppercase">{{ $c->category }}</span></td>
                    <td>
                        <div class="fw-medium text-dark">{{ $c->title }}</div>
                        <small class="text-muted">{{ $c->description }}</small>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $c->student->name ?? 'Resident' }}</div>
                        <small class="text-muted">{{ $c->student->roll_number ?? 'N/A' }}</small>
                    </td>
                    <td>
                        @if($c->priority === 'urgent' || $c->priority === 'high')
                            <span class="badge bg-danger text-white">{{ ucfirst($c->priority) }}</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border">{{ ucfirst($c->priority) }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-warning-subtle text-warning-emphasis border">{{ ucfirst(str_replace('_', ' ', $c->status)) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No open maintenance tickets currently recorded.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
