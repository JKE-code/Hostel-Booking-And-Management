@extends('layouts.admin')

@section('title', 'Campus Room Allotments & Bed Grid — HITAM Hostels')
@section('page_title', 'Campus Room & Bed Allotment Matrix')

@section('admin_content')
<!-- Top Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Filtered Rooms</span>
            <div class="fs-3 fw-bold text-dark mt-1">{{ $stats['total_rooms'] }}</div>
            <small class="text-primary"><i class="bi bi-door-open me-1"></i>Active in selection</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Total Bed Capacity</span>
            <div class="fs-3 fw-bold text-dark mt-1">{{ $stats['total_beds'] }}</div>
            <small class="text-muted"><i class="bi bi-layout-three-columns me-1"></i>Configured beds</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Occupied Beds</span>
            <div class="fs-3 fw-bold text-danger mt-1">{{ $stats['occupied_beds'] }}</div>
            <small class="text-danger"><i class="bi bi-person-fill-check me-1"></i>Allotted to students</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small fw-semibold">Vacant Available Beds</span>
            <div class="fs-3 fw-bold text-success mt-1">{{ $stats['available_beds'] }}</div>
            <small class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Ready for allotment</small>
        </div>
    </div>
</div>

<!-- Allotment Matrix Card with Interactive Filters -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <h5 class="fw-bold text-dark mb-0">Live Room & Bed Allocation Matrix</h5>
            <small class="text-muted">
                {{ $selectedHostel->name ?? 'All Hostels' }} 
                @if($selectedBlock) • {{ $selectedBlock->name }} @endif
                @if($selectedFloorId) • Floor {{ $selectedFloorId }} @endif
            </small>
        </div>
        
        <!-- Filter Form -->
        <form action="{{ route('admin.allocations') }}" method="GET" class="d-flex flex-wrap gap-2">
            <select name="hostel_id" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                @foreach($hostels as $h)
                    <option value="{{ $h->id }}" {{ $selectedHostel && $selectedHostel->id == $h->id ? 'selected' : '' }}>
                        {{ $h->name }} ({{ ucfirst($h->gender_type) }})
                    </option>
                @endforeach
            </select>

            @if($floors->isNotEmpty())
            <select name="floor_id" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                <option value="">All Floors in Wing</option>
                @foreach($floors as $f)
                    <option value="{{ $f->id }}" {{ $selectedFloorId == $f->id ? 'selected' : '' }}>
                        {{ $f->floor_label }} (Floor {{ $f->floor_number }})
                    </option>
                @endforeach
            </select>
            @endif
        </form>
    </div>

    <!-- Rooms & Beds Grid -->
    <div class="row g-3">
        @forelse($rooms as $room)
            @php
                $roomOccupied = $room->beds->where('status', 'occupied')->count();
                $roomTotal = $room->beds->count();
                $isFull = $roomTotal > 0 && $roomOccupied >= $roomTotal;
                $hasVacancy = $roomOccupied < $roomTotal;
            @endphp
            <div class="col-md-6 col-xl-4">
                <div class="p-3 border rounded-3 h-100 {{ $isFull ? 'bg-light' : 'border-success-subtle bg-white' }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Room {{ $room->room_number }}</h6>
                            <small class="text-muted">{{ ucfirst($room->room_type) }} • {{ $room->capacity }}-Sharing</small>
                        </div>
                        @if($isFull)
                            <span class="badge bg-secondary text-white">Full ({{ $roomOccupied }}/{{ $roomTotal }})</span>
                        @elseif($roomOccupied == 0)
                            <span class="badge bg-success text-white">Vacant (0/{{ $roomTotal }})</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ $roomTotal - $roomOccupied }} Available ({{ $roomOccupied }}/{{ $roomTotal }})</span>
                        @endif
                    </div>

                    <div class="pt-2 border-top">
                        @foreach($room->beds as $bed)
                            @php
                                $alloc = $bed->activeAllocation;
                                $student = $alloc?->student;
                            @endphp
                            <div class="d-flex justify-content-between align-items-center py-1 {{ ! $loop->last ? 'border-bottom border-light' : '' }}">
                                <div class="small">
                                    <span class="fw-semibold text-dark">Bed {{ $bed->bed_number }}:</span>
                                    @if($bed->status === 'occupied' && $student)
                                        <span class="text-primary fw-medium ms-1">{{ $student->name }}</span>
                                        <small class="text-muted">({{ $student->roll_number }})</small>
                                    @else
                                        <span class="text-success fw-semibold ms-1"><i class="bi bi-check-circle me-1"></i>Available</span>
                                    @endif
                                </div>
                                <span class="badge bg-{{ $bed->status === 'occupied' ? 'danger-subtle text-danger' : 'success-subtle text-success' }} small">
                                    {{ ucfirst($bed->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-door-closed fs-1 d-block mb-2 text-secondary opacity-50"></i>
                No rooms found for the selected hostel or floor filter.
            </div>
        @endforelse
    </div>
</div>
@endsection
