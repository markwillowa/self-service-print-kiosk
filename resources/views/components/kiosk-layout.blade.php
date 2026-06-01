<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover"
    >

    <title>{{ $title ?? $globalKioskName ?? 'Piso Print' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
            touch-action: manipulation;
            -webkit-user-select: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            overflow: hidden;
            overscroll-behavior: none;
            background: #020617;
        }

        input,
        textarea,
        select {
            -webkit-user-select: text;
            user-select: text;
        }

        input,
        button,
        textarea,
        select {
            font-size: 16px;
        }

        iframe {
            border: 0;
        }

        ::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body
    draggable="false"
    class="w-screen h-screen overflow-hidden bg-slate-950 select-none touch-manipulation"
>
<main class="relative w-screen h-screen overflow-hidden bg-gradient-to-br from-blue-50 via-slate-100 to-emerald-50">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-28 -left-28 w-[32rem] h-[32rem] rounded-full bg-blue-400/35 blur-3xl"></div>

        <div class="absolute top-10 right-[-6rem] w-[36rem] h-[36rem] rounded-full bg-emerald-400/35 blur-3xl"></div>

        <div class="absolute bottom-[-8rem] left-[25%] w-[34rem] h-[34rem] rounded-full bg-indigo-400/30 blur-3xl"></div>

        <div class="absolute top-[35%] left-[45%] w-[18rem] h-[18rem] rounded-full bg-amber-300/25 blur-3xl"></div>

        <div
            class="absolute inset-0 opacity-[0.08]"
            style="
            background-image:
            linear-gradient(rgba(15, 23, 42, 0.12) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 23, 42, 0.12) 1px, transparent 1px);
            background-size: 32px 32px;
        "
        ></div>

        <div
            class="absolute inset-0 opacity-[0.12]"
            style="
            background-image:
            radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.35), transparent 28%),
            radial-gradient(circle at 80% 25%, rgba(16, 185, 129, 0.35), transparent 30%),
            radial-gradient(circle at 45% 90%, rgba(99, 102, 241, 0.35), transparent 32%);
        "
        ></div>
    </div>

    <section class="relative z-10 h-full flex flex-col px-5 py-4">
        <header class="h-[64px] shrink-0 flex items-center justify-between mb-3 gap-3">
            <div class="min-w-0 flex-1">
                <p class="text-[10px] uppercase tracking-[0.24em] text-slate-500 font-black leading-none mb-1">
                    Self-Service Kiosk
                </p>

                <h1
                    id="adminUnlockLogo"
                    class="text-3xl font-black text-slate-950 leading-none truncate max-w-[520px]"
                >
                    {{ $globalKioskName ?? 'Piso Print' }}
                </h1>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <div class="rounded-2xl bg-emerald-400 text-emerald-950 px-4 py-3 font-black text-base whitespace-nowrap shadow-sm">
                    Credit: ₱{{ $kioskCreditBalance ?? 0 }}
                </div>

                @if (($globalCompany?->kiosk_name ?? 'Piso Print') === 'Piso Print')
                    <div class="rounded-2xl bg-slate-950 text-white px-4 py-3 font-black text-base whitespace-nowrap shadow-sm">
                        ₱1/page
                    </div>
                @else
                    <div class="rounded-2xl bg-slate-950 text-white px-4 py-3 font-black text-base whitespace-nowrap shadow-sm">
                        Black: ₱{{ $globalCompany?->black_price_per_page ?? 1 }}
                        |
                        Colored: ₱{{ $globalCompany?->color_price_per_page ?? 3 }}
                    </div>
                @endif

                <button
                    type="button"
                    onclick="openShutdownModal()"
                    class="w-12 h-12 rounded-2xl bg-red-600 text-white flex items-center justify-center shadow-lg active:scale-95 transition shrink-0"
                >
                    <x-heroicon-o-power class="w-6 h-6" />
                </button>
            </div>
        </header>

        <div class="flex-1 min-h-0 overflow-hidden">
            {{ $slot }}
        </div>
    </section>
</main>

<div
    id="adminPinModal"
    class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50"
