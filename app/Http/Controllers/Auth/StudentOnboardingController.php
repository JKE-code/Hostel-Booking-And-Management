<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\StudentSignupOtpMail;
use App\Models\Otp;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class StudentOnboardingController extends Controller
{
    /**
     * Show the 3-step signup / verification wizard.
     */
    public function showSignupForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        $step = session('signup_step', 1);
        $identifier = session('signup_identifier');
        $student = $identifier ? Student::where('email', $identifier)->first() : null;

        return view('auth.signup', compact('step', 'identifier', 'student'));
    }

    /**
     * Step 1: Verify student institutional email against whitelisted registry.
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'email'],
        ], [
            'identifier.required' => 'Please enter your institutional student email address.',
            'identifier.email'    => 'Please enter a valid email address.',
        ]);

        $email = trim(strtolower($request->input('identifier')));

        $student = Student::where('email', $email)->first();

        if (! $student) {
            return back()->withInput()->withErrors([
                'identifier' => 'No admission record found matching this email address. Please contact your Hostel Warden to be whitelisted into the hostel registry first.',
            ]);
        }

        if ($student->onboarding_status === 'active' && $student->user_id) {
            return back()->withInput()->with('info', 'Your account is already active and verified! Please sign in with your password.');
        }

        if ($student->onboarding_status === 'archived') {
            return back()->withInput()->withErrors([
                'identifier' => 'This student record is currently archived. Please contact hostel administration.',
            ]);
        }

        // Generate cryptographically secure 6-digit numeric OTP
        $otpCode = (string) random_int(100000, 999999);

        // Invalidate previous unverified OTPs for this student
        Otp::where('identifier', $student->email)
            ->whereNull('verified_at')
            ->delete();

        // Store hashed OTP in database to prevent leakage from raw DB snapshots
        Otp::create([
            'identifier'  => $student->email,
            'otp_code'    => Hash::make($otpCode),
            'purpose'     => 'student_signup',
            'attempts'    => 0,
            'is_locked'   => false,
            'expires_at'  => Carbon::now()->addMinutes(15),
        ]);

        // Dispatch real email via Brevo SMTP
        $mailSent = false;
        try {
            Mail::to($student->email)->send(new StudentSignupOtpMail($otpCode, $student->name, 15));
            $mailSent = true;
        } catch (\Throwable $e) {
            Log::warning('Brevo SMTP OTP delivery notice: ' . $e->getMessage());
        }

        session([
            'signup_step'       => 2,
            'signup_identifier' => $student->email,
            'demo_otp'          => $otpCode, // Available in session for local testing
        ]);

        $statusMsg = $mailSent
            ? "A 6-digit verification code has been dispatched to your email ({$student->email}) via Brevo SMTP!"
            : "A 6-digit verification code has been generated for your email ({$student->email})!";

        return redirect()->route('signup')->with('success', $statusMsg);
    }

    /**
     * Step 2: Verify the 6-digit OTP with brute-force lockout.
     */
    public function verifyOtp(Request $request)
    {
        $identifier = session('signup_identifier');

        if (! $identifier) {
            return redirect()->route('signup')->withErrors(['identifier' => 'Session expired. Please re-enter your email.']);
        }

        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'otp.required' => 'Please enter the 6-digit verification OTP.',
            'otp.size'     => 'The OTP must be exactly 6 digits.',
        ]);

        $enteredOtp = trim($request->input('otp'));

        $otpRecord = Otp::where('identifier', $identifier)
            ->where('purpose', 'student_signup')
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $otpRecord || $otpRecord->isExpired()) {
            return back()->withErrors([
                'otp' => 'Verification code expired or not found. Please request a fresh OTP.',
            ]);
        }

        // Check if token is locked due to >= 5 failed attempts
        if ($otpRecord->isLocked()) {
            return back()->withErrors([
                'otp' => 'This verification code is locked due to 5 consecutive incorrect attempts. For security, please request a new OTP.',
            ]);
        }

        // Verify hashed code
        if (! $otpRecord->verifyCode($enteredOtp)) {
            $otpRecord->recordFailedAttempt();
            $remaining = max(0, 5 - $otpRecord->attempts);

            if ($remaining === 0) {
                return back()->withErrors([
                    'otp' => 'Too many failed attempts. This code is now locked. Please request a fresh code.',
                ]);
            }

            return back()->withErrors([
                'otp' => "Incorrect verification code. {$remaining} attempt(s) remaining before this code is locked.",
            ]);
        }

        $otpRecord->update(['verified_at' => Carbon::now()]);

        session([
            'signup_step'         => 3,
            'signup_otp_verified' => true,
        ]);

        return redirect()->route('signup')->with('success', 'Email identity verified! Please configure your portal password.');
    }

    /**
     * Step 3: Set password and activate student account.
     */
    public function completeRegistration(Request $request)
    {
        $identifier = session('signup_identifier');
        $isVerified = session('signup_otp_verified', false);

        if (! $identifier || ! $isVerified) {
            return redirect()->route('signup')->withErrors(['identifier' => 'Verification required. Please start from Step 1.']);
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $student = Student::where('email', $identifier)->firstOrFail();

        // Check if user already exists
        $user = User::where('email', $student->email)->first();

        if ($user) {
            $user->update([
                'password'          => Hash::make($request->password),
                'role'              => 'student',
                'is_active'         => true,
                'email_verified_at' => Carbon::now(),
            ]);
        } else {
            $user = User::create([
                'name'              => $student->name,
                'email'             => $student->email,
                'password'          => Hash::make($request->password),
                'role'              => 'student',
                'is_active'         => true,
                'email_verified_at' => Carbon::now(),
            ]);
        }

        // Link student to user
        $student->update([
            'user_id'           => $user->id,
            'onboarding_status' => 'active',
        ]);

        // Clear signup session
        session()->forget(['signup_step', 'signup_identifier', 'signup_otp_verified', 'demo_otp']);

        Auth::login($user);

        return redirect()->route('student.dashboard')->with('success', 'Account successfully activated! Welcome to the HITAM Hostel Portal, ' . $student->name . '.');
    }

    /**
     * Reset the signup wizard back to step 1.
     */
    public function resetWizard()
    {
        session()->forget(['signup_step', 'signup_identifier', 'signup_otp_verified', 'demo_otp']);
        return redirect()->route('signup');
    }
}
