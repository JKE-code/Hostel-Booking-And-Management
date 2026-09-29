@extends('layouts.warden')

@section('title', 'Student Registry & Whitelisting — HITAM Hostels')
@section('page_title', 'Resident Scholars Registry & 2-Way Whitelisting')

@section('warden_content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-dark">Admitted Student Resident Registry</h5>
        <small class="text-muted">Pre-whitelist students here to authorize their 2-way OTP signup verification.</small>
    </div>
    <button class="btn btn-hitam-green fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#whitelistStudentModal">
        <i class="bi bi-person-plus-fill"></i>
        <span>Whitelist New Student</span>
    </button>
</div>

<!-- Filter Bar -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form action="{{ route('warden.students') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, roll number, email, or phone..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select name="status" class="form-select">
                <option value="">-- All Onboarding Statuses --</option>
                <option value="whitelisted" {{ request('status') === 'whitelisted' ? 'selected' : '' }}>Whitelisted (Pending Verification)</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active (Verified Resident)</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="department" class="form-select">
                <option value="">-- Department --</option>
                @foreach(['CSE', 'ECE', 'EEE', 'MECH', 'CIVIL', 'IT', 'MBA', 'MCA'] as $dept)
                    <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100">Filter</button>
            <a href="{{ route('warden.students') }}" class="btn btn-light border"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>

<!-- Students Table -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th>Scholar Name</th>
                    <th>Roll Number</th>
                    <th>Dept & Year</th>
                    <th>Contact Info</th>
                    <th>Parent / Guardian</th>
                    <th>Status</th>
                    <th>Bed Allotment</th>
                    <th>Document Vault</th>
                </tr>
            </thead>
            <tbody class="small">
                @forelse($students as $s)
                <tr>
                    <td>
                        <div class="fw-bold text-dark">{{ $s->name }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $s->email }}</div>
                    </td>
                    <td><span class="badge bg-light text-dark border font-monospace">{{ $s->roll_number }}</span></td>
                    <td>{{ $s->department }} (Yr {{ $s->year_of_study }})</td>
                    <td>
                        <div><i class="bi bi-phone me-1 text-muted"></i>{{ $s->phone }}</div>
                    </td>
                    <td>
                        <div>{{ $s->parent_name }}</div>
                        <small class="text-muted">{{ $s->parent_phone }}</small>
                    </td>
                    <td>
                        @if($s->onboarding_status === 'active')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                <i class="bi bi-check-circle me-1"></i>Active Resident
                            </span>
                        @elseif($s->onboarding_status === 'whitelisted')
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                <i class="bi bi-clock-history me-1"></i>Whitelisted (Pending OTP)
                            </span>
                        @else
                            <span class="badge bg-secondary px-2 py-1">{{ ucfirst($s->onboarding_status) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($s->activeAllocation)
                            <span class="badge bg-light text-dark border fw-semibold">
                                Room {{ $s->activeAllocation->bed->room->room_number ?? 'Assigned' }} (Bed {{ $s->activeAllocation->bed->bed_number }})
                            </span>
                        @else
                            <span class="text-muted" style="font-size: 0.75rem;">Awaiting Bed</span>
                        @endif
                    </td>
                    <td>
                        @if($s->id_proof_url || $s->admission_letter_url || $s->medical_cert_url || $s->photo_url)
                            <div class="d-flex gap-1">
                                @if($s->id_proof_url)
                                    <a href="{{ $s->id_proof_url }}" target="_blank" class="badge bg-success text-white text-decoration-none" title="View Govt ID">ID</a>
                                @endif
                                @if($s->admission_letter_url)
                                    <a href="{{ $s->admission_letter_url }}" target="_blank" class="badge bg-primary text-white text-decoration-none" title="View Admission Letter">Adm</a>
                                @endif
                                @if($s->medical_cert_url)
                                    <a href="{{ $s->medical_cert_url }}" target="_blank" class="badge bg-info text-dark text-decoration-none" title="View Medical Certificate">Med</a>
                                @endif
                                @if($s->photo_url)
                                    <a href="{{ $s->photo_url }}" target="_blank" class="badge bg-secondary text-white text-decoration-none" title="View Photo">Photo</a>
                                @endif
                            </div>
                        @else
                            <span class="text-muted" style="font-size: 0.75rem;">No Uploads</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No student records found matching search criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $students->links() }}
    </div>
</div>

<!-- Modal: Whitelist Admitted Student -->
<div class="modal fade" id="whitelistStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-person-plus-fill text-forest me-2"></i>Whitelist Admitted Scholar for Hostel Access
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('warden.students.whitelist') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-info-subtle small mb-4">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Once whitelisted, the scholar can visit the <strong>/signup</strong> page, enter their email or phone, receive their 2-way verification OTP, and create their personal password.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">Student Full Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Aditya Varma" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">Institutional / Personal Email *</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. aditya.varma@student.hitam.org" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">College Roll Number *</label>
                            <input type="text" name="roll_number" class="form-control" placeholder="e.g. 25HT1A0599" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">Mobile Number *</label>
                            <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark">Department *</label>
                            <select name="department" class="form-select" required>
                                <option value="CSE" selected>CSE</option>
                                <option value="ECE">ECE</option>
                                <option value="EEE">EEE</option>
                                <option value="MECH">MECH</option>
                                <option value="CIVIL">CIVIL</option>
                                <option value="IT">IT</option>
                                <option value="MBA">MBA</option>
                                <option value="MCA">MCA</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark">Year of Study *</label>
                            <select name="year_of_study" class="form-select" required>
                                <option value="1" selected>Year 1</option>
                                <option value="2">Year 2</option>
                                <option value="3">Year 3</option>
                                <option value="4">Year 4</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-dark">Gender *</label>
                            <select name="gender" class="form-select" required>
                                <option value="male" selected>Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">Parent / Guardian Name *</label>
                            <input type="text" name="parent_name" class="form-control" placeholder="e.g. S. Varma" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">Parent Contact Phone *</label>
                            <input type="text" name="parent_phone" class="form-control" placeholder="+91 98765 43200" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">Blood Group</label>
                            <input type="text" name="blood_group" class="form-control" placeholder="e.g. O+, B+, A+">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-dark">Permanent Residential Address</label>
                            <input type="text" name="permanent_address" class="form-control" placeholder="e.g. Hyderabad, Telangana">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-hitam-green fw-bold">Whitelist Scholar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
