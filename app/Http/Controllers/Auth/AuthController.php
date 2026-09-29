<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\Otp;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectForRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->login);

        // Determine if input is email or student roll number
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
        } else {
            // Check student roll number
            $student = Student::where('roll_number', strtoupper($loginInput))->first();
            $user = $student?->user;
        }

        if (! $user) {
            return back()->withErrors([
                'login' => 'Institutional email or roll number not recognized in the hostel residency database. Please check your email or contact the hostel warden if you are an admitted resident.',
            ])->onlyInput('login');
        }

        if (! $user->is_active) {
            return back()->withErrors([
                'login' => 'Your account has been deactivated. Please contact the hostel administration desk.',
            ])->onlyInput('login');
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $user->email, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();

            return $this->redirectForRole(Auth::user()->role)
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'login' => 'Incorrect password entered. Please verify your credentials and try again.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Smooth transition to homepage
        return redirect()->route('home')->with('success', 'You have been smoothly signed out. Have a great day!');
    }

    /**
     * Display the initial request form for OTP password reset.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    /**
     * Validate user identity and dispatch 6-digit OTP code via Brevo SMTP.
     */
    public function sendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
        ]);

        $loginInput = trim($request->login);

        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
        } else {
            $student = Student::where('roll_number', strtoupper($loginInput))->first();
            $user = $student?->user;
        }

        if (! $user) {
            return back()->withErrors([
                'login' => 'Institutional email or roll number not recognized in the hostel residency database. Please check your email or contact the hostel warden if you are an admitted resident.',
            ])->onlyInput('login');
        }

        if (! $user->is_active) {
            return back()->withErrors([
                'login' => 'Your account has been deactivated. Please contact the hostel administration desk.',
            ])->onlyInput('login');
        }

        // Generate 6-digit OTP code
        $otpCode = (string) random_int(100000, 999999);

        // Invalidate old pending password reset OTPs for this user
        Otp::where('identifier', $user->email)
            ->where('purpose', 'password_reset')
            ->delete();

        // Store new hashed OTP record with 15 min expiry
        Otp::create([
            'identifier'  => $user->email,
            'otp_code'    => Hash::make($otpCode),
            'purpose'     => 'password_reset',
            'attempts'    => 0,
            'is_locked'   => false,
            'expires_at'  => now()->addMinutes(15),
        ]);

        // Send OTP via Brevo SMTP Mail
        $mailSent = false;
        $errorMessage = '';
        $maxAttempts = 2;
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                Mail::to($user->email)->send(
                    new PasswordResetOtpMail($otpCode, $user->name, 15)
                );
                $mailSent = true;
                break;
            } catch (\Throwable $e) {
                $rawMsg = $e->getMessage();
                Log::error("Brevo Password Reset OTP Mail Error (Attempt {$attempt}): " . $rawMsg);

                if ($attempt < $maxAttempts) {
                    usleep(500000); // 0.5s pause before retry
                    continue;
                }

                if (str_contains($rawMsg, '525') || str_contains($rawMsg, 'Unauthorized IP')) {
                    $errorMessage = "Brevo SMTP blocked the request with '525 Unauthorized IP address'. To resolve: In Brevo, go to Settings > Security > Authorized IPs and either click 'Deactivate' or whitelist your server IP.";
                } elseif (str_contains($rawMsg, '535') || str_contains($rawMsg, 'Authentication failed')) {
                    $errorMessage = "Brevo SMTP returned Authentication Failed (535). Please verify login credentials.";
                } elseif (str_contains($rawMsg, 'php_network_getaddresses') || str_contains($rawMsg, 'No such host')) {
                    $errorMessage = "Transient DNS lookup delay connecting to Brevo relay.";
                } else {
                    $errorMessage = "Brevo SMTP notice: " . substr($rawMsg, 0, 120);
                }

                if (! config('app.debug')) {
                    return back()->withErrors([
                        'login' => 'Unable to dispatch verification email right now. Please verify Brevo SMTP configuration or contact hostel administration.',
                    ])->onlyInput('login');
                }
            }
        }

        // Remember verified email in session for stage 2
        $request->session()->put('password_reset_email', $user->email);

        $maskedEmail = substr($user->email, 0, 3) . '***@' . explode('@', $user->email)[1];

        if ($mailSent) {
            return redirect()->route('password.reset.form')
                ->with('success', "A 6-digit OTP verification code has been dispatched via Brevo to {$maskedEmail}. It is valid for 15 minutes.");
        }

        // In debug/local mode: provide OTP directly so testing is never blocked
        return redirect()->route('password.reset.form')
            ->with('warning', "{$errorMessage} For immediate local testing, your 6-digit OTP code is: {$otpCode}");
    }

    /**
     * Display the form to enter the OTP code and new password.
     */
    public function showResetPasswordForm(Request $request)
    {
        $email = $request->session()->get('password_reset_email');

        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['login' => 'Please enter your institutional email or roll number to begin password reset.']);
        }

        return view('auth.reset_password', ['email' => $email]);
    }

    /**
     * Verify OTP code and update user password.
     */
    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'otp_code'              => ['required', 'string', 'size:6'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = $request->session()->get('password_reset_email');

        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['login' => 'Password reset session expired. Please start again.']);
        }

        $user = User::where('email', $email)->first();
        if (! $user) {
            return redirect()->route('password.request')
                ->withErrors(['login' => 'No registered resident account found for this session.']);
        }

        $otpRecord = Otp::where('identifier', $email)
            ->where('purpose', 'password_reset')
            ->latest()
            ->first();

        if (! $otpRecord) {
            return back()->withErrors(['otp_code' => 'No active OTP verification code found. Please request a new one.']);
        }

        if ($otpRecord->isExpired()) {
            return back()->withErrors(['otp_code' => 'This 6-digit OTP code has expired. Please request a fresh code.']);
        }

        if ($otpRecord->isLocked()) {
            return back()->withErrors(['otp_code' => 'Too many failed verification attempts. Please request a new code.']);
        }

        if (! $otpRecord->verifyCode($request->otp_code)) {
            $otpRecord->recordFailedAttempt();
            $remaining = max(0, 5 - $otpRecord->attempts);
            return back()->withErrors(['otp_code' => "Invalid OTP code entered. {$remaining} attempts remaining before lockout."]);
        }

        // Mark OTP as verified
        $otpRecord->update(['verified_at' => now()]);

        // Update user's password
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session data
        $request->session()->forget('password_reset_email');

        return redirect()->route('login')
            ->with('success', 'Your password has been reset successfully! You can now sign in with your new password.');
    }

    protected function redirectForRole(string $role)
    {
        return match ($role) {
            'admin'    => redirect()->intended(route('admin.dashboard')),
            'warden'   => redirect()->intended(route('warden.dashboard')),
            'security' => redirect()->intended(route('security.dashboard')),
            'student'  => redirect()->intended(route('student.dashboard')),
            default    => redirect('/'),
        };
    }
}
