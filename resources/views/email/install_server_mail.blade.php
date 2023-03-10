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
        Server details <br />

        Web portal login:- <br />
        Web portal : {{ env('APP_URL') . '/login' }}<br />
        Username: {{ $server->web_user }}<br />
        Password: {{ $server->web_password }}<br />
        Desktop client login:- <br />
        Server IP: {{ $server->server_ip }}<br />
        Username: {{ $server->client_user }}<br />
        Password: {{ $server->client_password }}<br />
        UUID: {{ $server->uuid }}<br />

        Best regards
    </p>
    <strong>Your {{ env('APP_MAIL_NAME') }} Team</strong>
</body>

</html>
