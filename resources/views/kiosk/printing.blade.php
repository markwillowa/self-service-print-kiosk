<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Printing</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta
        http-equiv="refresh"
        content="5;url={{ route('kiosk.home') }}"
    >
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
<main class="w-[800px] h-[480px] bg-white flex flex-col items-center justify-center text-center p-8">
    <div class="text-7xl mb-8">
        🖨️
    </div>

    <h1 class="text-5xl font-bold mb-4">
        Queued for Printing
    </h1>

    <p class="text-2xl text-gray-600">
        Please wait... Your document is being prepared.
    </p>
</main>
</body>
</html>
