@extends('layouts.admin')

@section('title', 'Outpass & Leave Approvals — Warden Admin | HITAM Hostels')
@section('page_title', 'Outpass & Leave Approval Queue')

@section('admin_content')
{{-- Summary Metrics --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Total Pending</span>
                <i class="bi bi-hourglass-split text-warning fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">12</h3>
            <div class="small text-warning"><i class="bi bi-clock-history me-1"></i>Awaiting Warden Sign-off</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Approved Today</span>
                <i class="bi bi-check-circle text-success fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">8</h3>
            <div class="small text-success"><i class="bi bi-arrow-up-right me-1"></i>SMS sent to parents</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Students Out</span>
                <i class="bi bi-geo-alt text-primary fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">31</h3>
            <div class="small text-muted"><i class="bi bi-calendar2-check me-1"></i>Active outpasses</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Overdue Returns</span>
                <i class="bi bi-exclamation-triangle text-danger fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">2</h3>
            <div class="small text-danger"><i class="bi bi-bell me-1"></i>Parent contact required</div>
        </div>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
    <div class="card-header bg-transparent border-0 px-4 pt-4 pb-0">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Leave & Outpass Requests</h5>
                <small class="text-muted">Manage student leave permissions — verify, approve, or reject.</small>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <select class="form-select form-select-sm" style="width: auto;">
                    <option selected>All Hostels</option>
                    <option>Boys Hostel</option>
                    <option>Girls Hostel</option>
                </select>
                <select class="form-select form-select-sm" style="width: auto;">
                    <option selected>All Types</option>
                    <option>Weekend Visit</option>
                    <option>Medical</option>
                    <option>Academic</option>
                    <option>Emergency</option>
                </select>
            </div>
        </div>
        <ul class="nav nav-tabs border-0" id="leaveTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold text-forest" data-bs-toggle="tab" data-bs-target="#pending-tab-pane" type="button">
                    Pending <span class="badge bg-warning text-dark ms-1">12</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#approved-tab-pane" type="button">
                    Approved <span class="badge bg-success ms-1">8</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#overdue-tab-pane" type="button">
                    Overdue <span class="badge bg-danger ms-1">2</span>
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body px-4 pb-4">
        <div class="tab-content" id="leaveTabContent">
            {{-- Pending Tab --}}
            <div class="tab-pane fade show active" id="pending-tab-pane" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Hostel & Room</th>
                                <th>Leave Type</th>
                                <th>Destination</th>
                                <th>Dates</th>
                                <th>Parent Phone</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $requests = [
                                ['name'=>'Rahul Sharma','roll'=>'23HT1A0501','dept'=>'CSE','hostel'=>'Boys Hostel','room'=>'B-204','type'=>'Weekend Visit','badge'=>'bg-light text-forest border','dest'=>'Warangal, Telangana','from'=>'20 Sep','to'=>'22 Sep 2026','phone'=>'+91 91234 56789'],
                                ['name'=>'Kavya Reddy','roll'=>'22HT1A0412','dept'=>'ECE','hostel'=>'Girls Hostel','room'=>'G-108','type'=>'Medical Consultation','badge'=>'bg-light text-primary border','dest'=>'Apollo Clinic, Kompally','from'=>'Today 3 PM','to'=>'Today 7 PM','phone'=>'+91 98480 12345'],
                                ['name'=>'Sai Teja','roll'=>'24HT1A1208','dept'=>'IT','hostel'=>'Boys Hostel','room'=>'A-302','type'=>'Academic Hackathon','badge'=>'bg-light text-warning-emphasis border','dest'=>'IIT Hyderabad','from'=>'21 Sep','to'=>'23 Sep 2026','phone'=>'+91 94400 98765'],
                                ['name'=>'Priya Nair','roll'=>'23HT1A0687','dept'=>'MECH','hostel'=>'Girls Hostel','room'=>'G-215','type'=>'Emergency Family','badge'=>'bg-danger-subtle text-danger border','dest'=>'Kochi, Kerala','from'=>'19 Sep','to'=>'25 Sep 2026','phone'=>'+91 99460 11000'],
                                ['name'=>'V. Chaitanya','roll'=>'24HT1A0322','dept'=>'EEE','hostel'=>'Boys Hostel','room'=>'C-110','type'=>'Weekend Visit','badge'=>'bg-light text-forest border','dest'=>'Nalgonda, Telangana','from'=>'20 Sep','to'=>'22 Sep 2026','phone'=>'+91 87654 32100'],
                            ];
                            @endphp
                            @foreach($requests as $req)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $req['name'] }}</div>
                                    <small class="text-muted">{{ $req['roll'] }} &bull; {{ $req['dept'] }}</small>
                                </td>
                                <td>
                                    <span class="text-dark fw-semibold">{{ $req['hostel'] }}</span><br>
                                    <small class="text-muted">Room {{ $req['room'] }}</small>
                                </td>
                                <td><span class="badge {{ $req['badge'] }}">{{ $req['type'] }}</span></td>
                                <td class="small text-muted">{{ $req['dest'] }}</td>
                                <td class="small">{{ $req['from'] }} &rarr; {{ $req['to'] }}</td>
                                <td class="small">{{ $req['phone'] }}</td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <button class="btn btn-sm btn-success" onclick="alert('Outpass Approved! SMS notification sent to parent at {{ $req['phone'] }}.')">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="alert('Outpass Rejected.')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Approved Tab --}}
            <div class="tab-pane fade" id="approved-tab-pane" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Hostel & Room</th>
                                <th>Leave Type</th>
                                <th>Destination</th>
                                <th>Return Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">Aditya Kumar</div>
                                    <small class="text-muted">23HT1A0410 &bull; CSE</small>
                                </td>
                                <td>Boys Hostel • B-204</td>
                                <td><span class="badge bg-light text-forest border">Weekend Visit</span></td>
                                <td class="small text-muted">Hyderabad City</td>
                                <td class="small">22 Sep 2026</td>
                                <td><span class="badge bg-success">Out on Leave</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">Sneha Patel</div>
                                    <small class="text-muted">22HT1A0521 &bull; MBA</small>
                                </td>
                                <td>Girls Hostel • G-302</td>
                                <td><span class="badge bg-light text-primary border">Medical</span></td>
                                <td class="small text-muted">KIMS Hospital, Secunderabad</td>
                                <td class="small">20 Sep 2026</td>
                                <td><span class="badge bg-success">Out on Leave</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Overdue Tab --}}
            <div class="tab-pane fade" id="overdue-tab-pane" role="tabpanel">
                <div class="alert alert-danger border-0 rounded-3 mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div><strong>Overdue Returns:</strong> These students have not returned by their approved date. Immediate parent contact required.</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Hostel & Room</th>
                                <th>Return Date (Missed)</th>
                                <th>Days Overdue</th>
                                <th>Parent Phone</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-danger">
                                <td>
                                    <div class="fw-bold text-dark">N. Ravi Kumar</div>
                                    <small class="text-muted">22HT1A0715 &bull; CSE</small>
                                </td>
                                <td>Boys Hostel • A-108</td>
                                <td class="fw-bold text-danger">15 Sep 2026</td>
                                <td><span class="badge bg-danger">3 Days</span></td>
                                <td>+91 96521 44300</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-danger" onclick="alert('Escalation alert sent to Department HOD and security.')">
                                        <i class="bi bi-bell me-1"></i> Escalate
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
