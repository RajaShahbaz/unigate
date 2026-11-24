<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 10px;
        }
        .welcome-text {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .reset-code {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-size: 32px;
            font-weight: bold;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin: 30px 0;
            letter-spacing: 3px;
        }
        .instructions {
            background-color: #f8fafc;
            border-left: 4px solid #dc2626;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .warning {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
        }
        .security-notice {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🔒 Uni Gate</div>
            <h1 class="welcome-text">Password Reset Request</h1>
            <p>We received a request to reset your password. Use the code below to reset your password.</p>
        </div>

        <div class="instructions">
            <h3>🔑 Password Reset Code</h3>
            <p>Enter this code to reset your password:</p>
        </div>

        <div class="reset-code">
            {{ $reset_code }}
        </div>

        <div class="instructions">
            <h4>How to reset your password:</h4>
            <ol>
                <li>Return to the password reset page</li>
                <li>Enter this 6-digit reset code</li>
                <li>Create your new password</li>
                <li>Confirm your new password</li>
            </ol>
        </div>

        <div class="warning">
            <strong>⚠️ Security Notice:</strong>
            <ul>
                <li>This reset code will expire in <strong>15 minutes</strong></li>
                <li>Do not share this code with anyone</li>
                <li>If you didn't request this reset, please secure your account immediately</li>
            </ul>
        </div>

        <div class="security-notice">
            <strong>🛡️ Didn't request a password reset?</strong><br>
            If you didn't request this password reset, someone may be trying to access your account. 
            Please contact our support team immediately and consider changing your password.
        </div>

        <div style="text-align: center;">
            <p>Need help? Contact our support team:</p>
            <p>📧 Email: <a href="mailto:support@unigate.edu">support@unigate.edu</a></p>
            <p>📞 Phone: +1 (555) 123-4567</p>
        </div>

        <div class="footer">
            <p>This is an automated message from Uni Gate. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Uni Gate. All rights reserved.</p>
        </div>
    </div>
</body>
</html>





















