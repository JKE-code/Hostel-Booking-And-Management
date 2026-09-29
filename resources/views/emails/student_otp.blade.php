<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HITAM Hostel Portal Verification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #F1F5F9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1E293B;
            line-height: 1.6;
        }
        .container {
            max-width: 580px;
            margin: 30px auto;
            background: #FFFFFF;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #E2E8F0;
        }
        .header {
            background: linear-gradient(135deg, #064E3B 0%, #0F172A 100%);
            padding: 30px 40px;
            text-align: center;
            color: #FFFFFF;
        }
        .header h1 {
            margin: 10px 0 0 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #F59E0B;
            font-weight: 600;
        }
        .body {
            padding: 36px 40px;
        }
        .greeting {
            font-size: 17px;
            font-weight: 600;
            color: #0F172A;
            margin-bottom: 12px;
        }
        .message {
            font-size: 15px;
            color: #475569;
            margin-bottom: 24px;
        }
        .otp-box {
            background: #F8FAFC;
            border: 2px dashed #064E3B;
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            margin: 28px 0;
        }
        .otp-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748B;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 38px;
            font-weight: 800;
            color: #064E3B;
            letter-spacing: 8px;
            margin: 0;
        }
        .otp-expiry {
            font-size: 13px;
            color: #DC2626;
            font-weight: 500;
            margin-top: 8px;
        }
        .security-notice {
            background: #FFFBEB;
            border-left: 4px solid #F59E0B;
            padding: 14px 18px;
            border-radius: 0 6px 6px 0;
            font-size: 13px;
            color: #92400E;
            margin: 24px 0;
        }
        .footer {
            background: #F8FAFC;
            padding: 24px 40px;
            text-align: center;
            font-size: 12px;
            color: #94A3B8;
            border-top: 1px solid #E2E8F0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <p>Hyderabad Institute of Technology and Management</p>
            <h1>Hostel Residential Services</h1>
        </div>

        <!-- Body Content -->
        <div class="body">
            <div class="greeting">Hello, {{ $studentName }}</div>
            <div class="message">
                Your admission has been pre-whitelisted by the Hostel Warden. To complete your <strong>2-Way Verification Onboarding</strong> and configure your portal password, use the verification code below:
            </div>

            <!-- OTP Card -->
            <div class="otp-box">
                <div class="otp-label">Your 6-Digit Verification Code</div>
                <div class="otp-code">{{ $otpCode }}</div>
                <div class="otp-expiry">Expires in {{ $expiryMinutes }} minutes</div>
            </div>

            <div class="security-notice">
                <strong>Security Reminder:</strong> Never share this verification code with anyone. HITAM Hostel Wardens and staff will never ask for your verification code or password.
            </div>

            <p style="font-size: 13px; color: #64748B; margin: 0;">
                If you did not request this verification code, please immediately inform the Hostel Warden or IT Administration.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} Hyderabad Institute of Technology and Management (HITAM).<br>
            Campus Residential Life & Student Housing Services • Medchal, Hyderabad.
        </div>
    </div>
</body>
</html>
