<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Block;
use App\Models\Complaint;
use App\Models\Floor;
use App\Models\Hostel;
use App\Models\MessMenu;
use App\Models\Outpass;
use App\Models\Room;
use App\Models\RoomAllocation;
use App\Models\Student;
use App\Models\User;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminPortalController extends Controller
{
    /**
     * Master Administration Dashboard — Estate Overview.
     */
    public function dashboard()
    {
        $stats = [
            'total_hostels'        => Hostel::count(),
            'total_beds'           => Bed::count(),
            'occupied_beds'        => Bed::where('status', 'occupied')->count(),
            'available_beds'       => Bed::where('status', 'available')->count(),
            'total_admins'         => User::where('role', 'admin')->count(),
            'total_wardens'        => User::where('role', 'warden')->count(),
            'total_security'       => User::where('role', 'security')->count(),
            'total_students'       => Student::count(),
            'active_students'      => Student::where('onboarding_status', 'active')->count(),
            'whitelisted_students' => Student::where('onboarding_status', 'whitelisted')->count(),
            'pending_outpasses'    => Outpass::where('status', 'pending')->count(),
            'open_complaints'      => Complaint::whereIn('status', ['submitted', 'in_progress'])->count(),
        ];

        $admins = User::where('role', 'admin')->latest()->get();
        $wardens = User::where('role', 'warden')->with('wardenOf')->latest()->take(5)->get();
        $securityStaff = User::where('role', 'security')->latest()->take(5)->get();

        // Hostels with blocks, rooms & beds stats
        $hostels = Hostel::with(['blocks.floors.rooms.beds'])->get()->map(function ($hostel) {
            $totalBeds = 0;
            $occupiedBeds = 0;

            foreach ($hostel->blocks as $block) {
                foreach ($block->floors as $floor) {
                    foreach ($floor->rooms as $room) {
                        $totalBeds += $room->beds->count();
                        $occupiedBeds += $room->beds->where('status', 'occupied')->count();
                    }
                }
            }

            $hostel->computed_total_beds = $totalBeds > 0 ? $totalBeds : $hostel->total_capacity;
            $hostel->computed_occupied_beds = $occupiedBeds;
            $hostel->computed_available_beds = max(0, $hostel->computed_total_beds - $occupiedBeds);
            $hostel->occupancy_rate = $hostel->computed_total_beds > 0 
                ? round(($occupiedBeds / $hostel->computed_total_beds) * 100) 
                : 0;

            return $hostel;
        });

        return view('admin.dashboard', compact('stats', 'admins', 'wardens', 'securityStaff', 'hostels'));
    }

    /**
     * Manage Administrators (Super Admin + Estate Managers).
     */
    public function administrators()
    {
        $administrators = User::where('role', 'admin')->latest()->paginate(15);
        return view('admin.administrators', compact('administrators'));
    }

    /**
     * Create an individual Administrator account (No SPOF).
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)],
        ]);

        $admin = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        return redirect()->route('admin.administrators')->with('success', "Administrator account for {$admin->name} ({$admin->email}) created successfully.");
    }

    /**
     * Manage Wardens.
     */
    public function wardens()
    {
        $wardens = User::where('role', 'warden')->with('wardenOf')->latest()->paginate(15);
        $hostels = Hostel::all();

        return view('admin.wardens', compact('wardens', 'hostels'));
    }

    /**
     * Create a new Warden account.
     */
    public function storeWarden(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', Password::min(8)],
            'hostel_id' => ['nullable', 'exists:hostels,id'],
        ]);

        $warden = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'warden',
            'is_active' => true,
        ]);

        if ($request->filled('hostel_id')) {
            $hostel = Hostel::find($request->hostel_id);
            if ($hostel && ! $hostel->warden_in_charge_id) {
                $hostel->update(['warden_in_charge_id' => $warden->id]);
            }
        }

        return redirect()->route('admin.wardens')->with('success', "Warden account for {$warden->name} created successfully.");
    }

    /**
     * Toggle active status of a Staff account (Guards Primary Admin).
     */
    public function toggleUserStatus(User $user)
    {
        // Guard: Primary Super Administrator cannot be deactivated
        if ($user->email === 'admin@hitam.org') {
            return back()->with('error', 'The Primary Master Super Administrator (admin@hitam.org) cannot be deactivated.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $statusStr = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Account for {$user->name} has been {$statusStr}.");
    }

    /**
     * Manage Security Gate personnel accounts.
     */
    public function securityStaff()
    {
        $securityStaff = User::where('role', 'security')->latest()->paginate(15);
        return view('admin.security', compact('securityStaff'));
    }

    /**
     * Create a new Security Gate account.
     */
    public function storeSecurityStaff(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)],
        ]);

        $staff = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'security',
            'is_active' => true,
        ]);

        return redirect()->route('admin.security')->with('success', "Security Gate desk account for {$staff->name} created successfully.");
    }

    /**
     * Campus Room & Bed Allocations Matrix (Live Grid).
     */
    public function allocations(Request $request)
    {
        $hostels = Hostel::with('blocks.floors')->get();
        
        $selectedHostelId = $request->get('hostel_id', $hostels->first()?->id);
        $selectedHostel = $hostels->firstWhere('id', $selectedHostelId) ?? $hostels->first();

        $blocks = $selectedHostel ? $selectedHostel->blocks : collect();
        $selectedBlockId = $request->get('block_id', $blocks->first()?->id);
        $selectedBlock = $blocks->firstWhere('id', $selectedBlockId) ?? $blocks->first();

        $floors = $selectedBlock ? $selectedBlock->floors : collect();
        $selectedFloorId = $request->get('floor_id', $floors->first()?->id);

        $roomsQuery = Room::with(['beds.activeAllocation.student.user', 'floor.block.hostel']);

        if ($selectedFloorId) {
            $roomsQuery->where('floor_id', $selectedFloorId);
        } elseif ($selectedBlock) {
            $floorIds = $selectedBlock->floors->pluck('id');
            $roomsQuery->whereIn('floor_id', $floorIds);
        }

        $rooms = $roomsQuery->orderBy('room_number')->get();

        $stats = [
            'total_rooms'     => $rooms->count(),
            'total_beds'      => $rooms->sum(fn($r) => $r->beds->count()),
            'occupied_beds'   => $rooms->sum(fn($r) => $r->beds->where('status', 'occupied')->count()),
            'available_beds'  => $rooms->sum(fn($r) => $r->beds->where('status', 'available')->count()),
        ];

        return view('admin.allocations', compact(
            'hostels',
            'selectedHostel',
            'selectedBlock',
            'floors',
            'selectedFloorId',
            'rooms',
            'stats'
        ));
    }

    /**
     * Resident Scholars Directory.
     */
    public function students(Request $request)
    {
        $query = Student::with(['user', 'activeAllocation.bed.room.floor.block.hostel', 'documentVerifier']);

        if ($request->filled('status')) {
            $query->where('onboarding_status', $request->status);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('roll_number', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('department', 'like', "%{$s}%");
            });
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_students'      => Student::count(),
            'active_residents'    => Student::where('onboarding_status', 'active')->count(),
            'whitelisted_pending' => Student::where('onboarding_status', 'whitelisted')->count(),
            'documents_verified'  => Student::whereNotNull('document_verified_at')->count(),
        ];

        // Available beds for immediate allocation during admission
        $availableBeds = Bed::where('status', 'available')->with('room.floor.block.hostel')->get();
        $hostels = Hostel::all();

        return view('admin.students', compact('students', 'stats', 'availableBeds', 'hostels'));
    }

    /**
     * Admit & Register a Resident Scholar with Document Verification.
     */
    public function storeStudent(Request $request, \App\Services\DocumentStorageService $documentService)
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', 'unique:students,email', 'unique:users,email'],
            'roll_number'       => ['required', 'string', 'max:20', 'unique:students,roll_number'],
            'department'        => ['required', 'in:CSE,ECE,EEE,MECH,CIVIL,IT,MBA,MCA,PHD,OTHER'],
            'year_of_study'     => ['required', 'integer', 'between:1,4'],
            'phone'             => ['required', 'string', 'max:15'],
            'gender'            => ['required', 'in:male,female,other'],
            'parent_name'       => ['required', 'string', 'max:255'],
            'parent_phone'      => ['required', 'string', 'max:15'],
            'emergency_contact' => ['nullable', 'string', 'max:15'],
            'blood_group'       => ['nullable', 'string', 'max:10'],
            'date_of_birth'     => ['nullable', 'date'],
            'permanent_address' => ['nullable', 'string', 'max:500'],
            'onboarding_status' => ['required', 'in:active,whitelisted'],
            'initial_password'  => ['nullable', 'string', 'min:8'],
            'bed_id'            => ['nullable', 'exists:beds,id'],
            'id_proof'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'admission_letter'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'medical_cert'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'photo'             => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'verification_notes'=> ['nullable', 'string', 'max:1000'],
        ]);

        $userId = null;
        if ($request->onboarding_status === 'active') {
            $password = $request->filled('initial_password') 
                ? $request->initial_password 
                : 'hitam@' . $request->roll_number;

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($password),
                'role'      => 'student',
                'is_active' => true,
            ]);
            $userId = $user->id;
        }

        $student = Student::create([
            'user_id'            => $userId,
            'name'               => $request->name,
            'email'              => $request->email,
            'roll_number'        => strtoupper(trim($request->roll_number)),
            'department'         => $request->department,
            'year_of_study'      => $request->year_of_study,
            'phone'              => $request->phone,
            'gender'             => $request->gender,
            'parent_name'        => $request->parent_name,
            'parent_phone'       => $request->parent_phone,
            'emergency_contact'  => $request->emergency_contact,
            'blood_group'        => $request->blood_group,
            'date_of_birth'      => $request->date_of_birth,
            'permanent_address'  => $request->permanent_address,
            'onboarding_status'  => $request->onboarding_status,
            'verification_notes' => $request->verification_notes,
            'document_verified_at' => ($request->hasFile('id_proof') || $request->hasFile('admission_letter')) ? now() : null,
            'document_verified_by' => ($request->hasFile('id_proof') || $request->hasFile('admission_letter')) ? auth()->id() : null,
        ]);

        // Upload documents (Cloudinary-ready via DocumentStorageService)
        $updates = [];
        if ($request->hasFile('id_proof')) {
            $updates['id_proof_url'] = $documentService->storeDocument($request->file('id_proof'), 'id_proof', $student->roll_number);
        }
        if ($request->hasFile('admission_letter')) {
            $updates['admission_letter_url'] = $documentService->storeDocument($request->file('admission_letter'), 'admission_letter', $student->roll_number);
        }
        if ($request->hasFile('medical_cert')) {
            $updates['medical_cert_url'] = $documentService->storeDocument($request->file('medical_cert'), 'medical_cert', $student->roll_number);
        }
        if ($request->hasFile('photo')) {
            $updates['photo_url'] = $documentService->storeDocument($request->file('photo'), 'photo', $student->roll_number);
        }

        if (! empty($updates)) {
            $student->update($updates);
        }

        // Room allotment if bed selected
        if ($request->filled('bed_id')) {
            $bed = Bed::find($request->bed_id);
            if ($bed && $bed->status === 'available') {
                RoomAllocation::create([
                    'student_id'     => $student->id,
                    'bed_id'         => $bed->id,
                    'academic_year'  => '2025-2026',
                    'allocated_from' => now()->toDateString(),
                    'allocated_to'   => now()->addYear()->toDateString(),
                    'status'         => 'active',
                    'remarks'        => 'Allotted upon admission verification by ' . auth()->user()->name,
                    'allocated_by'   => auth()->id(),
                ]);
                $bed->update(['status' => 'occupied']);
            }
        }

        $msg = $request->onboarding_status === 'active'
            ? "Resident Scholar {$student->name} ({$student->roll_number}) admitted & verified successfully. Account is active."
            : "Scholar {$student->name} ({$student->roll_number}) added to Whitelist. Ready for 2-way signup verification.";

        return redirect()->route('admin.students')->with('success', $msg);
    }

    /**
     * Update existing Resident Scholar Profile details.
     */
    public function updateStudent(Request $request, Student $student)
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', 'unique:students,email,' . $student->id, 'unique:users,email,' . ($student->user_id ?? 0)],
            'roll_number'       => ['required', 'string', 'max:20', 'unique:students,roll_number,' . $student->id],
            'department'        => ['required', 'in:CSE,ECE,EEE,MECH,CIVIL,IT,MBA,MCA,PHD,OTHER'],
            'year_of_study'     => ['required', 'integer', 'between:1,4'],
            'phone'             => ['required', 'string', 'max:15'],
            'gender'            => ['required', 'in:male,female,other'],
            'parent_name'       => ['required', 'string', 'max:255'],
            'parent_phone'      => ['required', 'string', 'max:15'],
            'emergency_contact' => ['nullable', 'string', 'max:15'],
            'blood_group'       => ['nullable', 'string', 'max:10'],
            'permanent_address' => ['nullable', 'string', 'max:500'],
            'onboarding_status' => ['required', 'in:active,whitelisted'],
        ]);

        $oldEmail = $student->email;
        $newEmail = trim($request->email);

        // Update Student Record
        $student->update([
            'name'              => $request->name,
            'email'             => $newEmail,
            'roll_number'       => strtoupper(trim($request->roll_number)),
            'department'        => $request->department,
            'year_of_study'     => $request->year_of_study,
            'phone'             => $request->phone,
            'gender'            => $request->gender,
            'parent_name'       => $request->parent_name,
            'parent_phone'      => $request->parent_phone,
            'emergency_contact' => $request->emergency_contact,
            'blood_group'       => $request->blood_group,
            'permanent_address' => $request->permanent_address,
            'onboarding_status' => $request->onboarding_status,
        ]);

        // Keep linked user account in sync
        if ($student->user) {
            $student->user->update([
                'name'  => $student->name,
                'email' => $newEmail,
            ]);
        } elseif ($request->onboarding_status === 'active') {
            // If student had no user account yet and is now active, create one
            $user = User::create([
                'name'      => $student->name,
                'email'     => $newEmail,
                'password'  => Hash::make('hitam@' . $student->roll_number),
                'role'      => 'student',
                'is_active' => true,
            ]);
            $student->update(['user_id' => $user->id]);
        }

        // Sync any active OTP identifiers if email changed
        if ($oldEmail !== $newEmail) {
            \App\Models\Otp::where('identifier', $oldEmail)->update(['identifier' => $newEmail]);
        }

        return redirect()->route('admin.students')->with('success', "Scholar details for {$student->name} ({$student->roll_number}) updated successfully.");
    }

    /**
     * Upload or update verification documents for an existing student.
     */
    public function uploadStudentDocuments(Request $request, Student $student, \App\Services\DocumentStorageService $documentService)
    {
        $request->validate([
            'id_proof'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'admission_letter'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'medical_cert'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'photo'             => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'verification_notes'=> ['nullable', 'string', 'max:1000'],
            'mark_verified'     => ['nullable', 'boolean'],
        ]);

        $updates = [];
        if ($request->hasFile('id_proof')) {
            $documentService->deleteDocument($student->id_proof_url);
            $updates['id_proof_url'] = $documentService->storeDocument($request->file('id_proof'), 'id_proof', $student->roll_number);
        }
        if ($request->hasFile('admission_letter')) {
            $documentService->deleteDocument($student->admission_letter_url);
            $updates['admission_letter_url'] = $documentService->storeDocument($request->file('admission_letter'), 'admission_letter', $student->roll_number);
        }
        if ($request->hasFile('medical_cert')) {
            $documentService->deleteDocument($student->medical_cert_url);
            $updates['medical_cert_url'] = $documentService->storeDocument($request->file('medical_cert'), 'medical_cert', $student->roll_number);
        }
        if ($request->hasFile('photo')) {
            $documentService->deleteDocument($student->photo_url);
            $updates['photo_url'] = $documentService->storeDocument($request->file('photo'), 'photo', $student->roll_number);
        }

        if ($request->filled('verification_notes')) {
            $updates['verification_notes'] = $request->verification_notes;
        }

        if ($request->boolean('mark_verified', true)) {
            $updates['document_verified_at'] = now();
            $updates['document_verified_by'] = auth()->id();
        }

        $student->update($updates);

        return back()->with('success', "Verification documents updated for {$student->name} ({$student->roll_number}).");
    }

    /**
     * Campus-wide Outpass & Movement Oversight.
     */
    public function leaves(Request $request)
    {
        $query = Outpass::with(['student.user', 'student.activeAllocation.bed.room.floor.block.hostel', 'hostel']);

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'outside') {
                $query->where('status', 'approved')
                      ->whereNotNull('out_datetime')
                      ->whereNull('actual_return_datetime');
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('qr_verification_code', 'like', "%{$s}%")
                  ->orWhere('destination', 'like', "%{$s}%")
                  ->orWhereHas('student', function ($sq) use ($s) {
                      $sq->where('name', 'like', "%{$s}%")
                         ->orWhere('roll_number', 'like', "%{$s}%");
                  });
            });
        }

        $outpasses = $query->latest('created_at')->paginate(15)->withQueryString();

        $stats = [
            'pending'   => Outpass::where('status', 'pending')->count(),
            'approved'  => Outpass::where('status', 'approved')->count(),
            'outside'   => Outpass::where('status', 'approved')->whereNotNull('out_datetime')->whereNull('actual_return_datetime')->count(),
            'completed' => Outpass::where('status', 'completed')->count(),
        ];

        return view('admin.leaves', compact('outpasses', 'stats'));
    }

    /**
     * Campus Maintenance & Grievances Desk.
     */
    public function complaints(Request $request)
    {
        $query = Complaint::with(['student.user', 'room.floor.block.hostel']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
                  ->orWhereHas('student', function ($sq) use ($s) {
                      $sq->where('name', 'like', "%{$s}%")
                         ->orWhere('roll_number', 'like', "%{$s}%");
                  });
            });
        }

        $complaints = $query->latest('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total'       => Complaint::count(),
            'submitted'   => Complaint::where('status', 'submitted')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved'    => Complaint::where('status', 'resolved')->count(),
        ];

        return view('admin.complaints', compact('complaints', 'stats'));
    }

    /**
     * Inspect Lower Tier: Warden Console Inspection View.
     */
    public function inspectWarden()
    {
        $pendingOutpasses = Outpass::with(['student.user', 'student.activeAllocation.bed.room.floor.block.hostel'])
            ->where('status', 'pending')
            ->latest()
            ->take(6)
            ->get();

        $openComplaints = Complaint::with(['student.user', 'room'])
            ->whereIn('status', ['submitted', 'in_progress'])
            ->latest()
            ->take(6)
            ->get();

        $whitelistedPending = Student::where('onboarding_status', 'whitelisted')
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'pending_outpasses'   => Outpass::where('status', 'pending')->count(),
            'open_complaints'     => Complaint::whereIn('status', ['submitted', 'in_progress'])->count(),
            'active_residents'    => Student::where('onboarding_status', 'active')->count(),
            'whitelisted_pending' => Student::where('onboarding_status', 'whitelisted')->count(),
            'total_allocations'   => RoomAllocation::where('status', 'active')->count(),
        ];

        return view('admin.inspect.warden', compact('pendingOutpasses', 'openComplaints', 'whitelistedPending', 'stats'));
    }

    /**
     * Inspect Lower Tier: Security Gate Monitor Inspection View.
     */
    public function inspectSecurity(Request $request)
    {
        $today = Carbon::today();

        $stats = [
            'currently_outside'     => Outpass::where('status', 'approved')->whereNotNull('out_datetime')->whereNull('actual_return_datetime')->count(),
            'today_approved_passes' => Outpass::where('status', 'approved')->whereDate('out_datetime', $today)->count(),
            'active_visitors'       => Visitor::where('status', 'checked_in')->count(),
            'total_visitors_today'  => Visitor::whereDate('created_at', $today)->count(),
        ];

        $currentlyOutside = Outpass::with(['student.user', 'student.activeAllocation.bed.room.floor.block'])
            ->where('status', 'approved')
            ->whereNotNull('out_datetime')
            ->whereNull('actual_return_datetime')
            ->latest('out_datetime')
            ->take(10)
            ->get();

        $recentVisitors = Visitor::latest()->take(8)->get();

        $scannedPass = null;
        if ($request->filled('search')) {
            $term = trim($request->search);
            $scannedPass = Outpass::with(['student.user', 'student.activeAllocation.bed.room.floor.block.hostel'])
                ->where('qr_verification_code', $term)
                ->orWhere('id', $term)
                ->orWhereHas('student', function ($sq) use ($term) {
                    $sq->where('roll_number', $term)->orWhere('phone', $term);
                })
                ->first();
        }

        return view('admin.inspect.security', compact('stats', 'currentlyOutside', 'recentVisitors', 'scannedPass'));
    }

    /**
     * Inspect Lower Tier: Resident Student Portal Inspection View.
     */
    public function inspectStudent(Request $request)
    {
        $students = Student::where('onboarding_status', 'active')->get();
        
        $selectedStudentId = $request->get('student_id', $students->first()?->id);
        $student = $students->firstWhere('id', $selectedStudentId) ?? $students->first();

        $allocation = $student?->activeAllocation()->with('bed.room.floor.block.hostel')->first();
        $recentOutpasses = $student ? $student->outpasses()->latest()->take(5)->get() : collect();
        $recentComplaints = $student ? $student->complaints()->latest()->take(5)->get() : collect();
        $weeklyMenu = MessMenu::where('is_active', true)->orderBy('day_of_week')->get();

        return view('admin.inspect.student', compact('students', 'student', 'allocation', 'recentOutpasses', 'recentComplaints', 'weeklyMenu'));
    }
}
