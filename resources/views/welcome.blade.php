<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content={{ csrf_token() }}>
    <title>Websockets</title>


</head>
<h1>hello</h1>

<body>
    <!-- scripts -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    {{-- @vite('resources/js/app.js') --}}
    <script type="module">

     // console.log(Echo);
        // var channel = Echo.channel('notifcation');
        // channel.listen('NewTrade', function(data) {
        //     console.log(data)
        // });
        // Echo.connector.pusher.connection.bind('connected', () => {
        //     console.log('connected');
        // });
        // Echo.channel('notifcation')
        //         .listen('NewTrade', (message) => {
        //              console.log(message)
        //         });

             // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('46f6e5b6b66aa1ea4753', {
            // encrypted: true,
            cluster: 'ap2'
        });

        // Subscribe to the channel we specified in our Laravel Event
        var channel = pusher.subscribe('notification');
            channel.bind('App\\Events\\NewTrade', function(data) {
            console.log(data);
        });
    </script>
</body>

</html>
