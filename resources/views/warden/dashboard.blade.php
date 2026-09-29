@extends('layouts.warden')

@section('title', 'Warden Operations Dashboard — HITAM Hostels')
@section('page_title', 'Warden Operations & Approvals Console')

@section('warden_content')
<!-- Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Pending Outpasses</span>
                <div class="rounded-circle bg-warning-subtle text-warning p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['pending_outpasses'] }}</div>
            <small class="text-warning-emphasis"><a href="{{ route('warden.outpasses') }}" class="text-decoration-none">Review requests &rarr;</a></small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Open Complaints</span>
                <div class="rounded-circle bg-danger-subtle text-danger p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-tools"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['open_complaints'] }}</div>
            <small class="text-danger"><a href="{{ route('warden.complaints') }}" class="text-decoration-none">Maintenance queue &rarr;</a></small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Active Residents</span>
                <div class="rounded-circle bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['active_residents'] }}</div>
            <small class="text-success"><i class="bi bi-check-circle me-1"></i>Verified in system</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Whitelisted (Pending OTP)</span>
                <div class="rounded-circle bg-info-subtle text-info p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-person-plus"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['whitelisted_pending'] }}</div>
            <small class="text-info"><a href="{{ route('warden.students', ['status' => 'whitelisted']) }}" class="text-decoration-none">View pending &rarr;</a></small>
        </div>
    </div>
</div>

<!-- Pending Outpass Approvals Action Table -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-calendar-check text-warning me-2"></i>Immediate Outpass Requests Awaiting Action</h6>
            <small class="text-muted">Student leave requests submitted for warden parental verification & approval</small>
        </div>
        <a href="{{ route('warden.outpasses') }}" class="btn btn-sm btn-outline-dark">View All Requests</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light">
                <tr>
                    <th>Student Details</th>
                    <th>Type & Destination</th>
                    <th>Out Time</th>
                    <th>In Time</th>
                    <th>Reason</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingOutpasses as $pass)
                <tr>
                    <td>
                        <div class="fw-bold text-dark">{{ $pass->student->name }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Roll: {{ $pass->student->roll_number }} • Tel: {{ $pass->student->phone }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $pass->leave_type)) }}</span>
                        <div class="text-muted small">{{ $pass->destination }}</div>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($pass->out_datetime)->format('d M, h:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($pass->in_datetime)->format('d M, h:i A') }}</td>
                    <td style="max-width: 220px;" class="text-truncate">{{ $pass->reason }}</td>
                    <td class="text-end">
                        <form action="{{ route('warden.outpasses.approve', $pass->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success px-2 py-1">
                                <i class="bi bi-check-lg me-1"></i>Approve
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>
                        No pending outpass requests. All clear!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Open Maintenance Tickets -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-tools text-danger me-2"></i>Urgent Maintenance Complaints</h6>
            <small class="text-muted">Hostel room issues reported by residents</small>
        </div>
        <a href="{{ route('warden.complaints') }}" class="btn btn-sm btn-outline-dark">View Complaints Desk</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light">
                <tr>
                    <th>Category</th>
                    <th>Title & Resident</th>
                    <th>Priority</th>
                    <th>Logged</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($openComplaints as $comp)
                <tr>
                    <td>
                        <span class="badge bg-light text-dark border text-uppercase">{{ $comp->category }}</span>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $comp->title }}</div>
                        <small class="text-muted">{{ $comp->student->name }} (Roll: {{ $comp->student->roll_number }})</small>
                    </td>
                    <td>
                        @if($comp->priority === 'urgent' || $comp->priority === 'high')
                            <span class="badge bg-danger text-white text-uppercase">{{ $comp->priority }}</span>
                        @else
                            <span class="badge bg-warning text-dark text-uppercase">{{ $comp->priority }}</span>
                        @endif
                    </td>
                    <td>{{ $comp->created_at->diffForHumans() }}</td>
                    <td>
                        <span class="badge bg-warning-subtle text-warning-emphasis text-uppercase">{{ $comp->status }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No active maintenance tickets pending.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
