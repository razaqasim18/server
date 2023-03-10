<!DOCTYPE html>
<html>

<head>
    <title>Mail from {{ env('APP_MAIL_NAME') }}</title>
</head>

<body>
    <center>
        {{-- <h2 style="padding: 23px;background: #b3deb8a1;border-bottom: 6px green solid;">
<a href="https://itsolutionstuff.com">Visit Our Website : ItSolutionStuff.com</a>
</h2> --}}
    </center>
    <p>Dear Customer</p>
    <p>
        Thank you for choosing {{ env('APP_MAIL_NAME') }} as your web hosting partner.<br />
        We have received your order and shall inform you once we have ready your server.<br />
        Best regards
    </p>
    <strong>Your {{ env('APP_MAIL_NAME') }} Team</strong>
</body>

</html>
