@extends('layouts.warden')

@section('title', 'Bed Allocations — HITAM Hostels')
@section('page_title', 'Hostel Room & Bed Allocations')

@section('warden_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-dark">Active Resident Bed Allotments</h5>
        <small class="text-muted">Master room mapping across all hostel residential wings.</small>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Hostel Wing</th>
                    <th>Room & Bed</th>
                    <th>Resident Name</th>
                    <th>Roll Number</th>
                    <th>Dept & Year</th>
                    <th>Academic Year</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allocations as $alloc)
                <tr>
                    <td>
                        <span class="badge bg-light text-dark border">
                            {{ $alloc->bed->room->floor->block->hostel->name ?? 'Hostel Block' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">Room {{ $alloc->bed->room->room_number }}</div>
                        <small class="text-muted">Bed #{{ $alloc->bed->bed_number }}</small>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $alloc->student->name }}</div>
                        <small class="text-muted">{{ $alloc->student->phone }}</small>
                    </td>
                    <td><span class="badge bg-light text-dark border font-monospace">{{ $alloc->student->roll_number }}</span></td>
                    <td>{{ $alloc->student->department }} (Yr {{ $alloc->student->year_of_study }})</td>
                    <td>{{ $alloc->academic_year }}</td>
                    <td>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                            <i class="bi bi-check-circle me-1"></i>Active
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No active allocations found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $allocations->links() }}
    </div>
</div>
@endsection
