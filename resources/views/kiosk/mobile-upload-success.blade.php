<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Upload Complete</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
<main class="w-full max-w-md bg-white rounded-[2rem] p-6 shadow-2xl text-center">
    <div class="flex justify-center mb-4">
        <x-heroicon-o-check-circle class="w-16 h-16 text-emerald-500" />
    </div>

    <h1 class="text-3xl font-black text-slate-950 mb-3">
        Upload Complete
    </h1>

    <p class="text-sm text-slate-600 leading-relaxed">
        Your file has been uploaded successfully.
        Please continue on the PisoPrint kiosk.
    </p>
</main>
</body>
</html>
