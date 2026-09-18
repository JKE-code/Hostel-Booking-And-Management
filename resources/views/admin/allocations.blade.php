@extends('layouts.admin')

@section('title', 'Room Allotments & Bed Grid — Warden Admin')
@section('page_title', 'Hostel Room Allotments & Bed Grid')

@section('admin_content')
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-0">Live Room & Bed Allocation Matrix</h5>
            <small class="text-muted">Boys Hostel • Block B • Floor 2</small>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" style="width: auto;">
                <option selected>Boys Hostel (Block B)</option>
                <option>Boys Hostel (Block A)</option>
                <option>Girls Hostel</option>
            </select>
            <select class="form-select form-select-sm" style="width: auto;">
                <option selected>Floor 2</option>
                <option>Floor 1</option>
                <option>Floor 3</option>
                <option>Floor 4</option>
            </select>
        </div>
    </div>

    <!-- Interactive Room Cards Grid -->
    <div class="row g-3">
        <!-- Room B-201 -->
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-dark mb-0">Room B-201 (2-Sharing)</h6>
                    <span class="badge bg-success text-white">Full (2/2)</span>
                </div>
                <div class="small text-muted mb-1"><i class="bi bi-person-fill text-success"></i> Bed 01: V. Karthik (CSE)</div>
                <div class="small text-muted"><i class="bi bi-person-fill text-success"></i> Bed 02: P. Sandeep (ECE)</div>
            </div>
        </div>

        <!-- Room B-202 -->
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-dark mb-0">Room B-202 (2-Sharing)</h6>
                    <span class="badge bg-warning text-dark">1 Vacancy (1/2)</span>
                </div>
                <div class="small text-muted mb-1"><i class="bi bi-person-fill text-success"></i> Bed 01: M. Akhil (IT)</div>
                <div class="small text-success fw-semibold"><i class="bi bi-plus-circle-fill"></i> Bed 02: Available</div>
            </div>
        </div>

        <!-- Room B-204 (Rahul's Room) -->
        <div class="col-md-4">
            <div class="p-3 border rounded-3 border-success bg-success-subtle bg-opacity-10">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-forest mb-0">Room B-204 (2-Sharing)</h6>
                    <span class="badge bg-success text-white">Full (2/2)</span>
                </div>
                <div class="small text-dark fw-semibold mb-1"><i class="bi bi-person-fill text-forest"></i> Bed 01: Rahul Sharma (23HT1A0501)</div>
                <div class="small text-muted"><i class="bi bi-person-fill text-forest"></i> Bed 02: Aditya Kumar (23HT1A0410)</div>
            </div>
        </div>

        <!-- Room B-205 -->
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-dark mb-0">Room B-205 (3-Sharing)</h6>
                    <span class="badge bg-success text-white">Full (3/3)</span>
                </div>
                <div class="small text-muted mb-1"><i class="bi bi-person-fill text-success"></i> Bed 01: N. Rakesh</div>
                <div class="small text-muted mb-1"><i class="bi bi-person-fill text-success"></i> Bed 02: T. Varun</div>
                <div class="small text-muted"><i class="bi bi-person-fill text-success"></i> Bed 03: S. Charan</div>
            </div>
        </div>

        <!-- Room B-206 -->
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-dark mb-0">Room B-206 (2-Sharing)</h6>
                    <span class="badge bg-danger text-white">Maintenance</span>
                </div>
                <div class="small text-danger"><i class="bi bi-cone-striped"></i> Repainting & Door Polish in progress</div>
            </div>
        </div>

        <!-- Room B-207 -->
        <div class="col-md-4">
            <div class="p-3 border rounded-3 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-dark mb-0">Room B-207 (3-Sharing)</h6>
                    <span class="badge bg-warning text-dark">2 Vacancies (1/3)</span>
                </div>
                <div class="small text-muted mb-1"><i class="bi bi-person-fill text-success"></i> Bed 01: K. Nikhil</div>
                <div class="small text-success fw-semibold mb-1"><i class="bi bi-plus-circle-fill"></i> Bed 02: Available</div>
                <div class="small text-success fw-semibold"><i class="bi bi-plus-circle-fill"></i> Bed 03: Available</div>
            </div>
        </div>
    </div>
</div>
@endsection
