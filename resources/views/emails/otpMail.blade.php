 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .email-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .email-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .otp-code {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        .email-instructions {
            text-align: center;
            margin-bottom: 20px;
        }

        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-logo">
             
        </div>
        <div class="email-title">OTP Confirmation</div>
        <div class="otp-code">{{ $mailData['otp'] }}</div>
        <div class="email-instructions">
            Please use the above OTP code to confirm your authentication.
        </div>
        <div class="email-footer">
            This email was sent by Your Company Name. Please do not reply to this email.
        </div>
    </div>
</body>

</html>
