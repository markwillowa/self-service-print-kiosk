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
<main class="w-screen h-screen overflow-hidden bg-slate-100">
    <section class="h-full flex flex-col p-3">
        <header class="h-[52px] shrink-0 flex items-center justify-between mb-2">
            <div class="min-w-0">
                <p class="text-[9px] uppercase tracking-[0.22em] text-slate-500 font-black leading-none mb-1">
                    Self-Service Kiosk
                </p>

                <h1
                    id="adminUnlockLogo"
                    class="text-2xl font-black text-slate-950 leading-none"
                >
                    Piso Print
                </h1>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <div
                    id="kioskCreditBadge"
                    class="rounded-xl bg-emerald-600 text-white px-3 py-2 font-black text-sm"
                >
                    Credit: ₱{{ $kioskCreditBalance ?? 0 }}
                </div>

                <div class="rounded-xl bg-slate-950 text-white px-3 py-2 font-black text-sm">
                    ₱1/page
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
    <div class="w-[320px] rounded-2xl bg-white p-5 shadow-2xl">
        <h2 class="text-2xl font-black text-center mb-4">
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
                class="w-full rounded-2xl bg-slate-100 px-4 py-3 text-xl font-black text-center"
            >

            <button
                type="submit"
                class="w-full rounded-2xl bg-slate-950 text-white py-3 text-lg font-black"
            >
                Unlock
            </button>

            <button
                type="button"
                onclick="closeAdminModal()"
                class="w-full rounded-2xl bg-slate-200 text-slate-900 py-3 text-lg font-black"
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

<script>
    async function refreshKioskCredit() {
        try {
            const response = await fetch('{{ route('kiosk.credit') }}', {
                headers: {
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            const badge = document.getElementById('kioskCreditBadge');

            if (badge) {
                badge.textContent = 'Credit: ₱' + data.credit_balance;
            }
        } catch (error) {
        }
    }

    refreshKioskCredit();

    setInterval(refreshKioskCredit, 1000);
</script>

@include('kiosk.partials.kiosk-lockdown')
</body>
</html>
