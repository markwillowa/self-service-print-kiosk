<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover"
    >

    <title>{{ $title ?? 'Piso Print' }}</title>

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
<main
    class="relative w-screen h-screen overflow-hidden bg-gradient-to-br from-white via-slate-50 to-slate-200"
>
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

            <div class="flex items-center gap-4">
                <div class="rounded-full bg-emerald-600 text-white px-6 py-3 font-black text-xl shadow-xl">
                    Credit: ₱{{ $kioskCreditBalance ?? 0 }}
                </div>

                <div class="rounded-full bg-slate-950 text-white px-6 py-3 font-black text-xl shadow-xl">
                    ₱1 / page
                </div>
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
    <div class="w-[420px] max-w-[calc(100vw-2rem)] rounded-[2rem] bg-white p-8 shadow-2xl">
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
        const modal = document.getElementById(
            'adminPinModal'
        );

        modal
            .classList
            .remove('hidden');

        modal
            .classList
            .add('flex');

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

        modal
            .classList
            .remove('flex');

        modal
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
