@extends('layouts.admin')

@section('title', 'Security Desk Management — Root Administrator — HITAM Hostels')
@section('page_title', 'Campus Gate Security Staff Governance')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-dark">Main Gate Security Staff Directory</h5>
        <small class="text-muted">Manage security personnel authorized to scan outpass QR codes and record gate movements.</small>
    </div>
    <button class="btn btn-info fw-bold text-dark d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createSecurityModal">
        <i class="bi bi-shield-plus"></i>
        <span>Add Gate Security Account</span>
    </button>
</div>

<!-- Security Staff Table Card -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Security Desk / Officer</th>
                    <th>Email Address</th>
                    <th>Assigned Location</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($securityStaff as $staff)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-info-subtle text-info fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $staff->name }}</div>
                                <small class="text-muted">Created {{ $staff->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $staff->email }}</td>
                    <td><span class="badge bg-light text-dark border">Main Campus Entrance & Gate</span></td>
                    <td>
                        @if($staff->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Active</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Deactivated</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <form action="{{ route('admin.users.toggle', $staff->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Change active status for this Security account?');">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $staff->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                <i class="bi {{ $staff->is_active ? 'bi-shield-x' : 'bi-shield-check' }} me-1"></i>
                                {{ $staff->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No security accounts registered yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $securityStaff->links() }}
    </div>
</div>

<!-- Modal: Create New Security Staff -->
<div class="modal fade" id="createSecurityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-shield-lock-fill text-info me-2"></i>Create New Gate Security Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.security.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Security Desk / Officer Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. North Gate Security Post" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Login Email *</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. security.north@hitam.org" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Initial Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required minlength="8">
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info fw-bold text-dark">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
