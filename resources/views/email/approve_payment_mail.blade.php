<!DOCTYPE html>
<html>

<head>
    <title>Mail from {{ \Config::get('app.name') }}</title>
</head>

<body>
    <center>
        {{-- <h2 style="padding: 23px;background: #b3deb8a1;border-bottom: 6px green solid;">
<a href="https://itsolutionstuff.com">Visit Our Website : ItSolutionStuff.com</a>
</h2> --}}
    </center>
    <p>Dear Customer</p>
    <p>
        Thank you for choosing {{ \Config::get('app.name') }} as your web hosting partner.<br />
        We have received your {{ $amount }} from {{ $payment }}.<br />
        Balance as been updated in your portal.
        Best regards
    </p>
    <strong>Your {{ \Config::get('app.name') }} Team</strong>
</body>

</html>
