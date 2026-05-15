<!DOCTYPE html>
<html>
<head>
    <title>Reverb Chat Demo</title>
    @vite(['resources/js/app.js'])
</head>
<body>

<h1>Laravel Reverb Chat</h1>

<input type="text" id="message" placeholder="Type message">
<button onclick="sendMessage()">Send</button>

<hr>

<div id="messages"></div>

<script>

    async function sendMessage()
    {
        const message = document.getElementById('message').value;

        await fetch('/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: message
            })
        });

        document.getElementById('message').value = '';
    }

</script>

</body>
</html>
