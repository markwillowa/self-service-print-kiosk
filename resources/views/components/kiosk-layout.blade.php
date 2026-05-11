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

    <style>
        * {
            touch-action: manipulation;

            -webkit-user-select: none;

            user-select: none;
        }

        html,
        body {
            overscroll-behavior: none;
        }

        html {
            touch-action: manipulation;
        }
    </style>
</head>

<body
    draggable="false"
    class="min-h-screen bg-slate-950 flex items-center justify-center overflow-hidden select-none touch-manipulation"
>
<main class="relative w-[1024px] h-[600px] overflow-hidden bg-gradient-to-br from-white via-slate-50 to-slate-200 shadow-2xl">
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-blue-200/30 blur-3xl"></div>

    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-emerald-200/30 blur-3xl"></div>

    <section class="relative z-10 h-full flex flex-col p-8">
        <header class="flex items-center justify-between mb-6 shrink-0">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500 font-black mb-1">
                    Self-Service Kiosk
                </p>

                <h1
                    id="adminUnlockLogo"
                    class="text-4xl font-black text-slate-950"
                >
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

<div
    id="adminPinModal"
    class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50"
>
    <div class="w-[420px] rounded-[2rem] bg-white p-8 shadow-2xl">
        <h2 class="text-3xl font-black text-center mb-6">
            Admin Access
        </h2>

        <form
            method="POST"
            action="{{ route('admin.unlock') }}"
            autocomplete="off"
            class="space-y-5"
        >
            @csrf

            <input
                type="password"
                name="pin_code"
                placeholder="Enter PIN"
                required
                autofocus
                class="w-full rounded-3xl bg-slate-100 px-5 py-5 text-2xl font-black text-center"
            >

            <button
                type="submit"
                class="w-full rounded-3xl bg-slate-950 text-white py-5 text-2xl font-black"
            >
                Unlock
            </button>

            <button
                type="button"
                onclick="closeAdminModal()"
                class="w-full rounded-3xl bg-slate-200 text-slate-900 py-5 text-xl font-black"
            >
                Cancel
            </button>
        </form>
    </div>
</div>

<script>
    let adminTapCount = 0;

    let adminTapTimer;

    const adminLogo = document.getElementById(
        'adminUnlockLogo'
    );

    adminLogo?.addEventListener('click', () => {
        adminTapCount++;

        clearTimeout(adminTapTimer);

        adminTapTimer = setTimeout(() => {
            adminTapCount = 0;
        }, 2000);

        if (adminTapCount >= 5) {
            adminTapCount = 0;

            openAdminModal();
        }
    });

    function openAdminModal() {
        document
            .getElementById('adminPinModal')
            .classList
            .remove('hidden');

        document
            .getElementById('adminPinModal')
            .classList
            .add('flex');
    }

    function closeAdminModal() {
        document
            .getElementById('adminPinModal')
            .classList
            .remove('flex');

        document
            .getElementById('adminPinModal')
            .classList
            .add('hidden');
    }

    document
        .getElementById('adminPinModal')
        ?.addEventListener('click', (event) => {
            if (event.target.id === 'adminPinModal') {
                closeAdminModal();
            }
        });
</script>

@include('kiosk.partials.kiosk-lockdown')
</body>
</html>
