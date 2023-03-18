<!DOCTYPE html>
<html>

<head>
    <title>Mail from {{ \Config::get('app.name') }}</title>
</head>

<body>
    <p>Dear Customer</p>
    <p>
        Thank you for choosing {{ \Config::get('app.name') }} as your web hosting partner.<br />
        Please find given below Customer login <br />
        Email: {{ $email }}<br />
        Password: {{ $password }}<br />
        Web portal login: {{ \Config::get('app.url') . '/login' }}<br />
        Best regards
    </p>
    <strong>Your {{ \Config::get('app.name') }} Team</strong>
</body>

</html>
