@extends('layouts.admin')

@section('title', 'Root Administrator — Estate Dashboard — HITAM Hostels')
@section('page_title', 'Master Administration Dashboard')

@section('admin_content')
<!-- Top Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Total Hostels</span>
                <div class="rounded-circle bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-buildings"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['total_hostels'] }}</div>
            <small class="text-success"><i class="bi bi-check-circle me-1"></i>3 Active Wings</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Bed Occupancy</span>
                <div class="rounded-circle bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-door-closed"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['occupied_beds'] }} <span class="fs-6 text-muted fw-normal">/ {{ $stats['total_beds'] }}</span></div>
            <small class="text-muted">{{ $stats['available_beds'] }} beds available</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Hostel Wardens</span>
                <div class="rounded-circle bg-warning-subtle text-warning p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['total_wardens'] }}</div>
            <small class="text-primary"><a href="{{ route('admin.wardens') }}" class="text-decoration-none">Manage wardens &rarr;</a></small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Security Gate Staff</span>
                <div class="rounded-circle bg-info-subtle text-info p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['total_security'] }}</div>
            <small class="text-info"><a href="{{ route('admin.security') }}" class="text-decoration-none">Manage security &rarr;</a></small>
        </div>
    </div>
</div>

<!-- Quick Administration Actions -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-3 bg-warning text-dark p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">Quick Administrative Actions</h6>
                <small class="text-muted">High-priority operational gateways & estate configuration</small>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.allocations') }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                <i class="bi bi-grid-3x3-gap-fill"></i> Room Allotments
            </a>
            <a href="{{ route('admin.students') }}" class="btn btn-sm btn-outline-success d-flex align-items-center gap-1">
                <i class="bi bi-person-badge-fill"></i> Resident Scholars
            </a>
            <a href="{{ route('admin.leaves') }}" class="btn btn-sm btn-outline-warning text-dark d-flex align-items-center gap-1">
                <i class="bi bi-card-checklist"></i> Outpasses ({{ $stats['pending_outpasses'] }} pending)
            </a>
            <a href="{{ route('admin.complaints') }}" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1">
                <i class="bi bi-tools"></i> Maintenance ({{ $stats['open_complaints'] }} open)
            </a>
        </div>
    </div>
</div>

<!-- Campus Hostels Capacity & Occupancy Breakdown -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-buildings text-primary me-2"></i>Campus Hostels Estate Status</h6>
            <small class="text-muted">Live bed capacity, current student occupancy, and available vacancies across all wings</small>
        </div>
        <a href="{{ route('admin.allocations') }}" class="btn btn-sm btn-outline-dark">View Full Allotment Grid &rarr;</a>
    </div>

    <div class="row g-3">
        @foreach($hostels as $h)
        <div class="col-md-4">
            <div class="p-3 rounded-4 border bg-light h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ $h->name }}</h6>
                        <small class="text-muted">{{ ucfirst($h->gender_type) }} Residents • Code: {{ $h->code }}</small>
                    </div>
                    <span class="badge bg-{{ $h->occupancy_rate > 80 ? 'danger' : ($h->occupancy_rate > 50 ? 'warning' : 'success') }} text-white">
                        {{ $h->occupancy_rate }}% Full
                    </span>
                </div>

                <div class="progress my-2" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $h->occupancy_rate }}%;" aria-valuenow="{{ $h->occupancy_rate }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center small mt-2">
                    <span class="text-muted"><i class="bi bi-door-closed me-1"></i>Occupied: <strong>{{ $h->computed_occupied_beds }}</strong></span>
                    <span class="text-success"><i class="bi bi-check2-circle me-1"></i>Available: <strong>{{ $h->computed_available_beds }}</strong></span>
                    <span class="text-secondary">Capacity: {{ $h->computed_total_beds }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Student Onboarding Breakdown -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-dark">Resident Scholar Lifecycle & Onboarding</h6>
            <small class="text-muted">Total student profiles tracked across 2-way verification</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary-subtle text-secondary px-3 py-1">Total: {{ $stats['total_students'] }}</span>
            <a href="{{ route('admin.students') }}" class="btn btn-sm btn-outline-success">View Directory &rarr;</a>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="p-3 rounded-3 border border-success-subtle" style="background: #F0FDF4;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-success fw-bold small">Active Verified Residents</div>
                        <div class="fs-4 fw-bold text-dark">{{ $stats['active_students'] }}</div>
                        <small class="text-muted">Completed OTP verification and password setup</small>
                    </div>
                    <i class="bi bi-person-check-fill fs-1 text-success opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-3 rounded-3 border border-warning-subtle" style="background: #FFFBEB;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-warning-emphasis fw-bold small">Whitelisted (Pending Activation)</div>
                        <div class="fs-4 fw-bold text-dark">{{ $stats['whitelisted_students'] }}</div>
                        <small class="text-muted">Pre-approved by Warden; ready for 2-way signup verification</small>
                    </div>
                    <i class="bi bi-clock-history fs-1 text-warning opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Wardens & Security Staff Grid -->
<div class="row g-4">
    <!-- Wardens Section -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-people text-warning me-2"></i>Resident Wardens</h6>
                    <small class="text-muted">Authorized to whitelist scholars & approve outpasses</small>
                </div>
                <a href="{{ route('admin.wardens') }}" class="btn btn-sm btn-outline-dark">Manage Wardens &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Warden Name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wardens as $warden)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $warden->name }}</div>
                            </td>
                            <td class="text-muted">{{ $warden->email }}</td>
                            <td>
                                @if($warden->is_active)
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Deactivated</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">No wardens registered yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Security Staff Section -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-shield-lock text-info me-2"></i>Security Gate Personnel</h6>
                    <small class="text-muted">Authorized to operate main gate outpass scanner & log visitors</small>
                </div>
                <a href="{{ route('admin.security') }}" class="btn btn-sm btn-outline-dark">Manage Security &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Desk Officer</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($securityStaff as $staff)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $staff->name }}</div>
                            </td>
                            <td class="text-muted">{{ $staff->email }}</td>
                            <td>
                                @if($staff->is_active)
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Deactivated</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">No security staff configured.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
