@extends('layouts.admin')

@section('title', 'Resident Scholars & Admission Desk — HITAM Hostels')
@section('page_title', 'Resident Scholars Directory & Document Verification')

@section('admin_content')
<!-- Top Header & Primary Action -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Resident Scholars & Admission Desk</h4>
        <small class="text-muted">Master database of all campus hostel residents, room allocations, and verified document vaults</small>
    </div>
    <button class="btn text-white fw-semibold d-flex align-items-center gap-2 px-3 py-2 rounded-3 shadow-sm" style="background: #064E3B; transition: all 0.2s ease;" data-bs-toggle="modal" data-bs-target="#admitStudentModal">
        <i class="bi bi-person-plus-fill"></i>
        <span>Admit & Verify Scholar</span>
    </button>
</div>

<!-- Top Stat Cards (Cohesive Forest Green Palette) -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100" style="border-left: 4px solid #064E3B !important;">
            <span class="text-muted small fw-semibold">Total Scholars</span>
            <div class="fs-3 fw-bold text-dark mt-1">{{ $stats['total_students'] }}</div>
            <small class="text-muted"><i class="bi bi-mortarboard me-1"></i>Enrolled in Registry</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100" style="border-left: 4px solid #10B981 !important;">
            <span class="text-muted small fw-semibold">Active Residents</span>
            <div class="fs-3 fw-bold mt-1" style="color: #064E3B;">{{ $stats['active_residents'] }}</div>
            <small class="text-success"><i class="bi bi-patch-check-fill me-1"></i>Verified In-House</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100" style="border-left: 4px solid #D97706 !important;">
            <span class="text-muted small fw-semibold">Whitelisted (Pending)</span>
            <div class="fs-3 fw-bold text-dark mt-1">{{ $stats['whitelisted_pending'] }}</div>
            <small class="text-warning-emphasis"><i class="bi bi-clock-history me-1"></i>Awaiting Student OTP</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100" style="border-left: 4px solid #059669 !important;">
            <span class="text-muted small fw-semibold">Verified Documents</span>
            <div class="fs-3 fw-bold mt-1" style="color: #059669;">{{ $stats['documents_verified'] }}</div>
            <small class="text-success"><i class="bi bi-file-earmark-check me-1"></i>Govt & Medical Validated</small>
        </div>
    </div>
</div>

