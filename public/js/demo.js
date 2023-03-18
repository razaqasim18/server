  Echo.channel('trades').listen('.newMessage', (e) => {
            console.log(e)
            document.querySelector('#div-data').innerHTML = e.message
        });
