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
<main class="relative w-screen h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200 flex items-center justify-center">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-blue-200/40 blur-3xl"></div>
        <div class="absolute top-16 right-0 w-[30rem] h-[30rem] rounded-full bg-emerald-200/35 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/3 w-[26rem] h-[26rem] rounded-full bg-indigo-200/30 blur-3xl"></div>
    </div>

    <section class="relative z-10 w-full max-w-3xl rounded-[2rem] bg-white/90 border border-white p-10 shadow-2xl flex flex-col items-center justify-center text-center">
        <div class="w-32 h-32 rounded-[2rem] bg-slate-950 text-white flex items-center justify-center shadow-xl mb-6 animate-pulse">
            <span class="text-7xl">
                🖨️
            </span>
        </div>

        <div class="inline-flex items-center gap-3 rounded-full bg-emerald-100 border border-emerald-200 px-6 py-3 mb-6">
            <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>

            <span class="text-lg font-black text-emerald-900">
                Print Queue Active
            </span>
        </div>

        <h1 class="text-6xl font-black text-slate-950 leading-none mb-5">
            Queued for Printing
        </h1>

        <p class="text-2xl text-slate-600 leading-snug max-w-2xl mb-8 font-bold">
            Please wait while your document is being prepared
            and sent to the printer.
        </p>

        <div class="flex items-center gap-4">
            <div class="w-5 h-5 rounded-full bg-slate-950 animate-bounce"></div>

            <div
                class="w-5 h-5 rounded-full bg-slate-950 animate-bounce"
                style="animation-delay: 0.15s"
            ></div>

            <div
                class="w-5 h-5 rounded-full bg-slate-950 animate-bounce"
                style="animation-delay: 0.3s"
            ></div>
        </div>
    </section>
</main>
</body>
</html>
