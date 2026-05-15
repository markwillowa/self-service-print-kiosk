<x-kiosk-layout title="Insert Coins">
    <meta http-equiv="refresh" content="1">

    <div class="h-full flex flex-col min-h-0 gap-6">
        <div class="flex justify-between items-start shrink-0">
            <div class="min-w-0">
                <h2 class="text-6xl font-black text-slate-950 leading-none mb-2">
                    Insert Coins
                </h2>

                <p class="text-xl text-slate-600 truncate max-w-[780px] font-bold">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <div class="flex items-center gap-4 shrink-0">
                <a
                    href="{{ route('kiosk.preview', $printJob) }}"
                    class="rounded-[2rem] bg-slate-200 px-8 h-20 flex items-center justify-center text-xl font-black text-slate-900 shadow-xl active:scale-95 transition"
                >
                    Back
                </a>

                <form method="POST" action="{{ route('kiosk.cancel', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-[2rem] bg-red-100 px-8 h-20 text-xl font-black text-red-700 shadow-xl active:scale-95 transition"
                    >
                        Cancel
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-6 shrink-0">
            @foreach ([
                ['Pages', $printJob->selected_pages_count],
                ['Total', '₱' . $printJob->total_amount],
                ['Paid', '₱' . $printJob->paid_amount],
                ['Credit', '₱' . ($kioskCreditBalance ?? 0)],
            ] as [$label, $value])
                <div class="rounded-[3rem] bg-white/90 border border-white p-8 shadow-2xl text-center">
                    <div class="text-lg text-slate-500 font-black uppercase mb-3">
                        {{ $label }}
                    </div>

                    <div class="text-6xl font-black text-slate-950 leading-none">
                        {{ $value }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex-1 min-h-0">
            @if ($printJob->status === 'paid')
                <div class="h-full rounded-[3rem] bg-emerald-100 border border-emerald-200 p-12 text-center shadow-2xl flex flex-col items-center justify-center">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-emerald-600 text-white flex items-center justify-center shadow-2xl mb-8">
                        <span class="text-7xl">✓</span>
                    </div>

                    <p class="text-6xl font-black text-emerald-800 mb-10 leading-none">
                        Payment Complete
                    </p>

                    <form method="POST" action="{{ route('kiosk.print', $printJob) }}">
                        @csrf

                        <button
                            type="submit"
                            class="rounded-[2rem] bg-slate-950 text-white text-4xl font-black px-20 py-8 shadow-2xl active:scale-95 transition"
                        >
                            Print Now
                        </button>
                    </form>
                </div>
            @else
                <div class="h-full rounded-[3rem] bg-white/90 border border-white p-12 shadow-2xl flex flex-col items-center justify-center text-center">
                    <div class="w-36 h-36 rounded-[3rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl mb-8 animate-pulse">
                        <x-heroicon-o-banknotes class="w-20 h-20" />
                    </div>

                    <p class="text-5xl font-black text-slate-950 mb-5">
                        Please Insert Coins
                    </p>

                    <p class="text-4xl font-black text-slate-700 mb-8">
                        Remaining:
                        <span class="text-emerald-700">
                            ₱{{ max($printJob->total_amount - $printJob->paid_amount, 0) }}
                        </span>
                    </p>

                    <div class="inline-flex items-center gap-3 rounded-full bg-emerald-100 border border-emerald-200 px-7 py-4">
                        <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>

                        <span class="text-xl font-black text-emerald-900">
                            Waiting for coin slot payment...
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 60])
</x-kiosk-layout>
