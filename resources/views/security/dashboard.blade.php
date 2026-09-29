@extends('layouts.security')

@section('title', 'Main Gate Monitor & Outpass Scanner — HITAM Hostels')
@section('page_title', 'Gate Operations & Outpass Verifier')

@section('security_content')
<!-- Gate Status Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4">
        <div class="card border-0 rounded-4 p-3 h-100" style="background: #1E293B; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-white-50 small fw-semibold">Currently Outside Campus</span>
                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(239, 68, 68, 0.2); color: #EF4444;">
                    <i class="bi bi-door-open-fill"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-white">{{ $stats['currently_outside'] }}</div>
            <small class="text-warning"><i class="bi bi-broadcast me-1"></i>Active outpasses outside</small>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="card border-0 rounded-4 p-3 h-100" style="background: #1E293B; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-white-50 small fw-semibold">Today's Approved Passes</span>
                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(16, 185, 129, 0.2); color: #10B981;">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-white">{{ $stats['today_approved_passes'] }}</div>
            <small class="text-success"><i class="bi bi-shield-check me-1"></i>Warden verified leaves</small>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card border-0 rounded-4 p-3 h-100" style="background: #1E293B; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-white-50 small fw-semibold">Active Visitors On Campus</span>
                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(59, 130, 246, 0.2); color: #3B82F6;">
                    <i class="bi bi-person-lines-fill"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold text-white">{{ $stats['active_visitors'] }}</div>
            <small class="text-info"><a href="{{ route('security.visitors') }}" class="text-info text-decoration-none">Manage visitors desk &rarr;</a></small>
        </div>
    </div>
</div>

