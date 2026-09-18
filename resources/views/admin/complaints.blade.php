@extends('layouts.admin')

@section('title', 'Maintenance & Complaint Tickets — Warden Admin | HITAM Hostels')
@section('page_title', 'Maintenance Tickets & Complaint Management')

@section('admin_content')
{{-- Summary Metrics --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Open Tickets</span>
                <i class="bi bi-tools text-danger fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">5</h3>
            <div class="small text-danger"><i class="bi bi-arrow-up-right me-1"></i>Requires assignment</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">In Progress</span>
                <i class="bi bi-gear-wide-connected text-warning fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">7</h3>
            <div class="small text-warning"><i class="bi bi-person-gear me-1"></i>Technician assigned</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Resolved Today</span>
                <i class="bi bi-check-circle text-success fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">4</h3>
            <div class="small text-success"><i class="bi bi-calendar-check me-1"></i>Marked complete</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Avg. Resolution</span>
                <i class="bi bi-stopwatch text-primary fs-4"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">1.8 days</h3>
            <div class="small text-primary"><i class="bi bi-graph-up me-1"></i>Down from 2.3 days</div>
        </div>
    </div>
</div>

{{-- Ticket Table --}}
<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-header bg-transparent border-0 px-4 pt-4 pb-0">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">Maintenance & Complaint Tickets</h5>
                <small class="text-muted">Assign technicians, update status, and close resolved issues.</small>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <select class="form-select form-select-sm" style="width: auto;">
                    <option selected>All Categories</option>
                    <option>Electrical</option>
                    <option>Plumbing</option>
                    <option>Cleaning</option>
                    <option>Internet</option>
                    <option>Furniture</option>
                    <option>AC / Fan</option>
                </select>
                <select class="form-select form-select-sm" style="width: auto;">
                    <option selected>All Status</option>
                    <option>Open</option>
                    <option>In Progress</option>
                    <option>Resolved</option>
                </select>
            </div>
        </div>
        <ul class="nav nav-tabs border-0" id="ticketTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold text-forest" data-bs-toggle="tab" data-bs-target="#open-tab" type="button">
                    Open <span class="badge bg-danger ms-1">5</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#inprogress-tab" type="button">
                    In Progress <span class="badge bg-warning text-dark ms-1">7</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#resolved-tab" type="button">
                    Resolved <span class="badge bg-success ms-1">4</span>
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body px-4 pb-4">
        <div class="tab-content" id="ticketTabContent">

            {{-- Open Tickets --}}
            <div class="tab-pane fade show active" id="open-tab" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ticket #</th>
                                <th>Reported By</th>
                                <th>Room</th>
                                <th>Category</th>
                                <th>Issue Description</th>
                                <th>Filed</th>
                                <th>Priority</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $tickets = [
                                ['id'=>'TKT-0481','name'=>'Rahul Sharma','roll'=>'23HT1A0501','room'=>'B-204','cat'=>'Electrical','icon'=>'bi-lightning-charge','badge'=>'bg-warning text-dark','desc'=>'Tube light flickering since 3 days. Affects sleep and study.','filed'=>'17 Sep','priority'=>'Medium','pbadge'=>'bg-warning text-dark'],
                                ['id'=>'TKT-0482','name'=>'Sai Teja','roll'=>'24HT1A1208','room'=>'A-302','cat'=>'Plumbing','icon'=>'bi-droplet','badge'=>'bg-primary text-white','desc'=>'Bathroom tap dripping continuously. Water wastage.','filed'=>'17 Sep','priority'=>'Low','pbadge'=>'bg-secondary text-white'],
                                ['id'=>'TKT-0483','name'=>'Priya Nair','roll'=>'23HT1A0687','room'=>'G-215','cat'=>'AC / Fan','icon'=>'bi-wind','badge'=>'bg-info text-dark','desc'=>'Ceiling fan making loud rattling noise during operation.','filed'=>'18 Sep','priority'=>'High','pbadge'=>'bg-danger text-white'],
                                ['id'=>'TKT-0484','name'=>'V. Chaitanya','roll'=>'24HT1A0322','room'=>'C-110','cat'=>'Internet','icon'=>'bi-wifi','badge'=>'bg-dark text-white','desc'=>'Wi-Fi disconnects every 30 minutes. Cannot attend online classes.','filed'=>'18 Sep','priority'=>'High','pbadge'=>'bg-danger text-white'],
                                ['id'=>'TKT-0485','name'=>'M. Akhil','roll'=>'23HT1A0350','room'=>'B-202','cat'=>'Furniture','icon'=>'bi-table','badge'=>'bg-light text-dark border','desc'=>'Study table leg is broken. Table wobbles and unsafe to use.','filed'=>'18 Sep','priority'=>'Medium','pbadge'=>'bg-warning text-dark'],
                            ];
                            @endphp
                            @foreach($tickets as $t)
                            <tr>
                                <td><span class="badge bg-light text-dark border fw-bold font-monospace">{{ $t['id'] }}</span></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $t['name'] }}</div>
                                    <small class="text-muted">{{ $t['roll'] }}</small>
                                </td>
                                <td class="fw-semibold text-forest">{{ $t['room'] }}</td>
                                <td>
                                    <span class="badge {{ $t['badge'] }}">
                                        <i class="bi {{ $t['icon'] }} me-1"></i>{{ $t['cat'] }}
                                    </span>
                                </td>
                                <td class="small" style="max-width: 200px;">{{ $t['desc'] }}</td>
                                <td class="small text-muted">{{ $t['filed'] }}</td>
                                <td><span class="badge {{ $t['pbadge'] }}">{{ $t['priority'] }}</span></td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <button class="btn btn-sm btn-outline-primary" onclick="alert('Technician assigned to {{ $t['id'] }}. Status updated to In Progress.')">
                                            <i class="bi bi-person-check me-1"></i>Assign
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="alert('Ticket {{ $t['id'] }} closed.')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- In Progress --}}
            <div class="tab-pane fade" id="inprogress-tab" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ticket #</th>
                                <th>Room</th>
                                <th>Category</th>
                                <th>Issue</th>
                                <th>Assigned To</th>
                                <th>ETA</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-light text-dark border fw-bold font-monospace">TKT-0478</span></td>
                                <td class="fw-semibold text-forest">B-301</td>
                                <td><span class="badge bg-warning text-dark"><i class="bi bi-lightning-charge me-1"></i>Electrical</span></td>
                                <td class="small">Main power socket not working</td>
                                <td>
                                    <div class="fw-semibold small">R. Venkataramaiah</div>
                                    <small class="text-muted">Senior Electrician</small>
                                </td>
                                <td><span class="badge bg-warning text-dark">Today EOD</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-success" onclick="alert('Ticket TKT-0478 marked as Resolved. Student notified.')">
                                        <i class="bi bi-check-lg me-1"></i>Mark Resolved
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-light text-dark border fw-bold font-monospace">TKT-0476</span></td>
                                <td class="fw-semibold text-forest">G-105</td>
                                <td><span class="badge bg-primary text-white"><i class="bi bi-droplet me-1"></i>Plumbing</span></td>
                                <td class="small">Blocked drain in bathroom</td>
                                <td>
                                    <div class="fw-semibold small">K. Srinivas</div>
                                    <small class="text-muted">Plumber</small>
                                </td>
                                <td><span class="badge bg-secondary">Tomorrow</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-success" onclick="alert('Ticket TKT-0476 marked as Resolved.')">
                                        <i class="bi bi-check-lg me-1"></i>Mark Resolved
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Resolved --}}
            <div class="tab-pane fade" id="resolved-tab" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ticket #</th>
                                <th>Room</th>
                                <th>Category</th>
                                <th>Issue</th>
                                <th>Resolved By</th>
                                <th>Date</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-light text-dark border fw-bold font-monospace">TKT-0470</span></td>
                                <td class="fw-semibold text-forest">A-215</td>
                                <td><span class="badge bg-info text-dark"><i class="bi bi-wind me-1"></i>AC / Fan</span></td>
                                <td class="small">Fan speed regulator replaced</td>
                                <td class="small">M. Raju (Electrician)</td>
                                <td class="small">16 Sep 2026</td>
                                <td>
                                    <span class="text-warning">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                                    <span class="small text-muted ms-1">4/5</span>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-light text-dark border fw-bold font-monospace">TKT-0468</span></td>
                                <td class="fw-semibold text-forest">G-207</td>
                                <td><span class="badge bg-light text-dark border"><i class="bi bi-table me-1"></i>Furniture</span></td>
                                <td class="small">Wardrobe door hinge tightened</td>
                                <td class="small">P. Gopal (Carpenter)</td>
                                <td class="small">15 Sep 2026</td>
                                <td>
                                    <span class="text-warning">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                                    <span class="small text-muted ms-1">5/5</span>
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
