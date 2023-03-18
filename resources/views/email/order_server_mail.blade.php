<!DOCTYPE html>
<html>

<head>
    <title>Mail from {{ \Config::get('app.name') }}</title>
</head>

<body>
    <p>Dear Customer</p>
    <p>
        Thank you for choosing {{ \Config::get('app.name') }} as your web hosting partner.<br />
        We have received your order and shall inform you once we have ready your server.<br />
        Best regards
    </p>
    <strong>Your {{ \Config::get('app.name') }} Team</strong>
</body>

</html>
