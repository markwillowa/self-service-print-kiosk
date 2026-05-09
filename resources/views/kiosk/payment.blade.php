<x-kiosk-layout title="Insert Coins">
    <div class="h-full flex flex-col">
        <div class="flex justify-between items-start mb-4">
            <div class="min-w-0">
                <h2 class="text-3xl font-black">
                    Insert Coins
                </h2>

                <p class="text-sm text-slate-600 truncate max-w-[420px]">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <a
                href="{{ route('kiosk.home') }}"
                class="rounded-2xl bg-slate-200 px-5 py-2 text-base font-black shrink-0"
            >
                Cancel
            </a>
        </div>

        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="rounded-3xl bg-white/85 p-4 shadow-lg text-center">
                <div class="text-slate-500 text-sm font-bold mb-1">
                    Pages
                </div>

                <div class="text-4xl font-black">
                    {{ $printJob->pages }}
                </div>
            </div>

            <div class="rounded-3xl bg-white/85 p-4 shadow-lg text-center">
                <div class="text-slate-500 text-sm font-bold mb-1">
                    Total
                </div>

                <div class="text-4xl font-black">
                    ₱{{ $printJob->total_amount }}
                </div>
            </div>

            <div class="rounded-3xl bg-white/85 p-4 shadow-lg text-center">
                <div class="text-slate-500 text-sm font-bold mb-1">
                    Paid
                </div>

                <div class="text-4xl font-black">
                    ₱{{ $printJob->paid_amount }}
                </div>
            </div>
        </div>

        @if ($printJob->status === 'paid')
            <div class="rounded-[2rem] bg-emerald-100 p-5 text-center shadow-lg">
                <p class="text-2xl font-black text-emerald-700 mb-4">
                    Payment Complete
                </p>

                <form method="POST" action="{{ route('kiosk.print', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-3xl bg-slate-950 text-white text-2xl font-black px-12 py-4 active:scale-95 transition"
                    >
                        Print Now
                    </button>
                </form>
            </div>
        @else
            <div class="rounded-[2rem] bg-white/85 p-5 shadow-lg flex-1 flex flex-col justify-center">
                <p class="text-center text-xl font-black mb-4">
                    Remaining:
                    ₱{{ max($printJob->total_amount - $printJob->paid_amount, 0) }}
                </p>

                <div class="grid grid-cols-3 gap-4">
                    @foreach ([1, 5, 10] as $amount)
                        <form
                            method="POST"
                            action="{{ route('kiosk.add-credit', [$printJob, $amount]) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-3xl bg-slate-950 text-white text-3xl font-black py-6 shadow-xl active:scale-95 transition"
                            >
                                ₱{{ $amount }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 60])
</x-kiosk-layout>
