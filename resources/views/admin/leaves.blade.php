@extends('layouts.admin')

@section('title', 'Outpass & Student Leaves Oversight — HITAM Hostels')
@section('page_title', 'Campus Outpasses & Student Movement Oversight')

@section('admin_content')
{{-- Summary Metrics --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Pending Approvals</span>
                <div class="rounded-circle bg-warning-subtle text-warning p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['pending'] }}</div>
            <small class="text-warning"><i class="bi bi-clock-history me-1"></i>Awaiting Warden Review</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Total Approved</span>
                <div class="rounded-circle bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['approved'] }}</div>
            <small class="text-success"><i class="bi bi-shield-check me-1"></i>Valid Gate Passes</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Currently Outside</span>
                <div class="rounded-circle bg-danger-subtle text-danger p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-door-open-fill"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-danger">{{ $stats['outside'] }}</div>
            <small class="text-danger"><i class="bi bi-broadcast me-1"></i>Past Main Campus Gate</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Completed Returns</span>
                <div class="rounded-circle bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-person-check"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['completed'] }}</div>
            <small class="text-muted"><i class="bi bi-calendar-check me-1"></i>Safely Returned</small>
        </div>
    </div>
</div>

{{-- Outpasses Table Card --}}
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">Campus Outpasses Registry</h5>
            <small class="text-muted">Universal administrative log of all student movement, travel permissions, and gate timestamps</small>
        </div>

        <!-- Filter & Search Form -->
        <form action="{{ route('admin.leaves') }}" method="GET" class="d-flex flex-wrap gap-2">
            <select name="status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Approvals</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="outside" {{ request('status') === 'outside' ? 'selected' : '' }}>Currently Outside</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed / Returned</option>
            </select>

            <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" name="search" class="form-control" placeholder="Search roll no, destination..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </div>
            @if(request()->filled('search') || (request()->filled('status') && request('status') !== 'all'))
                <a href="{{ route('admin.leaves') }}" class="btn btn-sm btn-link text-danger">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Pass ID / Scholar</th>
                    <th>Leave Type</th>
                    <th>Destination & Purpose</th>
                    <th>Out-Time & Expected Return</th>
                    <th>Parent Consent</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($outpasses as $pass)
                @php
                    $student = $pass->student;
                @endphp
                <tr>
                    <td>
                        <div class="fw-bold text-dark font-monospace">{{ $pass->qr_verification_code ?? 'OP-'.$pass->id }}</div>
                        <div class="d-flex align-items-center gap-1 mt-1">
                            <span class="fw-semibold text-primary small">{{ $student->name ?? 'Student' }}</span>
                            <small class="text-muted">({{ $student->roll_number ?? 'N/A' }})</small>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.75rem;">
                            {{ str_replace('_', ' ', $pass->leave_type) }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-medium text-dark">{{ $pass->destination }}</div>
                        <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $pass->reason }}</small>
                    </td>
                    <td>
                        <div class="small">
                            <span class="text-muted">Out:</span> 
                            <strong>{{ $pass->out_datetime ? \Carbon\Carbon::parse($pass->out_datetime)->format('M d, h:i A') : 'Pending' }}</strong>
                        </div>
                        <div class="small">
                            <span class="text-muted">In:</span> 
                            <strong>{{ $pass->in_datetime ? \Carbon\Carbon::parse($pass->in_datetime)->format('M d, h:i A') : 'Pending' }}</strong>
                        </div>
                    </td>
                    <td>
                        @if($pass->parent_consent)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-telephone-check me-1"></i>Confirmed ({{ ucfirst($pass->parent_consent_via ?? 'call') }})
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                <i class="bi bi-clock me-1"></i>Pending Call
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($pass->status === 'approved')
                            @if($pass->actual_out_datetime && !$pass->actual_return_datetime)
                                <span class="badge bg-danger text-white">Outside Campus</span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Approved</span>
                            @endif
                        @elseif($pass->status === 'pending')
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Pending Review</span>
                        @elseif($pass->status === 'completed')
                            <span class="badge bg-secondary-subtle text-secondary border">Completed</span>
                        @elseif($pass->status === 'rejected')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Rejected</span>
                        @else
                            <span class="badge bg-light text-dark border">{{ ucfirst($pass->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-card-checklist fs-1 d-block mb-2 text-secondary opacity-50"></i>
                        No outpass records found matching the criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($outpasses->hasPages())
    <div class="mt-4">
        {{ $outpasses->links() }}
    </div>
    @endif
</div>
@endsection