<!-- Scholars Directory Table Card -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div>
            <h5 class="fw-bold text-dark mb-0">Scholars Registry & Document Vault</h5>
            <small class="text-muted">Inspect resident credentials, view uploaded verification files, or manage room allocations</small>
        </div>

        <!-- Filter & Search Form -->
        <form action="{{ route('admin.students') }}" method="GET" class="d-flex flex-wrap gap-2">
            <select name="status" class="form-select form-select-sm" style="width: auto; border-color: #E2E8F0;" onchange="this.form.submit()">
                <option value="">All Verification Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Verified Only</option>
                <option value="whitelisted" {{ request('status') === 'whitelisted' ? 'selected' : '' }}>Whitelisted (Pending OTP)</option>
            </select>

            <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" name="search" class="form-control" placeholder="Search name, roll, branch..." value="{{ request('search') }}" style="border-color: #E2E8F0;">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </div>
            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('admin.students') }}" class="btn btn-sm btn-link text-muted">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Student Scholar</th>
                    <th>Roll Number</th>
                    <th>Department / Year</th>
                    <th>Hostel & Room Bed</th>
                    <th>Document Vault</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                @php
                    $alloc = $student->activeAllocation;
                    $bed = $alloc?->bed;
                    $room = $bed?->room;
                    $hostel = $room?->floor?->block?->hostel;
                    $hasDocs = $student->id_proof_url || $student->admission_letter_url || $student->medical_cert_url || $student->photo_url;
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($student->photo_url)
                                <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="rounded-circle object-fit-cover" style="width: 38px; height: 38px;">
                            @else
                                <div class="rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #E8F5E9; color: #064E3B;">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold text-dark">{{ $student->name }}</div>
                                <small class="text-muted">{{ $student->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border font-monospace">{{ $student->roll_number }}</span>
                    </td>
                    <td>
                        <div class="text-dark fw-medium">{{ $student->department }}</div>
                        <small class="text-muted">Year {{ $student->year_of_study ?? 1 }}</small>
                    </td>
                    <td>
                        @if($room && $bed)
                            <div class="fw-semibold text-dark">{{ $hostel->name ?? 'Campus Hostel' }}</div>
                            <small class="text-muted">Room {{ $room->room_number }} • Bed {{ $bed->bed_number }}</small>
                        @else
                            <span class="badge bg-light text-muted border">Unallocated</span>
                        @endif
                    </td>
                    <td>
                        @if($student->document_verified_at)
                            <span class="badge bg-success-subtle border border-success-subtle" style="color: #064E3B;">
                                <i class="bi bi-file-earmark-check me-1"></i>Verified Docs
                            </span>
                        @elseif($hasDocs)
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle">
                                <i class="bi bi-file-earmark-arrow-up me-1"></i>Pending Review
                            </span>
                        @else
                            <span class="badge bg-light text-muted border">No Uploads</span>
                        @endif
                    </td>
                    <td>
                        @if($student->onboarding_status === 'active')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                <i class="bi bi-check-circle-fill me-1"></i>Active Resident
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-1">
                                <i class="bi bi-clock-history me-1"></i>Whitelisted (Pending OTP)
                            </span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <button class="btn btn-sm btn-outline-dark d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#studentModal{{ $student->id }}" title="View Profile & Documents">
                                <i class="bi bi-person-lines-fill"></i>
                                <span>Profile & Docs</span>
                            </button>
                            <button class="btn btn-sm text-white d-inline-flex align-items-center gap-1" style="background: #064E3B;" data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $student->id }}" title="Edit Scholar Details">
                                <i class="bi bi-pencil-square"></i>
                                <span>Edit</span>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Scholar Profile & Documents Inspection Modal -->
                <div class="modal fade" id="studentModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                            <!-- Modal Header -->
                            <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, #064E3B 0%, #022C22 100%);">
                                <div class="d-flex align-items-center gap-3">
                                    @if($student->photo_url)
                                        <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="rounded-circle border border-2 border-white object-fit-cover" style="width: 52px; height: 52px;">
                                    @else
                                        <div class="rounded-circle bg-white fw-bold d-flex align-items-center justify-content-center text-forest" style="width: 52px; height: 52px; font-size: 1.25rem;">
                                            {{ strtoupper(substr($student->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="modal-title fw-bold text-white mb-0">{{ $student->name }}</h5>
                                        <small class="text-white-50 font-monospace">{{ $student->roll_number }} • {{ $student->department }} Year {{ $student->year_of_study }}</small>
                                    </div>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body p-4" style="max-height: calc(85vh - 140px); overflow-y: auto;">
                                <!-- Personal & Guardian Details -->
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge text-success me-2"></i>Scholar Profile</h6>
                                <div class="row g-3 small mb-4">
                                    <div class="col-sm-6 col-md-4">
                                        <div class="text-muted">Institutional Email:</div>
                                        <div class="fw-semibold text-dark">{{ $student->email }}</div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="text-muted">Phone Number:</div>
                                        <div class="fw-semibold text-dark">{{ $student->phone }}</div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="text-muted">Gender & Blood Group:</div>
                                        <div class="fw-semibold text-dark">{{ ucfirst($student->gender) }} • {{ $student->blood_group ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="text-muted">Parent / Guardian:</div>
                                        <div class="fw-semibold text-dark">{{ $student->parent_name }} ({{ $student->parent_phone }})</div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="text-muted">Emergency Contact:</div>
                                        <div class="fw-semibold text-dark">{{ $student->emergency_contact ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="text-muted">Room Allotment:</div>
                                        <div class="fw-semibold text-dark">
                                            @if($room && $bed)
                                                {{ $hostel->name ?? 'Hostel' }} (Rm {{ $room->room_number }}, Bed {{ $bed->bed_number }})
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Verification Document Vault Cards (Cloudinary Ready) -->
                                <div class="d-flex justify-content-between align-items-center mb-3 pt-3 border-top">
                                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-lock2 text-success me-2"></i>Verification Document Vault</h6>
                                    @if($student->document_verified_at)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-all me-1"></i>Verified on {{ $student->document_verified_at->format('M d, Y') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="row g-3 mb-4">
                                    <!-- ID Proof Card -->
                                    <div class="col-sm-6 col-md-3">
                                        <div class="p-3 border rounded-3 text-center h-100 bg-light">
                                            <i class="bi bi-card-heading fs-2 {{ $student->id_proof_url ? 'text-success' : 'text-muted opacity-50' }} d-block mb-1"></i>
                                            <div class="small fw-bold text-dark">Govt ID / Aadhaar</div>
                                            @if($student->id_proof_url)
                                                <a href="{{ $student->id_proof_url }}" target="_blank" class="btn btn-sm btn-outline-success mt-2 py-0 px-2" style="font-size: 0.75rem;">
                                                    <i class="bi bi-eye me-1"></i>View File
                                                </a>
                                            @else
                                                <small class="text-muted d-block mt-1">Not Uploaded</small>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Admission Letter Card -->
                                    <div class="col-sm-6 col-md-3">
                                        <div class="p-3 border rounded-3 text-center h-100 bg-light">
                                            <i class="bi bi-award fs-2 {{ $student->admission_letter_url ? 'text-success' : 'text-muted opacity-50' }} d-block mb-1"></i>
                                            <div class="small fw-bold text-dark">Admission Letter</div>
                                            @if($student->admission_letter_url)
                                                <a href="{{ $student->admission_letter_url }}" target="_blank" class="btn btn-sm btn-outline-success mt-2 py-0 px-2" style="font-size: 0.75rem;">
                                                    <i class="bi bi-eye me-1"></i>View File
                                                </a>
                                            @else
                                                <small class="text-muted d-block mt-1">Not Uploaded</small>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Medical Certificate Card -->
                                    <div class="col-sm-6 col-md-3">
                                        <div class="p-3 border rounded-3 text-center h-100 bg-light">
                                            <i class="bi bi-heart-pulse fs-2 {{ $student->medical_cert_url ? 'text-success' : 'text-muted opacity-50' }} d-block mb-1"></i>
                                            <div class="small fw-bold text-dark">Medical Fitness</div>
                                            @if($student->medical_cert_url)
                                                <a href="{{ $student->medical_cert_url }}" target="_blank" class="btn btn-sm btn-outline-success mt-2 py-0 px-2" style="font-size: 0.75rem;">
                                                    <i class="bi bi-eye me-1"></i>View File
                                                </a>
                                            @else
                                                <small class="text-muted d-block mt-1">Not Uploaded</small>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Student Photo Card -->
                                    <div class="col-sm-6 col-md-3">
                                        <div class="p-3 border rounded-3 text-center h-100 bg-light">
                                            <i class="bi bi-image fs-2 {{ $student->photo_url ? 'text-success' : 'text-muted opacity-50' }} d-block mb-1"></i>
                                            <div class="small fw-bold text-dark">Passport Photo</div>
                                            @if($student->photo_url)
                                                <a href="{{ $student->photo_url }}" target="_blank" class="btn btn-sm btn-outline-success mt-2 py-0 px-2" style="font-size: 0.75rem;">
                                                    <i class="bi bi-eye me-1"></i>View Photo
                                                </a>
                                            @else
                                                <small class="text-muted d-block mt-1">Not Uploaded</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Verification Notes -->
                                @if($student->verification_notes)
                                    <div class="p-3 rounded-3 bg-light border small text-muted mb-4">
                                        <strong class="text-dark">Verification Remarks:</strong> {{ $student->verification_notes }}
                                    </div>
                                @endif

                                <!-- Quick Upload / Re-verify Documents Form -->
                                <div class="card border-0 bg-light p-3 rounded-3">
                                    <button class="btn btn-sm btn-link text-decoration-none text-dark fw-semibold p-0 text-start d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#uploadDocsCollapse{{ $student->id }}">
                                        <span><i class="bi bi-cloud-arrow-up me-1 text-success"></i>Upload or Update Documents for this Scholar</span>
                                        <i class="bi bi-chevron-down"></i>
                                    </button>

                                    <div class="collapse mt-3" id="uploadDocsCollapse{{ $student->id }}">
                                        <form action="{{ route('admin.students.documents', $student->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row g-2 small">
                                                <div class="col-md-6">
                                                    <label class="form-label mb-1">Government ID (Aadhaar / Passport PDF/IMG):</label>
                                                    <input type="file" name="id_proof" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label mb-1">College Admission Letter (PDF/IMG):</label>
                                                    <input type="file" name="admission_letter" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label mb-1">Medical Fitness Certificate (PDF/IMG):</label>
                                                    <input type="file" name="medical_cert" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label mb-1">Student Passport Photo (JPG/PNG):</label>
                                                    <input type="file" name="photo" class="form-control form-control-sm" accept="image/*">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label mb-1">Verification Remarks:</label>
                                                    <input type="text" name="verification_notes" class="form-control form-control-sm" placeholder="e.g. Originals verified physically during hostel check-in" value="{{ $student->verification_notes }}">
                                                </div>
                                                <div class="col-12 mt-3 d-flex justify-content-between align-items-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="mark_verified" value="1" id="markVer{{ $student->id }}" checked>
                                                        <label class="form-check-label" for="markVer{{ $student->id }}">Mark documents officially verified</label>
                                                    </div>
                                                    <button type="submit" class="btn btn-sm text-white px-3" style="background: #064E3B;">Save Documents</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light p-3">
                                <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $student->id }}">
                                    <i class="bi bi-pencil-square me-1"></i>Edit Scholar Details
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close Window</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal: Edit Resident Scholar Details -->
                <div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1" aria-labelledby="editStudentModalLabel{{ $student->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="max-height: 90vh; display: flex; flex-direction: column;">
                            @csrf
                            @method('PUT')

                            <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, #064E3B 0%, #022C22 100%); flex-shrink: 0;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-white fw-bold d-flex align-items-center justify-content-center text-forest" style="width: 48px; height: 48px; font-size: 1.15rem; color: #064E3B;">
                                        <i class="bi bi-pencil-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="modal-title fw-bold text-white mb-0" id="editStudentModalLabel{{ $student->id }}">
                                            Edit Scholar: {{ $student->name }}
                                        </h5>
                                        <small class="text-white-50 font-monospace">Roll No: {{ $student->roll_number }} • ID #{{ $student->id }}</small>
                                    </div>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body p-4" style="overflow-y: auto !important; max-height: calc(90vh - 140px); flex: 1 1 auto;">
                                <!-- Section 1: Academic & Contact Info -->
                                <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="color: #064E3B !important;">
                                    1. Personal & Academic Credentials
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark mb-1">Scholar Full Name *</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $student->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark mb-1">Institutional Email *</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" required>
                                        <small class="text-muted" style="font-size: 0.72rem;">Updating this also updates the student's portal login email.</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold text-dark mb-1">Roll Number *</label>
                                        <input type="text" name="roll_number" class="form-control font-monospace text-uppercase" value="{{ old('roll_number', $student->roll_number) }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold text-dark mb-1">Department / Branch *</label>
                                        <select name="department" class="form-select" required>
                                            @foreach(['CSE', 'ECE', 'EEE', 'IT', 'MECH', 'CIVIL', 'MBA', 'MCA', 'OTHER'] as $dept)
                                                <option value="{{ $dept }}" {{ old('department', $student->department) === $dept ? 'selected' : '' }}>
                                                    {{ $dept }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold text-dark mb-1">Year of Study *</label>
                                        <select name="year_of_study" class="form-select" required>
                                            @for($y = 1; $y <= 4; $y++)
                                                <option value="{{ $y }}" {{ old('year_of_study', $student->year_of_study) == $y ? 'selected' : '' }}>
                                                    Year {{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold text-dark mb-1">Mobile Phone *</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold text-dark mb-1">Gender *</label>
                                        <select name="gender" class="form-select" required>
                                            <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $student->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold text-dark mb-1">Blood Group</label>
                                        <input type="text" name="blood_group" class="form-control text-uppercase" value="{{ old('blood_group', $student->blood_group) }}" placeholder="e.g. O+, B+, AB-">
                                    </div>
                                </div>

                                <!-- Section 2: Guardian Details -->
                                <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="color: #064E3B !important;">
                                    2. Guardian & Residence Details
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark mb-1">Parent / Guardian Name *</label>
                                        <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', $student->parent_name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark mb-1">Parent Mobile Phone *</label>
                                        <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone', $student->parent_phone) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark mb-1">Secondary Emergency Contact</label>
                                        <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $student->emergency_contact) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-dark mb-1">Permanent Home Address</label>
                                        <input type="text" name="permanent_address" class="form-control" value="{{ old('permanent_address', $student->permanent_address) }}">
                                    </div>
                                </div>

                                <!-- Section 3: Status -->
                                <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="color: #064E3B !important;">
                                    3. Resident Status & Onboarding Mode
                                </h6>
                                <div class="row g-3 mb-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold text-dark mb-1">Verification / Residency Status *</label>
                                        <div class="p-3 border rounded-3 bg-light">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="onboarding_status" id="editStatusActive{{ $student->id }}" value="active" {{ old('onboarding_status', $student->onboarding_status) === 'active' ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold text-dark small" for="editStatusActive{{ $student->id }}">
                                                            Direct Verified Resident (Active)
                                                        </label>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Account is active and can log into the student portal.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="onboarding_status" id="editStatusWhitelisted{{ $student->id }}" value="whitelisted" {{ old('onboarding_status', $student->onboarding_status) === 'whitelisted' ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold text-dark small" for="editStatusWhitelisted{{ $student->id }}">
                                                            Whitelisted (Pending OTP)
                                                        </label>
                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Requires self-verification at /signup.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer bg-light p-3" style="flex-shrink: 0;">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn text-white fw-semibold px-4" style="background: #064E3B;">
                                    <i class="bi bi-check2-circle me-1"></i>Save Scholar Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                        No student scholars found matching the specified filters.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($students->hasPages())
    <div class="mt-4">
        {{ $students->links() }}
    </div>
    @endif
</div>

<!-- Modal: Admit New Resident Scholar (Multi-Section Form with Document Uploads) -->
<div class="modal fade" id="admitStudentModal" tabindex="-1" aria-labelledby="admitStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="max-height: 90vh; display: flex; flex-direction: column;">
            @csrf
            <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, #064E3B 0%, #022C22 100%); flex-shrink: 0;">
                <div>
                    <h5 class="modal-title fw-bold text-white mb-0" id="admitStudentModalLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>Admit New Resident Scholar
                    </h5>
                    <small class="text-white-50">Register scholar details, assign hostel room bed, and upload verification documents</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="overflow-y: auto !important; max-height: calc(90vh - 140px); flex: 1 1 auto;">
                <!-- Section 1: Academic & Personal Info -->
                <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="color: #064E3B !important;">
                    1. Personal & Academic Information
                </h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-dark mb-1">Scholar Full Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Vikramaditya Reddy" required>
                    </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">Institutional Email *</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. vikram.reddy@student.hitam.org" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark mb-1">College Roll Number *</label>
                            <input type="text" name="roll_number" class="form-control font-monospace text-uppercase" placeholder="e.g. 24HT1A0589" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark mb-1">Department / Branch *</label>
                            <select name="department" class="form-select" required>
                                <option value="CSE" selected>CSE (Computer Science)</option>
                                <option value="ECE">ECE (Electronics & Comm)</option>
                                <option value="EEE">EEE (Electrical)</option>
                                <option value="IT">IT (Information Tech)</option>
                                <option value="MECH">MECH (Mechanical)</option>
                                <option value="CIVIL">CIVIL (Civil)</option>
                                <option value="MBA">MBA</option>
                                <option value="MCA">MCA</option>
                                <option value="OTHER">Other Academic Branch</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark mb-1">Year of Study *</label>
                            <select name="year_of_study" class="form-select" required>
                                <option value="1" selected>1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark mb-1">Mobile Phone *</label>
                            <input type="text" name="phone" class="form-control" placeholder="10-digit mobile" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark mb-1">Gender *</label>
                            <select name="gender" class="form-select" required>
                                <option value="male" selected>Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark mb-1">Blood Group</label>
                            <input type="text" name="blood_group" class="form-control text-uppercase" placeholder="e.g. O+, B+, AB-">
                        </div>
                    </div>

                    <!-- Section 2: Parent & Emergency Details -->
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="color: #064E3B !important;">
                        2. Parent & Emergency Contact Details
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">Parent / Guardian Name *</label>
                            <input type="text" name="parent_name" class="form-control" placeholder="e.g. K. R. Reddy" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">Parent Mobile Phone *</label>
                            <input type="text" name="parent_phone" class="form-control" placeholder="10-digit emergency phone" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">Secondary Emergency Contact</label>
                            <input type="text" name="emergency_contact" class="form-control" placeholder="Relative / Local guardian number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">Permanent Home Address</label>
                            <input type="text" name="permanent_address" class="form-control" placeholder="City, District, State, Pincode">
                        </div>
                    </div>

                    <!-- Section 3: Onboarding Mode -->
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="color: #064E3B !important;">
                        3. Onboarding Mode & Verification Status
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-dark mb-1">Verification Status *</label>
                            <div class="p-3 border rounded-3 bg-light">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="onboarding_status" id="statusActive" value="active" checked>
                                            <label class="form-check-label fw-semibold text-dark small" for="statusActive">
                                                Direct Verified Admission (Active Resident)
                                            </label>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Creates student login account immediately with verified credentials.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="onboarding_status" id="statusWhitelisted" value="whitelisted">
                                            <label class="form-check-label fw-semibold text-dark small" for="statusWhitelisted">
                                                Pre-Whitelist for Student Self-OTP Signup
                                            </label>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Scholar self-registers at /signup using Brevo 6-digit OTP verification.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Document Verification Vault (Cloudinary-Ready) -->
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="color: #064E3B !important;">
                        4. Document Verification Vault (Stored securely, Cloudinary-ready)
                    </h6>
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">Government ID / Aadhaar (PDF/JPG/PNG)</label>
                            <input type="file" name="id_proof" class="form-control form-control-sm">
                            <small class="text-muted" style="font-size: 0.72rem;">Max 5MB. Verified identity proof.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">College Admission Letter (PDF/JPG/PNG)</label>
                            <input type="file" name="admission_letter" class="form-control form-control-sm">
                            <small class="text-muted" style="font-size: 0.72rem;">HITAM allotment / admission authorization.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">Medical Fitness / Undertaking (PDF/JPG/PNG)</label>
                            <input type="file" name="medical_cert" class="form-control form-control-sm">
                            <small class="text-muted" style="font-size: 0.72rem;">Hostel rule compliance & medical declaration.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark mb-1">Scholar Passport Photograph (JPG/PNG)</label>
                            <input type="file" name="photo" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted" style="font-size: 0.72rem;">Recent colored passport photograph.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-dark mb-1">Verification Remarks / Notes</label>
                            <textarea name="verification_notes" class="form-control form-control-sm" rows="2" placeholder="e.g. Physical documents verified by Warden during admission. Originals checked."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light p-3" style="flex-shrink: 0;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn text-white fw-semibold px-4" style="background: #064E3B;">
                        <i class="bi bi-check2-circle me-1"></i>Confirm Admission & Save
                    </button>
                </div>
            </form>
    </div>
</div>
@endsection
