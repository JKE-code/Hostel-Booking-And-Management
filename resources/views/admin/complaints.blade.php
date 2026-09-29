@extends('layouts.admin')

@section('title', 'Maintenance & Grievances Desk — HITAM Hostels')
@section('page_title', 'Campus Hostel Maintenance & Grievances Desk')

@section('admin_content')
{{-- Summary Metrics --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">New Submissions</span>
                <div class="rounded-circle bg-danger-subtle text-danger p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-tools"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-danger">{{ $stats['submitted'] }}</div>
            <small class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Awaiting Technician</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">In Progress</span>
                <div class="rounded-circle bg-warning-subtle text-warning p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-gear-wide-connected"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-warning">{{ $stats['in_progress'] }}</div>
            <small class="text-warning"><i class="bi bi-person-gear me-1"></i>Active Repairs</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Resolved</span>
                <div class="rounded-circle bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-success">{{ $stats['resolved'] }}</div>
            <small class="text-success"><i class="bi bi-patch-check-fill me-1"></i>Closed Tickets</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Total Campus Tickets</span>
                <div class="rounded-circle bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-clipboard-data"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ $stats['total'] }}</div>
            <small class="text-muted"><i class="bi bi-archive me-1"></i>All Recorded Issues</small>
        </div>
    </div>
</div>

{{-- Complaint Tickets Card --}}
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">Maintenance Tickets Queue</h5>
            <small class="text-muted">Universal administrative log of all student complaints, room repairs, and equipment upkeep</small>
        </div>

        <!-- Filter & Search Form -->
        <form action="{{ route('admin.complaints') }}" method="GET" class="d-flex flex-wrap gap-2">
            <select name="status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Statuses</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>New Submissions</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>

            <select name="category" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                <option value="all" {{ request('category') === 'all' || !request('category') ? 'selected' : '' }}>All Categories</option>
                <option value="electrical" {{ request('category') === 'electrical' ? 'selected' : '' }}>Electrical</option>
                <option value="plumbing" {{ request('category') === 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                <option value="carpentry" {{ request('category') === 'carpentry' ? 'selected' : '' }}>Carpentry</option>
                <option value="cleaning" {{ request('category') === 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                <option value="internet" {{ request('category') === 'internet' ? 'selected' : '' }}>Internet</option>
                <option value="mess" {{ request('category') === 'mess' ? 'selected' : '' }}>Mess</option>
            </select>

            <div class="input-group input-group-sm" style="width: 220px;">
                <input type="text" name="search" class="form-control" placeholder="Search ticket title, roll..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </div>
            @if(request()->filled('search') || (request()->filled('status') && request('status') !== 'all') || (request()->filled('category') && request('category') !== 'all'))
                <a href="{{ route('admin.complaints') }}" class="btn btn-sm btn-link text-danger">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Ticket / Student</th>
                    <th>Category</th>
                    <th>Issue Description</th>
                    <th>Room Location</th>
                    <th>Priority</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $ticket)
                @php
                    $student = $ticket->student;
                    $room = $ticket->room;
                    $hostel = $ticket->hostel ?? $room?->floor?->block?->hostel;
                @endphp
                <tr>
                    <td>
                        <div class="fw-bold text-dark">#TCK-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</div>
                        <div class="small text-primary fw-medium">{{ $student->name ?? 'Resident' }}</div>
                        <small class="text-muted font-monospace">{{ $student->roll_number ?? 'N/A' }}</small>
                    </td>
                    <td>
                        @php
                            $catIcons = [
                                'electrical' => 'bi-lightning-charge text-warning',
                                'plumbing'   => 'bi-droplet-half text-info',
                                'carpentry'  => 'bi-hammer text-secondary',
                                'cleaning'   => 'bi-stars text-primary',
                                'internet'   => 'bi-wifi text-success',
                                'mess'       => 'bi-cup-hot text-danger',
                            ];
                        @endphp
                        <span class="badge bg-light text-dark border d-inline-flex align-items-center gap-1">
                            <i class="bi {{ $catIcons[$ticket->category] ?? 'bi-wrench' }}"></i>
                            {{ ucfirst($ticket->category) }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $ticket->title }}</div>
                        <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">{{ $ticket->description }}</small>
                    </td>
                    <td>
                        <div class="fw-medium text-dark">{{ $hostel->name ?? 'Campus Hostel' }}</div>
                        <small class="text-muted">{{ $room ? 'Room '.$room->room_number : 'General Facility' }}</small>
                    </td>
                    <td>
                        @if($ticket->priority === 'urgent')
                            <span class="badge bg-danger text-white">Urgent</span>
                        @elseif($ticket->priority === 'high')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">High</span>
                        @elseif($ticket->priority === 'medium')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Medium</span>
                        @else
                            <span class="badge bg-light text-dark border">Low</span>
                        @endif
                    </td>
                    <td>
                        @if($ticket->status === 'submitted')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">New Submission</span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">In Progress</span>
                        @elseif($ticket->status === 'resolved')
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Resolved</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($ticket->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-tools fs-1 d-block mb-2 text-secondary opacity-50"></i>
                        No maintenance tickets found matching the specified filters.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($complaints->hasPages())
    <div class="mt-4">
        {{ $complaints->links() }}
    </div>
    @endif
</div>
@endsection
