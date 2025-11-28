<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Set Your Password</title>
    <style>
        /* Base Styles */
        body {
            margin: 0;
            padding: 0;
            background: #eef2f7;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }

        .container {
            max-width: 480px;
            width: 90%;
            margin: 60px auto;
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
            padding: 35px 25px;
            color: #333;
        }

        .content p {
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .errors {
            background: #ffe0e0;
            color: #b02a37;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .errors ul {
            margin: 0;
            padding-left: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            border-color: #0366d6;
            outline: none;
            box-shadow: 0 0 0 2px rgba(3, 102, 214, 0.2);
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 14px;
            background: #034e7a;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background: #02375b;
        }

        .footer {
            background: #034e7a;
            padding: 15px;
            text-align: center;
            font-size: 13px;
            color: #fff;
        }

        /* Responsive Styles */
        @media (max-width: 500px) {
            .header h2 {
                font-size: 22px;
            }

            .content {
                padding: 25px 20px;
            }

            .content p {
                font-size: 15px;
                margin-bottom: 20px;
            }

            .form-group input {
                padding: 12px 10px;
            }

            .btn-submit {
                padding: 12px;
                font-size: 15px;
            }

            .container {
                margin: 40px 10px;
            }
        }

        @media (max-width: 350px) {
            .header h2 {
                font-size: 20px;
            }

            .content p {
                font-size: 14px;
            }

            .btn-submit {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        @if (session('success'))
        <div class="success" style="padding: 10px;">
            <p style="color: #22c55e;">{{ session('success') }}</p>
        </div>
        @endif
        <div class="header">
            <h2>Set Your Password</h2>
        </div>
        <div class="content">
            <p>Please choose a secure password to complete your registration.</p>

            @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ url('/set-password') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Enter your password">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required placeholder="Confirm your password">
                </div>

                <button type="submit" class="btn-submit">Set Password</button>
            </form>
        </div>

        <div class="footer">
            © {{ date('Y') }} Your Company. All rights reserved.
        </div>
    </div>

</body>

</html>