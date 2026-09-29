@extends('layouts.admin')

@section('title', 'Warden Management — Root Administrator — HITAM Hostels')
@section('page_title', 'Hostel Wardens Governance')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-dark">Resident Wardens Directory</h5>
        <small class="text-muted">Manage hostel warden accounts, assigned blocks, and operational permissions.</small>
    </div>
    <button class="btn btn-warning fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createWardenModal">
        <i class="bi bi-person-plus-fill"></i>
        <span>Add New Warden</span>
    </button>
</div>

<!-- Wardens Table Card -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Warden Name</th>
                    <th>Institutional Email</th>
                    <th>Assigned Hostel Wing</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wardens as $warden)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-warning-subtle text-dark fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                {{ strtoupper(substr($warden->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $warden->name }}</div>
                                <small class="text-muted">Registered {{ $warden->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $warden->email }}</td>
                    <td>
                        @if($warden->wardenOf->isNotEmpty())
                            @foreach($warden->wardenOf as $hostel)
                                <span class="badge bg-light text-dark border">{{ $hostel->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted small">Campus Floating Warden</span>
                        @endif
                    </td>
                    <td>
                        @if($warden->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Active</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Deactivated</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <form action="{{ route('admin.users.toggle', $warden->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Change active status for this Warden?');">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $warden->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                <i class="bi {{ $warden->is_active ? 'bi-person-x' : 'bi-person-check' }} me-1"></i>
                                {{ $warden->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No wardens found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $wardens->links() }}
    </div>
</div>

<!-- Modal: Create New Warden -->
<div class="modal fade" id="createWardenModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-badge-fill text-warning me-2"></i>Create New Warden Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.wardens.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Full Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Dr. K. Ramesh Rao" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Institutional Email *</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. ramesh.warden@hitam.org" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Initial Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Assign Hostel Wing (Optional)</label>
                        <select name="hostel_id" class="form-select">
                            <option value="">-- Unassigned / General Warden --</option>
                            @foreach($hostels as $h)
                                <option value="{{ $h->id }}">{{ $h->name }} ({{ ucfirst($h->gender_type) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold">Create Warden</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
