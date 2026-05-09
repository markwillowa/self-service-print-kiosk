<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
<main class="w-[800px] h-[480px] bg-white p-8">
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-4xl font-bold mb-2">Insert Coins</h1>
            <p class="text-xl text-gray-600">
                {{ $printJob->original_filename }}
            </p>
        </div>

        <a
            href="{{ route('kiosk.home') }}"
            class="rounded-xl bg-gray-200 px-6 py-3 text-xl font-bold"
        >
            Cancel
        </a>
    </div>

    <div class="grid grid-cols-3 gap-6 text-center mb-8">
        <div class="rounded-2xl bg-gray-100 p-6">
            <div class="text-gray-500 text-lg mb-2">Pages</div>
            <div class="text-5xl font-bold">{{ $printJob->pages }}</div>
        </div>

        <div class="rounded-2xl bg-gray-100 p-6">
            <div class="text-gray-500 text-lg mb-2">Total</div>
            <div class="text-5xl font-bold">₱{{ $printJob->total_amount }}</div>
        </div>

        <div class="rounded-2xl bg-gray-100 p-6">
            <div class="text-gray-500 text-lg mb-2">Paid</div>
            <div class="text-5xl font-bold">₱{{ $printJob->paid_amount }}</div>
        </div>
    </div>

    @if ($printJob->status === 'paid')
        <div class="text-center">
            <p class="text-3xl font-bold text-green-600 mb-6">
                Payment complete
            </p>

            <form method="POST" action="{{ route('kiosk.print', $printJob) }}">
                @csrf

                <button
                    type="submit"
                    class="rounded-2xl bg-black text-white text-2xl font-bold px-12 py-5"
                >
                    Print Now
                </button>
            </form>
        </div>
    @else
        <div class="space-y-6">
            <p class="text-center text-2xl font-bold">
                Remaining:
                ₱{{ max($printJob->total_amount - $printJob->paid_amount, 0) }}
            </p>

            <div class="grid grid-cols-3 gap-4">
                <form method="POST" action="{{ route('kiosk.add-credit', [$printJob, 1]) }}">
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-black text-white text-3xl font-bold py-8"
                    >
                        ₱1
                    </button>
                </form>

                <form method="POST" action="{{ route('kiosk.add-credit', [$printJob, 5]) }}">
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-black text-white text-3xl font-bold py-8"
                    >
                        ₱5
                    </button>
                </form>

                <form method="POST" action="{{ route('kiosk.add-credit', [$printJob, 10]) }}">
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-black text-white text-3xl font-bold py-8"
                    >
                        ₱10
                    </button>
                </form>
            </div>
        </div>
    @endif
</main>
@include('kiosk.partials.auto-reset', ['seconds' => 60])
</body>
</html>
