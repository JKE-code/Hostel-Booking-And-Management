<?php

namespace App\Http\Controllers\Warden;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Hostel;
use App\Models\Outpass;
use App\Models\RoomAllocation;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WardenPortalController extends Controller
{
    /**
     * Warden Operations Dashboard.
     */
    public function dashboard()
    {
        $pendingOutpasses = Outpass::with('student.user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $openComplaints = Complaint::with('student.user')
            ->whereIn('status', ['submitted', 'in_progress'])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'pending_outpasses'   => Outpass::where('status', 'pending')->count(),
            'open_complaints'     => Complaint::whereIn('status', ['submitted', 'in_progress'])->count(),
            'active_residents'    => Student::where('onboarding_status', 'active')->count(),
            'whitelisted_pending' => Student::where('onboarding_status', 'whitelisted')->count(),
            'total_allocations'   => RoomAllocation::where('status', 'active')->count(),
        ];

        return view('warden.dashboard', compact('pendingOutpasses', 'openComplaints', 'stats'));
    }

    /**
     * Student Registry & Pre-Whitelisting Management.
     */
    public function students(Request $request)
    {
        $query = Student::with(['user', 'activeAllocation.bed.room.floor.block.hostel']);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('roll_number', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('onboarding_status', $request->status);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        return view('warden.students', compact('students'));
    }

    /**
     * Whitelist a new student to authorize their 2-way verification signup.
     */
    public function storeStudentWhitelist(Request $request)
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', 'unique:students,email', 'unique:users,email'],
            'roll_number'       => ['required', 'string', 'max:50', 'unique:students,roll_number'],
            'department'        => ['required', 'string', 'in:CSE,ECE,EEE,MECH,CIVIL,IT,MBA,MCA,PHD,OTHER'],
            'year_of_study'     => ['required', 'integer', 'between:1,4'],
            'phone'             => ['required', 'string', 'max:15'],
            'parent_name'       => ['required', 'string', 'max:255'],
            'parent_phone'      => ['required', 'string', 'max:15'],
            'gender'            => ['required', 'string', 'in:male,female,other'],
            'blood_group'       => ['nullable', 'string', 'max:5'],
            'permanent_address' => ['nullable', 'string', 'max:500'],
        ], [
            'email.unique'       => 'A student or user with this email address already exists in the system.',
            'roll_number.unique' => 'A student with this Roll Number is already registered.',
        ]);

        $validated['onboarding_status'] = 'whitelisted';
        $validated['user_id'] = null;

        $student = Student::create($validated);

        return redirect()->route('warden.students')
            ->with('success', "Student {$student->name} ({$student->roll_number}) successfully whitelisted! They may now complete 2-way verification on the signup page.");
    }

    /**
     * Outpass Approvals Management.
     */
    public function outpasses(Request $request)
    {
        $status = $request->get('status', 'pending');
        $query = Outpass::with(['student.user', 'approvedBy']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $outpasses = $query->latest()->paginate(15)->withQueryString();

        return view('warden.outpasses', compact('outpasses', 'status'));
    }

    /**
     * Approve Outpass.
     */
    public function approveOutpass(Outpass $outpass, Request $request)
    {
        $outpass->update([
            'status'          => 'approved',
            'approved_by'     => Auth::id(),
            'approved_at'     => Carbon::now(),
            'warden_remarks'  => $request->input('warden_remarks', 'Approved by Warden on duty.'),
        ]);

        return back()->with('success', "Outpass #{$outpass->id} approved for {$outpass->student->name}.");
    }

    /**
     * Reject Outpass.
     */
    public function rejectOutpass(Outpass $outpass, Request $request)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:255'],
        ]);

        $outpass->update([
            'status'          => 'rejected',
            'approved_by'     => Auth::id(),
            'rejection_reason'=> $request->input('rejection_reason'),
        ]);

        return back()->with('success', "Outpass #{$outpass->id} has been rejected.");
    }

    /**
     * Maintenance Complaints.
     */
    public function complaints(Request $request)
    {
        $query = Complaint::with(['student.user', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->latest()->paginate(15)->withQueryString();

        return view('warden.complaints', compact('complaints'));
    }

    /**
     * Update Complaint Status.
     */
    public function updateComplaint(Complaint $complaint, Request $request)
    {
        $request->validate([
            'status'          => ['required', 'in:submitted,in_progress,resolved,cancelled'],
            'resolution_notes'=> ['nullable', 'string', 'max:500'],
        ]);

        $updates = ['status' => $request->status];

        if ($request->filled('resolution_notes')) {
            $updates['resolution_notes'] = $request->resolution_notes;
        }

        if ($request->status === 'resolved') {
            $updates['resolved_at'] = Carbon::now();
        }

        $complaint->update($updates);

        return back()->with('success', "Complaint #{$complaint->id} status updated to " . strtoupper($request->status) . ".");
    }

    /**
     * Room Allotments & Bed Grid.
     */
    public function allocations()
    {
        $allocations = RoomAllocation::with(['student', 'bed.room.floor.block.hostel'])
            ->where('status', 'active')
            ->latest()
            ->paginate(20);

        return view('warden.allocations', compact('allocations'));
    }
}
