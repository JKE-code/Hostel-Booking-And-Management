@extends('layouts.public')

@section('title', 'Rules, Code of Conduct & Regulations — HITAM Hostels')
@section('meta_description', 'Review the residential rules, gate timings, leave approval procedures, anti-ragging mandates, visitor guidelines, and disciplinary policies at HITAM Hostels.')

@section('content')
<!-- Page Banner -->
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb-hitam">
            <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
            <span>/</span>
            <span>Rules & Conduct</span>
        </div>
        <h1 class="page-hero-title">Rules, Regulations & Code of Conduct</h1>
        <p class="page-hero-sub">
            Clear, transparent guidelines established to guarantee safety, personal dignity, academic discipline, and harmonious community life for every resident.
        </p>
    </div>
</section>

<!-- Main Rules Content -->
<section class="py-5">
    <div class="container py-2">
        <div class="row g-5">
            <!-- Left: Rules Accordion / Cards -->
            <div class="col-lg-8">
                <!-- Section 1: Anti-Ragging Mandate -->
                <div class="p-4 mb-4 rounded-3 border border-danger bg-danger-subtle">
                    <div class="d-flex align-items-center gap-2 mb-2 text-danger">
                        <i class="bi bi-shield-x fs-3"></i>
                        <h4 class="fw-bold mb-0">Strict Zero-Tolerance Anti-Ragging Mandate</h4>
                    </div>
                    <p class="small text-danger-emphasis mb-2">
                        Ragging in any form — physical, mental, verbal, or electronic — is strictly forbidden both within the hostel premises and the broader college campus. Any student found guilty of abetting or participating in ragging will face immediate suspension, rustication from HITAM, and criminal lodging with law enforcement authorities as mandated by Supreme Court directives and UGC Regulations.
                    </p>
                    <div class="small fw-semibold text-danger">
                        National Anti-Ragging Helpline: 1800-180-5522 | HITAM Vigilance Desk: +91 92480 09871
                    </div>
                </div>

                <!-- Section 2: Gate Timings & Curfew Rules -->
                <div class="hitam-card p-4 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-door-closed text-forest fs-4"></i>
                        <h4 class="fw-bold text-forest mb-0">1. Gate Timings & Night Curfew</h4>
                    </div>
                    <ul class="text-secondary small mb-3">
                        <li class="mb-2"><strong>Morning Gate Opening:</strong> Hostel gates open at 06:00 AM daily.</li>
                        <li class="mb-2"><strong>Girls Hostel Evening In-Time:</strong> All female residents must report inside campus gates by <strong>06:30 PM</strong> and inside hostel block by <strong>07:00 PM</strong>.</li>
                        <li class="mb-2"><strong>Boys Hostel Evening In-Time:</strong> All male residents must report inside hostel block by <strong>08:30 PM</strong> sharp.</li>
                        <li class="mb-2"><strong>Biometric Roll Call:</strong> Mandatory biometric attendance logging is conducted daily between 08:30 PM and 09:30 PM. Unexcused absence will trigger automated SMS alerts to registered parents.</li>
                        <li><strong>Emergency Late Entry:</strong> Any late entry requires prior written approval from the Chief Warden or verified parental telephone confirmation.</li>
                    </ul>
                </div>

                <!-- Section 3: Room Discipline & Property Care -->
                <div class="hitam-card p-4 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-house-check text-forest fs-4"></i>
                        <h4 class="fw-bold text-forest mb-0">2. Room Maintenance & Electric Appliances</h4>
                    </div>
                    <ul class="text-secondary small mb-3">
                        <li class="mb-2">Residents are responsible for the safety and condition of all furniture, fittings, electrical points, and fixtures allocated to them.</li>
                        <li class="mb-2"><strong>Prohibited Electrical Devices:</strong> High-wattage heating coils, electric stoves, induction cookers, and unauthorized high-power immersion rods are strictly barred due to fire safety hazards.</li>
                        <li class="mb-2">Defacing walls with stickers, nail piercings, or graffiti will incur damage penalties deducted from the caution deposit.</li>
                        <li>Switch off all lights and fans whenever stepping out of the room to support HITAM’s green campus energy conservation initiative.</li>
                    </ul>
                </div>

                <!-- Section 4: Leave Application & Outpass Protocol -->
                <div class="hitam-card p-4 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-journal-arrow-up text-forest fs-4"></i>
                        <h4 class="fw-bold text-forest mb-0">3. Leave Application & Night Stay Outpass</h4>
                    </div>
                    <ul class="text-secondary small mb-3">
                        <li class="mb-2">Students intending to leave the hostel for weekends, vacations, or emergency visits must submit a digital leave request through the student portal at least <strong>24 hours in advance</strong>.</li>
                        <li class="mb-2">Leave approval is contingent upon verification call/consent from the student's registered parent or local guardian.</li>
                        <li class="mb-2">Students must log their exit and return biometric punch at the campus security gate checkpoint.</li>
                        <li>Overstaying approved leave without written intimation will invite disciplinary review and parental summons.</li>
                    </ul>
                </div>

                <!-- Section 5: Visitors & Day Scholars -->
                <div class="hitam-card p-4 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-people text-forest fs-4"></i>
                        <h4 class="fw-bold text-forest mb-0">4. Visitors & Guest Policy</h4>
                    </div>
                    <ul class="text-secondary small mb-0">
                        <li class="mb-2">Parents and verified local guardians are permitted to meet residents only during specified visiting hours (04:30 PM to 06:30 PM on weekdays; 09:00 AM to 06:00 PM on Sundays).</li>
                        <li class="mb-2">Visitors must register their government ID details at the main reception security desk.</li>
                        <li class="mb-2">No visitor or parent is permitted to enter resident student rooms or stay overnight in dormitories without formal written permission from the Chief Warden.</li>
                        <li><strong>Day Scholars are strictly prohibited</strong> from entering hostel dormitory rooms under all circumstances.</li>
                    </ul>
                </div>

                <!-- Section 6: Substance Abuse & Quiet Hours -->
                <div class="hitam-card p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-volume-mute text-forest fs-4"></i>
                        <h4 class="fw-bold text-forest mb-0">5. Substance Prohibition & Quiet Hours</h4>
                    </div>
                    <ul class="text-secondary small mb-0">
                        <li class="mb-2">HITAM campus and hostels are 100% <strong>Alcohol, Tobacco, Nicotine, and Narcotics-free zones</strong>. Possession or consumption of any intoxicating substance attracts immediate expulsion and police intimation.</li>
                        <li class="mb-2"><strong>Quiet Hours:</strong> Observed daily from 10:00 PM to 06:00 AM. Loud music, shouting, running in corridors, or any disturbance to studying peers is strictly forbidden.</li>
                    </ul>
                </div>
            </div>

            <!-- Right: Rules Summary Card & Download -->
            <div class="col-lg-4">
                <div class="hitam-card p-4 mb-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold text-forest mb-3">Official Undertakings</h5>
                    <p class="small text-muted mb-3">
                        Every student and parent must sign the formal hostel code of conduct agreement during room allotment and admission confirmation.
                    </p>
                    <a href="{{ route('public.downloads') }}" class="btn btn-hitam-green w-100 mb-2">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Download Conduct Rulebook
                    </a>
                    <a href="{{ route('public.downloads') }}" class="btn btn-hitam-outline-green w-100 mb-4">
                        <i class="bi bi-download me-1"></i> Anti-Ragging Affidavit Form
                    </a>

                    <div class="p-3 bg-light rounded border">
                        <div class="fw-bold text-forest small mb-1"><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>Disciplinary Committee</div>
                        <p class="small text-muted mb-0">
                            Violations are referred to the Disciplinary Committee consisting of the Chief Warden, Deans, and Student Counselors.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
