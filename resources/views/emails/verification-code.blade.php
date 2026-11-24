<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verification</title>
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
            color: #4f46e5;
            margin-bottom: 10px;
        }
        .welcome-text {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .verification-code {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-left: 4px solid #4f46e5;
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
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🎓 Uni Gate</div>
            <h1 class="welcome-text">Welcome to Uni Gate!</h1>
            <p>Thank you for registering with us. Please verify your email address to complete your registration.</p>
        </div>

        <div class="instructions">
            <h3>📧 Email Verification Required</h3>
            <p>To activate your account, please enter the verification code below:</p>
        </div>

        <div class="verification-code">
            {{ $verification_code }}
        </div>

        <div class="instructions">
            <h4>How to use this code:</h4>
            <ol>
                <li>Return to the registration page</li>
                <li>Enter this 6-digit verification code</li>
                <li>Click "Verify Email" to complete your registration</li>
            </ol>
        </div>

        <div class="warning">
            <strong>⚠️ Important:</strong>
            <ul>
                <li>This verification code will expire in <strong>15 minutes</strong></li>
                <li>Do not share this code with anyone</li>
                <li>If you didn't request this verification, please ignore this email</li>
            </ul>
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





















