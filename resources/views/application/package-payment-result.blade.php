<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Package Payment</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            padding: 24px;
        }

        .container {
            max-width: 480px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.12);
            padding: 24px 24px 20px;
            text-align: center;
        }

        .status-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 28px;
        }

        .status-icon.success {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-icon.error {
            background: #fee2e2;
            color: #b91c1c;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 8px;
            color: #0f172a;
        }

        p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .hint {
            font-size: 13px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="status-icon {{ $success ? 'success' : 'error' }}">
            {{ $success ? '✓' : '!' }}
        </div>
        <h1>{{ $success ? 'Payment Completed' : 'Payment Status' }}</h1>
        <p>{{ $message }}</p>
        <p class="hint">
            You can now close this tab and return to the Uni-Gate app to continue.
        </p>
    </div>
</body>
</html>


