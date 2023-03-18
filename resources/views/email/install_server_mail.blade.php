<!DOCTYPE html>
<html>

<head>
    <title>Mail from {{ \Config::get('app.name') }}</title>
</head>

<body>
    <p>Dear Customer</p>
    <p>
        Thank you for choosing {{ \Config::get('app.name') }} as your web hosting partner.<br />
        Server details <br />

        Web portal login:- <br />
        Web portal : {{ \Config::get('app.url'). '/login' }}<br />
        Username: {{ $server->web_user }}<br />
        Password: {{ $server->web_password }}<br />
        Desktop client login:- <br />
        Server IP: {{ $server->server_ip }}<br />
        Username: {{ $server->client_user }}<br />
        Password: {{ $server->client_password }}<br />
        UUID: {{ $server->uuid }}<br />

        Best regards
    </p>
    <strong>Your {{ \Config::get('app.name') }} Team</strong>
</body>

</html>
