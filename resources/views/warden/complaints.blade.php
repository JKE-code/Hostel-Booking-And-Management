@extends('layouts.warden')

@section('title', 'Complaints Desk — HITAM Hostels')
@section('page_title', 'Maintenance Complaints & Work Orders')

@section('warden_content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-dark">Resident Maintenance Tickets</h5>
        <small class="text-muted">Review reported room issues, assign to maintenance technicians, and log resolutions.</small>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Ticket</th>
                    <th>Resident</th>
                    <th>Category & Title</th>
                    <th>Priority</th>
                    <th>Reported</th>
                    <th>Status</th>
                    <th class="text-end">Update Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $c)
                <tr>
                    <td><span class="badge bg-light text-dark border font-monospace">#{{ $c->id }}</span></td>
                    <td>
                        <div class="fw-bold text-dark">{{ $c->student->name }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Roll: {{ $c->student->roll_number }}</div>
                    </td>
                    <td style="max-width: 240px;">
                        <span class="badge bg-light text-dark border text-uppercase mb-1" style="font-size: 0.68rem;">{{ $c->category }}</span>
                        <div class="fw-semibold text-dark text-truncate">{{ $c->title }}</div>
                        <div class="text-muted text-truncate" style="font-size: 0.75rem;">{{ $c->description }}</div>
                    </td>
                    <td>
                        @if($c->priority === 'urgent' || $c->priority === 'high')
                            <span class="badge bg-danger text-white text-uppercase">{{ $c->priority }}</span>
                        @else
                            <span class="badge bg-warning text-dark text-uppercase">{{ $c->priority }}</span>
                        @endif
                    </td>
                    <td>{{ $c->created_at->diffForHumans() }}</td>
                    <td>
                        @if($c->status === 'resolved')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Resolved</span>
                        @elseif($c->status === 'in_progress')
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">In Progress</span>
                        @elseif($c->status === 'cancelled')
                            <span class="badge bg-secondary px-2 py-1">Cancelled</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">Submitted</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-dark px-2 py-1" data-bs-toggle="modal" data-bs-target="#updateComplaintModal{{ $c->id }}">
                            <i class="bi bi-pencil-square me-1"></i>Update
                        </button>

                        <!-- Modal Update Status -->
                        <div class="modal fade" id="updateComplaintModal{{ $c->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0">
                                    <div class="modal-header border-bottom p-3">
                                        <h6 class="modal-title fw-bold">Update Ticket #{{ $c->id }}</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('warden.complaints.status', $c->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body text-start p-3">
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-dark">Ticket Status *</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="submitted" {{ $c->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                                    <option value="in_progress" {{ $c->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="resolved" {{ $c->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                    <option value="cancelled" {{ $c->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-dark">Resolution Notes / Action Taken</label>
                                                <textarea name="resolution_notes" class="form-control" rows="2" placeholder="e.g. Electrician replaced switchboard...">{{ $c->resolution_notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top p-2">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-hitam-green">Save Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No complaints recorded.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $complaints->links() }}
    </div>
</div>
@endsection
