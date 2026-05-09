<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Piso Print</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
<main class="w-[800px] h-[480px] bg-white flex flex-col items-center justify-center text-center p-8">
    <h1 class="text-5xl font-bold mb-4">Piso Print</h1>
    <p class="text-2xl mb-8">₱1 per page</p>

    <a
        href="{{ route('kiosk.upload') }}"
        class="rounded-2xl bg-black text-white text-2xl font-bold px-10 py-5"
    >
        Start Printing
    </a>
</main>
</body>
</html>
