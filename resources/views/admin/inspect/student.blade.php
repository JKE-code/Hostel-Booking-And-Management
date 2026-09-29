@extends('layouts.admin')

@section('title', 'Student Portal Inspection — Root Administrator — HITAM Hostels')
@section('page_title', 'Resident Student Portal — Operational Inspection Mode')

@section('admin_content')
<!-- Inspection Alert Banner with Student Switcher -->
<div class="alert border-0 rounded-4 p-3 mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3" style="background: linear-gradient(135deg, #064E3B 0%, #022c22 100%); color: #FFFFFF;">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle bg-white text-forest p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; font-size: 1.25rem;">
            <i class="bi bi-mortarboard"></i>
        </div>
        <div>
            <div class="fw-bold fs-6">Live Inspection Mode: Resident Student Portal (Level 4)</div>
            <small class="text-white-50">Simulating real-time student resident dashboard, room cards, leaves, and digital mess passes.</small>
        </div>
    </div>
    
    <!-- Student Switcher Dropdown -->
    <form action="{{ route('admin.inspect.student') }}" method="GET" class="d-flex align-items-center gap-2">
        <label class="small text-white-50 text-nowrap d-none d-md-inline">Inspect As Scholar:</label>
        <select name="student_id" class="form-select form-select-sm bg-white text-dark border-0 fw-semibold" onchange="this.form.submit()" style="min-width: 220px;">
            @foreach($students as $s)
                <option value="{{ $s->id }}" {{ $student && $student->id == $s->id ? 'selected' : '' }}>
                    {{ $s->name }} ({{ $s->roll_number }})
                </option>
            @endforeach
        </select>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light text-nowrap">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </form>
</div>

@if($student)
<!-- Resident Welcome & Room Snapshot (Exact replication of Student View) -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #064E3B 0%, #022c22 100%);">
            <div class="position-relative" style="z-index: 2;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-white text-forest fw-bold px-2 py-1">Active Resident Scholar</span>
                    <span class="text-white-50 small">Roll No: {{ $student->roll_number }}</span>
                    <span class="badge bg-warning text-dark small fw-semibold">{{ $student->department }} • Year {{ $student->year_of_study ?? 1 }}</span>
                </div>
                <h3 class="fw-bold mb-1">Resident Profile: {{ $student->name }}</h3>
                <p class="text-white-50 small mb-4" style="max-width: 500px;">
                    @if($allocation)
                        {{ $allocation->bed->room->floor->block->hostel->name ?? 'Residential Wing' }} • Room {{ $allocation->bed->room->room_number }} • Bed {{ $allocation->bed->bed_number }}
                    @else
                        Room Allocation in progress by Hostel Warden
                    @endif
                </p>

                <div class="row g-3 pt-3 border-top border-white border-opacity-10 text-start">
                    <div class="col-6 col-sm-4">
                        <div class="small text-white-50">Evening Gate In-Time</div>
                        <div class="fw-bold fs-6 text-white">08:30 PM</div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <div class="small text-white-50">Biometric Roll Call</div>
                        <div class="fw-bold fs-6 text-white">09:00 PM – 09:30 PM</div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="small text-white-50">Active Leave Status</div>
                        <div class="fw-bold fs-6 text-white">
                            @if($recentOutpasses->where('status', 'approved')->isNotEmpty())
                                <span class="badge bg-success text-white">Active Approved Pass</span>
                            @else
                                <span class="badge bg-light text-forest">No Active Outpass</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute end-0 bottom-0 opacity-10 p-3" style="font-size: 8rem; line-height: 1;">
                <i class="bi bi-building"></i>
            </div>
        </div>
    </div>

    <!-- Scholar Fast Details Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge text-forest me-2"></i>Resident Scholar Record</h6>
            <div class="d-flex flex-column gap-2 small">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Institutional Email:</span>
                    <span class="fw-semibold text-dark">{{ $student->email }}</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Contact Phone:</span>
                    <span class="fw-semibold text-dark">{{ $student->phone }}</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Room Type:</span>
                    <span class="fw-semibold text-dark">{{ ucfirst($allocation->bed->room->room_type ?? '2-Sharing') }}</span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Onboarding Status:</span>
                    <span class="badge bg-success-subtle text-success">Verified Active</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Student's Recent Outpasses & QR Tokens -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-qr-code text-forest me-2"></i>Recent Outpass Requests & QR Passes</h6>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Token / Type</th>
                            <th>Destination</th>
                            <th>Out-Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOutpasses as $pass)
                        <tr>
                            <td>
                                <div class="fw-bold font-monospace text-dark">{{ $pass->qr_verification_code ?? 'OP-'.$pass->id }}</div>
                                <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.7rem;">{{ str_replace('_', ' ', $pass->leave_type) }}</span>
                            </td>
                            <td>
                                <div class="text-dark">{{ $pass->destination }}</div>
                                <small class="text-muted">{{ $pass->reason }}</small>
                            </td>
                            <td>
                                <div>{{ $pass->out_datetime ? \Carbon\Carbon::parse($pass->out_datetime)->format('M d, h:i A') : 'N/A' }}</div>
                            </td>
                            <td>
                                @if($pass->status === 'approved')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Approved</span>
                                @elseif($pass->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Pending Review</span>
                                @else
                                    <span class="badge bg-light text-dark border">{{ ucfirst($pass->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No outpass requests submitted by this resident.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Student Complaints / Grievances -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-tools text-forest me-2"></i>Resident Maintenance Requests</h6>

            <div class="list-group list-group-flush">
                @forelse($recentComplaints as $c)
                <div class="list-group-item px-0 py-2 border-light">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold text-dark">{{ $c->title }}</div>
                            <small class="text-muted">{{ $c->description }}</small>
                        </div>
                        <span class="badge bg-{{ $c->status === 'resolved' ? 'success' : 'warning' }}-subtle text-{{ $c->status === 'resolved' ? 'success' : 'dark' }} small">
                            {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">No maintenance complaints registered for this resident.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@else
<div class="card border-0 shadow-sm rounded-4 p-5 bg-white text-center">
    <i class="bi bi-person-exclamation fs-1 text-warning d-block mb-3"></i>
    <h5 class="fw-bold text-dark">No Active Resident Found</h5>
    <p class="text-muted">There are currently no active students with verified accounts to inspect.</p>
</div>
@endif
@endsection