<!-- Fast Outpass QR / Roll Number Verification Tool -->
<div class="card border-0 rounded-4 p-4 mb-4" style="background: #1E293B; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h5 class="fw-bold mb-0 text-white"><i class="bi bi-upc-scan text-success me-2"></i>Rapid Outpass QR & Roll Number Verifier</h5>
            <small class="text-white-50">Enter Student Roll Number, Outpass ID, or QR Verification Token to validate gate pass.</small>
        </div>
        <span class="badge bg-success text-dark px-3 py-1 fw-bold">Live Verifier</span>
    </div>

    <form action="{{ route('security.dashboard') }}" method="GET" class="mb-3">
        <div class="input-group input-group-lg">
            <span class="input-group-text border-0 text-white-50" style="background: #0F172A;"><i class="bi bi-qr-code"></i></span>
            <input type="text" name="search" class="form-control border-0 text-white" style="background: #0F172A;" placeholder="Scan QR Code or Type Roll Number (e.g. 23HT1A0501)..." value="{{ request('search') }}" autofocus>
            <button type="submit" class="btn btn-success fw-bold px-4">
                <i class="bi bi-search me-1"></i> Verify Pass
            </button>
        </div>
    </form>

    <!-- Scanned Result Card -->
    @if(request()->filled('search'))
        @if($scannedPass)
            <div class="p-4 rounded-4 mt-3" style="background: #0F172A; border: 2px solid #10B981;">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-emerald text-white fw-bold d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: #10B981; font-size: 1.4rem;">
                            {{ strtoupper(substr($scannedPass->student->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h4 class="fw-bold text-white mb-0">{{ $scannedPass->student->name }}</h4>
                                <span class="badge bg-light text-dark font-monospace">{{ $scannedPass->student->roll_number }}</span>
                                @if($scannedPass->status === 'approved')
                                    <span class="badge bg-success text-white">VALID PASS</span>
                                @elseif($scannedPass->status === 'returned')
                                    <span class="badge bg-info text-dark">ALREADY RETURNED</span>
                                @else
                                    <span class="badge bg-danger text-white">{{ strtoupper($scannedPass->status) }}</span>
                                @endif
                            </div>
                            <div class="text-white-50 small">
                                Dept: {{ $scannedPass->student->department }} (Year {{ $scannedPass->student->year_of_study }}) • Phone: {{ $scannedPass->student->phone }} • Parent: {{ $scannedPass->student->parent_phone }}
                            </div>
                        </div>
                    </div>

                    <div class="text-md-end">
                        <div class="small text-white-50">Outpass Identifier</div>
                        <div class="fw-bold text-white font-monospace">{{ $scannedPass->qr_verification_code ?? ('PASS #' . $scannedPass->id) }}</div>
                    </div>
                </div>

                <hr class="border-secondary my-3">

                <div class="row g-3 text-start mb-3">
                    <div class="col-6 col-md-3">
                        <div class="small text-white-50">Leave Classification</div>
                        <div class="fw-semibold text-white text-capitalize">{{ str_replace('_', ' ', $scannedPass->leave_type) }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="small text-white-50">Destination</div>
                        <div class="fw-semibold text-white">{{ $scannedPass->destination }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="small text-white-50">Scheduled Out</div>
                        <div class="fw-semibold text-white">{{ \Carbon\Carbon::parse($scannedPass->out_datetime)->format('d M, h:i A') }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="small text-white-50">Expected In Time</div>
                        <div class="fw-semibold text-warning">{{ \Carbon\Carbon::parse($scannedPass->in_datetime)->format('d M, h:i A') }}</div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 justify-content-end align-items-center pt-2">
                    @if($scannedPass->status === 'approved')
                        <form action="{{ route('security.outpasses.entry', $scannedPass->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success fw-bold px-4 py-2">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Record Student Return Entry
                            </button>
                        </form>
                    @else
                        <span class="text-white-50 small">Pass status is: {{ strtoupper($scannedPass->status) }}</span>
                    @endif
                    <a href="{{ route('security.dashboard') }}" class="btn btn-outline-secondary px-3 py-2 text-white">Clear Scan</a>
                </div>
            </div>
        @else
            <div class="alert alert-danger rounded-3 mt-3 border-0" style="background: rgba(239, 68, 68, 0.2); color: #FCA5A5;">
                <i class="bi bi-x-circle-fill me-2"></i>No approved or active outpass found matching: <strong>{{ request('search') }}</strong>. Verify student roll number or pass ID.
            </div>
        @endif
    @endif
</div>

<!-- Currently Outside Students Live Monitor Table -->
<div class="card border-0 rounded-4 p-4" style="background: #1E293B; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-white"><i class="bi bi-clock-history text-warning me-2"></i>Currently Checked Out Residents</h6>
            <small class="text-white-50">Students currently outside the hostel campus boundaries</small>
        </div>
        <span class="badge bg-danger-subtle text-danger px-3 py-1 fw-bold">{{ $currentlyOutside->total() }} Outside</span>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0 small">
            <thead>
                <tr class="text-white-50" style="border-color: rgba(255, 255, 255, 0.1);">
                    <th>Student Name</th>
                    <th>Roll Number</th>
                    <th>Destination</th>
                    <th>Out Time</th>
                    <th>Return Due By</th>
                    <th class="text-end">Gate Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($currentlyOutside as $out)
                <tr style="border-color: rgba(255, 255, 255, 0.06);">
                    <td>
                        <div class="fw-bold text-white">{{ $out->student->name }}</div>
                        <div class="text-white-50" style="font-size: 0.72rem;">Tel: {{ $out->student->phone }}</div>
                    </td>
                    <td><span class="badge bg-dark border text-light font-monospace">{{ $out->student->roll_number }}</span></td>
                    <td class="text-white-50">{{ $out->destination }}</td>
                    <td class="text-white">{{ \Carbon\Carbon::parse($out->out_datetime)->format('d M, h:i A') }}</td>
                    <td>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                            {{ \Carbon\Carbon::parse($out->in_datetime)->format('d M, h:i A') }}
                        </span>
                    </td>
                    <td class="text-end">
                        <form action="{{ route('security.outpasses.entry', $out->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success px-3 py-1">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Log Return
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-white-50 py-4">
                        <i class="bi bi-shield-check text-success fs-3 d-block mb-1"></i>
                        No resident students currently checked out.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $currentlyOutside->links() }}
    </div>
</div>
@endsection
