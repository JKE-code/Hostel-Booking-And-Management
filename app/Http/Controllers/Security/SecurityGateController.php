<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\Outpass;
use App\Models\Student;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SecurityGateController extends Controller
{
    /**
     * Main Gate Monitor & Outpass Verification Dashboard.
     */
    public function dashboard(Request $request)
    {
        $today = Carbon::today();

        // 1. Stats
        $outsideCount = Outpass::where('status', 'approved')
            ->whereNotNull('out_datetime')
            ->whereNull('actual_return_datetime')
            ->count();

        $todayApprovedPasses = Outpass::where('status', 'approved')
            ->whereDate('out_datetime', $today)
            ->count();

        $activeVisitors = Visitor::where('status', 'checked_in')->count();

        $stats = [
            'currently_outside'     => $outsideCount,
            'today_approved_passes' => $todayApprovedPasses,
            'active_visitors'       => $activeVisitors,
        ];

        // 2. Currently outside students
        $currentlyOutside = Outpass::with(['student.user', 'student.activeAllocation.bed.room'])
            ->where('status', 'approved')
            ->whereNull('actual_return_datetime')
            ->latest('out_datetime')
            ->paginate(10);

        // 3. Optional scanned / searched outpass
        $scannedPass = null;
        if ($request->filled('search')) {
            $term = trim($request->search);
            $scannedPass = Outpass::with(['student.user', 'student.activeAllocation.bed.room.floor.block'])
                ->where(function ($q) use ($term) {
                    $q->where('qr_verification_code', $term)
                      ->orWhere('id', $term)
                      ->orWhereHas('student', function ($sq) use ($term) {
                          $sq->where('roll_number', $term)
                            ->orWhere('phone', $term);
                      });
                })
                ->whereIn('status', ['approved', 'overdue', 'returned'])
                ->latest()
                ->first();
        }

        return view('security.dashboard', compact('stats', 'currentlyOutside', 'scannedPass'));
    }

    /**
     * Gate Action: Log student return entry.
     */
    public function recordEntry(Outpass $outpass)
    {
        $outpass->update([
            'status'                 => 'returned',
            'actual_return_datetime' => Carbon::now(),
        ]);

        return back()->with('success', "Return entry recorded for {$outpass->student->name} ({$outpass->student->roll_number}) at " . Carbon::now()->format('h:i A') . ".");
    }

    /**
     * Gate Action: Check in a visitor.
     */
    public function checkInVisitor(Visitor $visitor)
    {
        $visitor->update([
            'status'        => 'checked_in',
            'checked_in_at' => Carbon::now(),
            'approved_by'   => Auth::id(),
        ]);

        return back()->with('success', "Visitor {$visitor->visitor_name} checked in successfully.");
    }

    /**
     * Gate Action: Check out a visitor.
     */
    public function checkOutVisitor(Visitor $visitor)
    {
        $visitor->update([
            'status'         => 'checked_out',
            'checked_out_at' => Carbon::now(),
        ]);

        return back()->with('success', "Visitor {$visitor->visitor_name} checked out.");
    }

    /**
     * Visitors Log & Rapid Check-In.
     */
    public function visitors(Request $request)
    {
        $today = Carbon::today();
        $visitors = Visitor::with(['student.user'])
            ->whereDate('visit_date', $today)
            ->latest()
            ->paginate(15);

        return view('security.visitors', compact('visitors'));
    }

    /**
     * Rapid Gate Visitor Log Entry.
     */
    public function storeVisitor(Request $request)
    {
        $request->validate([
            'roll_number'   => ['required', 'string', 'exists:students,roll_number'],
            'visitor_name'  => ['required', 'string', 'max:255'],
            'relation'      => ['required', 'string', 'max:50'],
            'visitor_phone' => ['required', 'string', 'max:15'],
            'purpose'       => ['required', 'string', 'max:255'],
        ]);

        $student = Student::where('roll_number', $request->roll_number)->firstOrFail();

        Visitor::create([
            'student_id'    => $student->id,
            'visitor_name'  => $request->visitor_name,
            'relation'      => $request->relation,
            'visitor_phone' => $request->visitor_phone,
            'visit_date'    => Carbon::today(),
            'purpose'       => $request->purpose,
            'status'        => 'checked_in',
            'checked_in_at' => Carbon::now(),
            'approved_by'   => Auth::id(),
        ]);

        return back()->with('success', "Visitor {$request->visitor_name} checked in for resident {$student->name} ({$student->roll_number}).");
    }
}
