<!DOCTYPE html>
<html>
<head>
    <title>Video Processing Demo</title>

    @vite(['resources/js/app.js'])
</head>
<body>

<h1>Laravel Reverb Video Processing</h1>

<button onclick="startProcessing()">
    Start Processing
</button>

<hr>

<h2 id="status">
    Waiting...
</h2>

<script>

    async function startProcessing()
    {
        await fetch('/process-video', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        });
    }

</script>

</body>
</html>
