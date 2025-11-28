<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Complete Your Registration</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #eef2f7;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }

        .container {
            max-width: 480px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .header {
            background: #034e7a;
            color: #fff;
            padding: 25px 20px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 0.5px;
        }

        .content {
            padding: 30px 25px;
            color: #333;
        }

        .content p {
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background: #034e7a;
            color: #fff;
            padding: 14px 22px;
            font-size: 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #034e7a;
        }

        .footer {
            background: #f5f7fa;
            padding: 15px;
            text-align: center;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Complete Your Registration</h2>
        </div>

        <div class="content">
            <p>Hello,</p>
            <p>Click the button below to complete your registration and set your password:</p>
            <p style="text-align:center; margin:30px 0;">
                <a href="{{ $url }}" class="btn">Complete Registration</a>
            </p>
            <p>This link will expire in 15 minutes.</p>
            <p>Thank you!</p>
        </div>

        <div class="footer">
            © {{ date('Y') }} Your Company. All rights reserved.
        </div>
    </div>
</body>

</html>