
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .otp {
            background-color: #f2f2f2;
            padding: 20px;
            text-align: center;
        }

        .otp-code {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        .instructions {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
           <h1>Times Multicare Hospital Ltd</h1>
        </div>
        <div class="otp">
            <h2>One-Time Password (OTP)</h2>
            <p>Dear {{$data['name']}},</p>
            <p class="otp-code">{{$data['otp']}}</p>
            <p class="instructions">Please use this OTP to complete your authentication process.</p>
        </div>
        
    </div>
</body>
</html>