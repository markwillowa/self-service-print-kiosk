<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Piso Print' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-900 flex items-center justify-center overflow-hidden">
<main class="relative w-[800px] h-[480px] bg-gradient-to-br from-white via-slate-50 to-slate-200 overflow-hidden shadow-2xl">
    <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-blue-200/40"></div>
    <div class="absolute -bottom-28 -left-28 w-80 h-80 rounded-full bg-emerald-200/40"></div>

    <section class="relative z-10 h-full flex flex-col p-8">
        <header class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-500 font-bold">
                    Self-Service Kiosk
                </p>
                <h1 class="text-3xl font-black text-slate-950">
                    Piso Print
                </h1>
            </div>

            <div class="rounded-full bg-slate-950 text-white px-5 py-3 font-bold text-lg shadow-lg">
                ₱1 / page
            </div>
        </header>

        <div class="flex-1">
            {{ $slot }}
        </div>
    </section>
</main>
</body>
</html>