>
    <div class="w-[380px] rounded-3xl bg-white p-6 shadow-2xl">
        <h2 class="text-3xl font-black text-center mb-5">
            Admin Access
        </h2>

        <form
            method="POST"
            action="{{ route('admin.unlock') }}"
            autocomplete="off"
            class="space-y-3"
        >
            @csrf

            <input
                type="password"
                name="pin_code"
                placeholder="Enter PIN"
                required
                autofocus
                class="w-full rounded-2xl bg-slate-100 px-4 py-4 text-2xl font-black text-center"
            >

            <button
                type="submit"
                class="w-full rounded-2xl bg-slate-950 text-white py-4 text-xl font-black"
            >
                Unlock
            </button>

            <button
                type="button"
                onclick="closeAdminModal()"
                class="w-full rounded-2xl bg-slate-200 text-slate-900 py-4 text-xl font-black"
            >
                Cancel
            </button>
        </form>
    </div>
</div>

<div
    id="shutdownModal"
    class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center p-4"
>
    <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl text-center">
        <div class="w-20 h-20 rounded-3xl bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-5">
            <x-heroicon-o-power class="w-12 h-12" />
        </div>

        <h3 class="text-3xl font-black text-slate-950 mb-3">
            Turn Off Device?
        </h3>

        <p class="text-base text-slate-500 font-bold mb-6">
            This will safely shut down the Raspberry Pi.
        </p>

        <div class="grid grid-cols-2 gap-3">
            <button
                type="button"
                onclick="closeShutdownModal()"
                class="rounded-2xl bg-slate-200 text-slate-900 px-4 py-4 text-base font-black active:scale-95 transition"
            >
                Cancel
            </button>

            <form
                method="POST"
                action="{{ route('kiosk.shutdown') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-red-600 text-white px-4 py-4 flex items-center justify-center active:scale-95 transition"
                >
                    <x-heroicon-o-power class="w-6 h-6" />
                </button>
            </form>
        </div>
    </div>
</div>

<div
    id="printerOfflineModal"
    class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center p-4"
>
    <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl text-center">
        <div class="w-20 h-20 rounded-3xl bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-5">
            <x-heroicon-o-printer class="w-12 h-12" />
        </div>

        <h3 class="text-3xl font-black text-slate-950 mb-3">
            Printer Offline
        </h3>

        <p class="text-base text-slate-500 font-bold mb-6">
            Please turn on the printer or contact the operator.
        </p>

        <button
            type="button"
            onclick="closePrinterOfflineModal()"
            class="w-full rounded-2xl bg-slate-950 text-white px-4 py-4 text-base font-black active:scale-95 transition"
        >
            OK
        </button>
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
        const modal = document.getElementById(
            'adminPinModal'
        );

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal
                .querySelector('input[name="pin_code"]')
                ?.focus();
        }, 100);
    }

    function closeAdminModal() {
        const modal = document.getElementById(
            'adminPinModal'
        );

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    function openShutdownModal() {
        const modal = document.getElementById(
            'shutdownModal'
        );

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeShutdownModal() {
        const modal = document.getElementById(
            'shutdownModal'
        );

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    function openPrinterOfflineModal() {
        if (sessionStorage.getItem('admin_mode') === '1') {
            return;
        }

        const modal = document.getElementById(
            'printerOfflineModal'
        );

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePrinterOfflineModal() {
        const modal = document.getElementById(
            'printerOfflineModal'
        );

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    document
        .getElementById('adminPinModal')
        ?.addEventListener('click', (event) => {
            if (event.target.id === 'adminPinModal') {
                closeAdminModal();
            }
        });

    document
        .getElementById('shutdownModal')
        ?.addEventListener('click', (event) => {
            if (event.target.id === 'shutdownModal') {
                closeShutdownModal();
            }
        });

    document.addEventListener('gesturestart', (event) => {
        event.preventDefault();
    });

    document.addEventListener('dragstart', (event) => {
        event.preventDefault();
    });
</script>

@include('kiosk.partials.kiosk-lockdown')
</body>
</html>
