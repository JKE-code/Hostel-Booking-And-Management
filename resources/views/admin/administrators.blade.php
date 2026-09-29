@extends('layouts.admin')

@section('title', 'Administrator Governance — HITAM Hostels')
@section('page_title', 'Campus Administrators Governance')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-dark">System Administrators Directory</h5>
        <small class="text-muted">Multi-admin governance model providing redundancy, eliminating single point of failure (SPOF), and enforcing individual audit trails.</small>
    </div>
    <button class="btn btn-warning fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createAdminModal">
        <i class="bi bi-shield-plus"></i>
        <span>Add Administrator</span>
    </button>
</div>

<!-- Admins Table Card -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Administrator</th>
                    <th>Institutional Email</th>
                    <th>Tier / Privilege</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($administrators as $admin)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                {{ strtoupper(substr($admin->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $admin->name }}</div>
                                <small class="text-muted">Registered {{ $admin->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        @if($admin->email === 'admin@hitam.org')
                            <span class="badge bg-warning text-dark border border-warning px-2 py-1"><i class="bi bi-shield-shaded me-1"></i>Primary Super Admin</span>
                        @else
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">Campus Administrator</span>
                        @endif
                    </td>
                    <td>
                        @if($admin->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Active</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Deactivated</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if($admin->email === 'admin@hitam.org')
                            <span class="badge bg-light text-muted border px-2 py-1"><i class="bi bi-lock-fill me-1"></i>Protected Master</span>
                        @else
                            <form action="{{ route('admin.users.toggle', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Change active status for this Administrator?');">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $admin->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                    <i class="bi {{ $admin->is_active ? 'bi-shield-x' : 'bi-shield-check' }} me-1"></i>
                                    {{ $admin->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No administrator accounts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $administrators->links() }}
    </div>
</div>

<!-- Modal: Create New Administrator -->
<div class="modal fade" id="createAdminModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-shield-plus text-warning me-2"></i>Create New Administrator Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.administrators.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Full Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Dr. P. Rajesh (Estate Director)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Institutional Email *</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. director.estate@hitam.org" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Initial Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required minlength="8">
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold">Create Administrator</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
