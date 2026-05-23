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
<main class="w-full max-w-md bg-white rounded-[2rem] p-8 shadow-2xl text-center">
    <div class="flex justify-center mb-5">
        <div class="w-24 h-24 rounded-[2rem] bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-lg">
            <x-heroicon-o-check-circle class="w-14 h-14" />
        </div>
    </div>

    <h1 class="text-4xl font-black text-slate-950 mb-4">
        Upload Complete
    </h1>

    <p class="text-base text-slate-600 leading-relaxed mb-6">
        Your file has been uploaded successfully.
        Please continue on the {{ $globalKioskName ?? 'Piso Print' }} kiosk.
    </p>

    <div class="rounded-[1.5rem] bg-emerald-50 border border-emerald-200 p-5">
        <div class="flex items-center justify-center gap-2 mb-2">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>

            <span class="text-sm font-black text-emerald-900">
                    Upload Successful
                </span>
        </div>

        <p class="text-sm font-bold text-emerald-800 leading-relaxed">
            Returning to upload page in 5 seconds...
        </p>
    </div>
</main>
</body>
</html>
