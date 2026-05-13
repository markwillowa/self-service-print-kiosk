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
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-blue-200/30 blur-3xl"></div>

    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-emerald-200/30 blur-3xl"></div>

    <section class="relative z-10 flex flex-col items-center justify-center text-center px-10">
        <div class="w-40 h-40 rounded-[3rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl mb-10 animate-pulse">
                <span class="text-8xl">
                    🖨️
                </span>
        </div>

        <div class="inline-flex items-center gap-3 rounded-full bg-emerald-100 border border-emerald-200 px-6 py-3 mb-8">
            <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>

            <span class="text-lg font-black text-emerald-900">
                    Print Queue Active
                </span>
        </div>

        <h1 class="text-7xl font-black text-slate-950 leading-none mb-6">
            Queued for Printing
        </h1>

        <p class="text-3xl text-slate-600 leading-relaxed max-w-3xl mb-10">
            Please wait while your document is being
            prepared and sent to the printer.
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
