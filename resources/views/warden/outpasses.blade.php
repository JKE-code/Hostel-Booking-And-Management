@extends('layouts.warden')

@section('title', 'Outpass Approvals — HITAM Hostels')
@section('page_title', 'Outpass & Leave Permission Approvals')

@section('warden_content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-dark">Hostel Outpass Requests</h5>
        <small class="text-muted">Review, approve, or reject student requests for home visits, weekend passes, or day outpasses.</small>
    </div>
    <!-- Filter Tabs -->
    <div class="btn-group" role="group">
        <a href="{{ route('warden.outpasses', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' }}">Pending</a>
        <a href="{{ route('warden.outpasses', ['status' => 'approved']) }}" class="btn btn-sm {{ $status === 'approved' ? 'btn-success fw-bold' : 'btn-outline-secondary' }}">Approved</a>
        <a href="{{ route('warden.outpasses', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-danger fw-bold' : 'btn-outline-secondary' }}">Rejected</a>
        <a href="{{ route('warden.outpasses', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-dark fw-bold' : 'btn-outline-secondary' }}">All Requests</a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Pass ID</th>
                    <th>Student Details</th>
                    <th>Leave Type</th>
                    <th>Destination & Reason</th>
                    <th>Out Date/Time</th>
                    <th>Expected Return</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($outpasses as $pass)
                <tr>
                    <td><span class="badge bg-light text-dark border font-monospace">#{{ $pass->id }}</span></td>
                    <td>
                        <div class="fw-bold text-dark">{{ $pass->student->name }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Roll: {{ $pass->student->roll_number }} • Tel: {{ $pass->student->phone }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border text-capitalize">{{ str_replace('_', ' ', $pass->leave_type) }}</span>
                    </td>
                    <td style="max-width: 200px;">
                        <div class="fw-semibold text-dark text-truncate">{{ $pass->destination }}</div>
                        <div class="text-muted text-truncate" style="font-size: 0.75rem;">{{ $pass->reason }}</div>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($pass->out_datetime)->format('d M, h:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($pass->in_datetime)->format('d M, h:i A') }}</td>
                    <td>
                        @if($pass->status === 'approved')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Approved</span>
                        @elseif($pass->status === 'pending')
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">Pending</span>
                        @elseif($pass->status === 'rejected')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Rejected</span>
                        @elseif($pass->status === 'returned')
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">Returned</span>
                        @else
                            <span class="badge bg-secondary px-2 py-1">{{ ucfirst($pass->status) }}</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if($pass->status === 'pending')
                            <form action="{{ route('warden.outpasses.approve', $pass->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success px-2 py-1">
                                    <i class="bi bi-check-lg me-1"></i>Approve
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $pass->id }}">
                                <i class="bi bi-x-lg me-1"></i>Reject
                            </button>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $pass->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0">
                                        <div class="modal-header border-bottom p-3">
                                            <h6 class="modal-title fw-bold text-danger">Reject Outpass #{{ $pass->id }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('warden.outpasses.reject', $pass->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body text-start p-3">
                                                <label class="form-label small fw-semibold text-dark">Reason for Rejection *</label>
                                                <input type="text" name="rejection_reason" class="form-control" placeholder="e.g. Parental verification failed or attendance below 75%" required>
                                            </div>
                                            <div class="modal-footer border-top p-2">
                                                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-danger">Confirm Rejection</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-muted" style="font-size: 0.75rem;">Processed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No outpasses found matching current filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $outpasses->links() }}
    </div>
</div>
@endsection
