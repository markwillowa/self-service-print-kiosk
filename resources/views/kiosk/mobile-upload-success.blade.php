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

    <meta
        http-equiv="refresh"
        content="5;url={{ route('kiosk.mobile-upload') }}"
    >
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
<main class="w-full max-w-md bg-white rounded-[2rem] p-6 shadow-2xl text-center">
    <div class="flex justify-center mb-4">
        <x-heroicon-o-check-circle class="w-16 h-16 text-emerald-500" />
    </div>

    <h1 class="text-3xl font-black text-slate-950 mb-3">
        Upload Complete
    </h1>

    <p class="text-sm text-slate-600 leading-relaxed mb-4">
        Your file has been uploaded successfully.
        Please continue on the PisoPrint kiosk.
    </p>

    <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4">
        <p class="text-xs font-bold text-emerald-800">
            Returning to upload page in 5 seconds...
        </p>
    </div>
</main>
</body>
</html>
