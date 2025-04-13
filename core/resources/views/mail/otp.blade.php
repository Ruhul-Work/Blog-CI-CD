<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>{{ get_option('title') }}</title>
  <style>
    /* Global Styles */
    body {
      font-family: Arial, sans-serif;
      color: #333333;
    }

    /* Container Styles */
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #f5fdff;
      border-radius: 6px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Header Styles */
    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    .logo {
      max-width: 150px;
    }

    /* Content Styles */
    .content {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 6px;
    }

    /* Table Styles */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f5f5f5;
    }

    /* Footer Styles */
    .footer {
      margin-top: 20px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
        <img src="{{asset(get_option('logo'))}}" style="width:230px" />
    </div>
    <div class="content">
      <h2>{{ get_option('title') }} - Reset Password</h2>
      <p>Dear {{$data['name']}},</p>
      <p>Thank you for choosing {{ get_option('title') }}. To ensure the security of your account and protect your personal information, we have implemented an additional layer of authentication through a One-Time Password (OTP) verification process.</p>
      <p>Please find below your OTP details:</p>
      <table>
        <tr>
          <th>OTP:</th>
          <td style="color:cornflowerblue; font-size:27px;"><strong>[{{$data['otp']}}]</strong></td>
        </tr>
      </table>
      <p>This OTP will expire in 15 minutes, so please make sure to enter it promptly.</p>
      <p>If you did not initiate this request or if you have any concerns regarding your account security, please contact our support team immediately at {{ get_option('email') }}</p>
      <p>For the security of your account, we recommend following these guidelines to create a strong password:</p>
      <ul>
        <li>Use a minimum of 8 characters.</li>
        <li>Include a combination of uppercase and lowercase letters.</li>
        <li>Include numbers and special characters, such as @, #, $, %.</li>
        <li>Avoid using common or easily guessable passwords.</li>
        <li>Regularly update your password and avoid reusing it across multiple platforms.</li>
      </ul>
      <p>Thank you for your cooperation.</p>
    </div>
    <div class="footer">
      <p>Best regards,<br> {{ get_option('title') }}</p>
    </div>
  </div>
</body>
</html>
