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

        /*
        |--------------------------------------------------------------------------
        | 800x480 Raspberry Pi Touchscreen Compact Mode
        |--------------------------------------------------------------------------
        */

        @media (max-width: 900px) and (max-height: 520px) {
            section.relative.z-10 {
                padding: 0.75rem !important;
            }

            header {
                margin-bottom: 0.5rem !important;
            }

            header h1 {
                font-size: 1.5rem !important;
                line-height: 1.1 !important;
            }

            header p {
                font-size: 0.55rem !important;
                letter-spacing: 0.18em !important;
            }

            header .rounded-full {
                padding: 0.45rem 0.8rem !important;
                font-size: 0.85rem !important;
            }

            .text-7xl {
                font-size: 2.4rem !important;
                line-height: 0.95 !important;
            }

            .text-6xl {
                font-size: 2.1rem !important;
                line-height: 1 !important;
            }

            .text-5xl {
                font-size: 1.75rem !important;
                line-height: 1.05 !important;
            }

            .text-4xl {
                font-size: 1.45rem !important;
                line-height: 1.05 !important;
            }

            .text-3xl {
                font-size: 1.2rem !important;
                line-height: 1.15 !important;
            }

            .text-2xl {
                font-size: 1rem !important;
                line-height: 1.2 !important;
            }

            .text-xl {
                font-size: 0.9rem !important;
                line-height: 1.2 !important;
            }

            .text-lg {
                font-size: 0.8rem !important;
                line-height: 1.2 !important;
            }

            .text-base {
                font-size: 0.75rem !important;
                line-height: 1.2 !important;
            }

            .p-16,
            .p-12,
            .p-10,
            .p-8,
            .p-6,
            .p-5 {
                padding: 0.75rem !important;
            }

            .px-20,
            .px-12,
            .px-10,
            .px-9,
            .px-8,
            .px-7,
            .px-6,
            .px-5 {
                padding-left: 0.85rem !important;
                padding-right: 0.85rem !important;
            }

            .py-8,
            .py-7,
            .py-6,
            .py-5,
            .py-4,
            .py-3 {
                padding-top: 0.55rem !important;
                padding-bottom: 0.55rem !important;
            }

            .gap-10,
            .gap-8,
            .gap-6,
            .gap-5,
            .gap-4 {
                gap: 0.65rem !important;
            }

            .mb-10,
            .mb-8,
            .mb-7,
            .mb-6,
            .mb-5,
            .mb-4,
            .mb-3,
            .mb-2 {
                margin-bottom: 0.5rem !important;
            }

            .mt-5,
            .mt-4,
            .mt-3,
            .mt-2 {
                margin-top: 0.4rem !important;
            }

            .h-20 {
                height: 3rem !important;
            }

            .w-40,
            .h-40 {
                width: 4.5rem !important;
                height: 4.5rem !important;
            }

            .w-36,
            .h-36,
            .w-32,
            .h-32 {
                width: 4rem !important;
                height: 4rem !important;
            }

            .w-24,
            .h-24,
            .w-20,
            .h-20 {
                width: 3.2rem !important;
                height: 3.2rem !important;
            }

            .w-16,
            .h-16 {
                width: 2.6rem !important;
                height: 2.6rem !important;
            }

            .w-14,
            .h-14,
            .w-11,
            .h-11,
            .w-10,
            .h-10,
            .w-9,
            .h-9,
            .w-8,
            .h-8,
            .w-7,
            .h-7,
            .w-6,
            .h-6 {
                width: 1.45rem !important;
                height: 1.45rem !important;
            }

            .rounded-\[3rem\],
            .rounded-\[2\.5rem\],
            .rounded-\[2rem\] {
                border-radius: 1.1rem !important;
            }

            .rounded-3xl {
                border-radius: 1rem !important;
            }

            .rounded-2xl {
                border-radius: 0.85rem !important;
            }

            .shadow-2xl,
            .shadow-xl {
                box-shadow: 0 8px 20px rgb(15 23 42 / 0.12) !important;
            }

            .grid-cols-\[1\.1fr_0\.9fr\],
            .grid-cols-\[0\.9fr_1\.1fr\] {
                grid-template-columns: 1fr 1fr !important;
            }

            .max-w-5xl {
                max-width: 92vw !important;
            }

            .max-w-4xl {
                max-width: 88vw !important;
            }

            .max-w-3xl,
            .max-w-2xl {
                max-width: 100% !important;
            }

            iframe {
                transform: scale(1);
                transform-origin: top left;
            }

            #adminPinModal .w-\[420px\] {
                width: 330px !important;
            }

            #adminPinModal input {
                height: 3rem !important;
                font-size: 1.2rem !important;
                padding: 0.5rem !important;
            }

            #adminPinModal button {
                height: 3rem !important;
                padding: 0.5rem !important;
                font-size: 1rem !important;
            }
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

            <div class="flex items-center gap-3">
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
