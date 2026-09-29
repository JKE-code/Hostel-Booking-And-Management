<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\MessMenu;
use App\Models\Outpass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentPortalController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $student = $user->student ?? ($user->role === 'admin' ? \App\Models\Student::where('onboarding_status', 'active')->first() : null);
        $allocation = $student?->activeAllocation()->with('bed.room.floor.block.hostel')->first();
        $recentOutpasses = $student ? $student->outpasses()->latest()->take(3)->get() : collect();
        $recentComplaints = $student ? $student->complaints()->latest()->take(3)->get() : collect();

        return view('student.dashboard', compact('user', 'student', 'allocation', 'recentOutpasses', 'recentComplaints'));
    }

    public function room()
    {
        $user = Auth::user();
        $student = $user->student;
        $allocation = $student?->activeAllocation()->with(['bed.room.floor.block.hostel', 'bed.room.beds.allocation.student'])->first();

        return view('student.room', compact('user', 'student', 'allocation'));
    }

    public function leaves()
    {
        $user = Auth::user();
        $student = $user->student;
        $outpasses = $student ? $student->outpasses()->latest()->paginate(10) : collect();

        return view('student.leaves', compact('user', 'student', 'outpasses'));
    }

    public function storeLeave(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (! $student) {
            return back()->with('error', 'Student record not found.');
        }

        $request->validate([
            'leave_type'   => ['required', 'in:day_pass,weekend,vacation,emergency,medical,academic'],
            'destination'  => ['required', 'string', 'max:255'],
            'reason'       => ['required', 'string', 'max:500'],
            'out_datetime' => ['required', 'date', 'after_or_equal:now'],
            'in_datetime'  => ['required', 'date', 'after:out_datetime'],
        ]);

        $allocation = $student->activeAllocation()->with('bed.room.floor.block.hostel')->first();

        Outpass::create([
            'student_id'            => $student->id,
            'hostel_id'             => $allocation?->bed?->room?->floor?->block?->hostel_id,
            'leave_type'            => $request->leave_type,
            'destination'           => $request->destination,
            'reason'                => $request->reason,
            'out_datetime'          => $request->out_datetime,
            'in_datetime'           => $request->in_datetime,
            'parent_consent'        => $request->boolean('parent_consent', true),
            'parent_consent_via'    => 'call',
            'status'                => 'pending',
            'qr_verification_code'  => 'OP-' . strtoupper(Str::random(8)) . '-' . $student->roll_number,
        ]);

        return redirect()->route('student.leaves')->with('success', 'Outpass request submitted to Warden for review.');
    }

    public function complaints()
    {
        $user = Auth::user();
        $student = $user->student;
        $complaints = $student ? $student->complaints()->latest()->paginate(10) : collect();

        return view('student.complaints', compact('user', 'student', 'complaints'));
    }

    public function storeComplaint(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (! $student) {
            return back()->with('error', 'Student record not found.');
        }

        $request->validate([
            'category'    => ['required', 'in:electrical,plumbing,carpentry,cleaning,internet,mess,other'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'priority'    => ['required', 'in:low,medium,high,urgent'],
        ]);

        $allocation = $student->activeAllocation()->with('bed.room.floor.block.hostel')->first();

        Complaint::create([
            'student_id'  => $student->id,
            'hostel_id'   => $allocation?->bed?->room?->floor?->block?->hostel_id,
            'room_id'     => $allocation?->bed?->room_id,
            'category'    => $request->category,
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority,
            'status'      => 'submitted',
        ]);

        return redirect()->route('student.complaints')->with('success', 'Maintenance complaint lodged successfully.');
    }

    public function mess()
    {
        $user = Auth::user();
        $student = $user->student;
        $weeklyMenu = MessMenu::where('is_active', true)->orderBy('day_of_week')->get();

        return view('student.mess', compact('user', 'student', 'weeklyMenu'));
    }

    public function profile()
    {
        $user = Auth::user();
        $student = $user->student;
        $allocation = $student?->activeAllocation()->with('bed.room.floor.block.hostel')->first();

        return view('student.profile', compact('user', 'student', 'allocation'));
    }
}
