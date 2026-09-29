@extends('layouts.security')

@section('title', 'Daily Visitors Log — HITAM Hostels')
@section('page_title', 'Campus Gate Daily Visitors Log')

@section('security_content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-white">Main Entrance Visitors Desk</h5>
        <small class="text-white-50">Log and verify external visitors, parents, and guardians visiting resident scholars.</small>
    </div>
    <button class="btn btn-success fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#newVisitorModal">
        <i class="bi bi-person-plus-fill"></i>
        <span>Check-In New Visitor</span>
    </button>
</div>

<!-- Visitors Table Card -->
<div class="card border-0 rounded-4 p-4" style="background: #1E293B; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0 small">
            <thead>
                <tr class="text-white-50" style="border-color: rgba(255, 255, 255, 0.1);">
                    <th>Visitor Name</th>
                    <th>Visiting Scholar</th>
                    <th>Relation</th>
                    <th>Contact Phone</th>
                    <th>Purpose</th>
                    <th>Check-In Time</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $v)
                <tr style="border-color: rgba(255, 255, 255, 0.06);">
                    <td>
                        <div class="fw-bold text-white">{{ $v->visitor_name }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-white">{{ $v->student->name }}</div>
                        <div class="text-white-50" style="font-size: 0.72rem;">Roll: {{ $v->student->roll_number }}</div>
                    </td>
                    <td><span class="badge bg-dark border text-light">{{ $v->relation }}</span></td>
                    <td class="text-white-50">{{ $v->visitor_phone }}</td>
                    <td class="text-white-50">{{ $v->purpose }}</td>
                    <td class="text-white">{{ $v->checked_in_at ? \Carbon\Carbon::parse($v->checked_in_at)->format('h:i A') : 'Pending' }}</td>
                    <td>
                        @if($v->status === 'checked_in')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">On Campus</span>
                        @elseif($v->status === 'checked_out')
                            <span class="badge bg-secondary px-2 py-1">Checked Out</span>
                        @else
                            <span class="badge bg-info-subtle text-info px-2 py-1">{{ ucfirst($v->status) }}</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if($v->status === 'checked_in')
                            <form action="{{ route('security.visitors.check-out', $v->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-warning px-3 py-1">
                                    <i class="bi bi-box-arrow-right me-1"></i>Check-Out
                                </button>
                            </form>
                        @elseif($v->status === 'pre_registered')
                            <form action="{{ route('security.visitors.check-in', $v->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success px-3 py-1">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Check-In
                                </button>
                            </form>
                        @else
                            <span class="text-white-50 small">Completed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-white-50 py-4">No visitor logs recorded for today.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $visitors->links() }}
    </div>
</div>

<!-- Modal: New Visitor Check-In -->
<div class="modal fade" id="newVisitorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 text-white" style="background: #1E293B;">
            <div class="modal-header border-secondary p-4">
                <h5 class="modal-title fw-bold text-white"><i class="bi bi-person-plus-fill text-success me-2"></i>Rapid Gate Visitor Entry</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('security.visitors.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-white">Student Roll Number *</label>
                        <input type="text" name="roll_number" class="form-control text-white border-secondary" style="background: #0F172A;" placeholder="e.g. 23HT1A0501" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-white">Visitor Full Name *</label>
                        <input type="text" name="visitor_name" class="form-control text-white border-secondary" style="background: #0F172A;" placeholder="e.g. K. Ravi Reddy" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-white">Relation to Scholar *</label>
                            <select name="relation" class="form-select text-white border-secondary" style="background: #0F172A;" required>
                                <option value="Father" selected>Father</option>
                                <option value="Mother">Mother</option>
                                <option value="Guardian">Guardian</option>
                                <option value="Sibling">Sibling</option>
                                <option value="Other">Other Relative</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-white">Visitor Phone *</label>
                            <input type="tel" name="visitor_phone" class="form-control text-white border-secondary" style="background: #0F172A;" placeholder="+91 98480 12345" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-white">Visit Purpose *</label>
                        <input type="text" name="purpose" class="form-control text-white border-secondary" style="background: #0F172A;" placeholder="e.g. Delivering luggage / weekend visit" required>
                    </div>
                </div>
                <div class="modal-footer border-secondary p-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-bold">Check-In Visitor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
