<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
    >

    <title>{{ $title ?? 'Piso Print' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 flex items-center justify-center overflow-hidden select-none">
<main class="relative w-[1024px] h-[600px] overflow-hidden bg-gradient-to-br from-white via-slate-50 to-slate-200 shadow-2xl">
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-blue-200/30 blur-3xl"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-emerald-200/30 blur-3xl"></div>

    <section class="relative z-10 h-full flex flex-col p-8">
        <header class="flex items-center justify-between mb-6 shrink-0">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500 font-black mb-1">
                    Self-Service Kiosk
                </p>

                <h1 class="text-4xl font-black text-slate-950">
                    Piso Print
                </h1>
            </div>

            <div class="rounded-full bg-slate-950 text-white px-6 py-3 font-black text-xl shadow-xl">
                ₱1 / page
            </div>
        </header>

        <div class="flex-1 min-h-0">
            {{ $slot }}
        </div>
    </section>
</main>
</body>
</html>
