<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
    >

    <title>Printing</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta
        http-equiv="refresh"
        content="5;url={{ route('kiosk.home') }}"
    >
</head>

<body class="w-screen h-screen overflow-hidden bg-slate-950">
<main class="relative w-screen h-screen overflow-hidden bg-gradient-to-br from-white via-slate-50 to-slate-200 flex items-center justify-center">
    <section class="relative z-10 flex flex-col items-center justify-center text-center px-6">
        <div class="w-20 h-20 rounded-2xl bg-slate-950 text-white flex items-center justify-center shadow-xl mb-4 animate-pulse">
            <span class="text-5xl">
                🖨️
            </span>
        </div>

        <div class="inline-flex items-center gap-2 rounded-full bg-emerald-100 border border-emerald-200 px-4 py-2 mb-4">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>

            <span class="text-sm font-black text-emerald-900">
                Print Queue Active
            </span>
        </div>

        <h1 class="text-4xl font-black text-slate-950 leading-none mb-3">
            Queued for Printing
        </h1>

        <p class="text-lg text-slate-600 leading-snug max-w-xl mb-5 font-bold">
            Please wait while your document is being prepared
            and sent to the printer.
        </p>

        <div class="flex items-center gap-3">
            <div class="w-4 h-4 rounded-full bg-slate-950 animate-bounce"></div>

            <div
                class="w-4 h-4 rounded-full bg-slate-950 animate-bounce"
                style="animation-delay: 0.15s"
            ></div>

            <div
                class="w-4 h-4 rounded-full bg-slate-950 animate-bounce"
                style="animation-delay: 0.3s"
            ></div>
        </div>
    </section>
</main>
</body>
</html>
