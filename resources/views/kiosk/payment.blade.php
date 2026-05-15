<x-kiosk-layout title="Insert Coins">
    <meta http-equiv="refresh" content="1">

    <div class="h-full flex flex-col min-h-0 gap-3">
        <div class="flex justify-between items-start shrink-0">
            <div class="min-w-0">
                <h2 class="text-3xl font-black text-slate-950 leading-none mb-1">
                    Insert Coins
                </h2>

                <p class="text-sm text-slate-600 truncate max-w-[420px] font-bold">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a
                    href="{{ route('kiosk.preview', $printJob) }}"
                    class="rounded-xl bg-slate-200 px-4 h-11 flex items-center justify-center text-sm font-black text-slate-900 shadow-lg active:scale-95 transition"
                >
                    Back
                </a>

                <form method="POST" action="{{ route('kiosk.cancel', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-xl bg-red-100 px-4 h-11 text-sm font-black text-red-700 shadow-lg active:scale-95 transition"
                    >
                        Cancel
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-2 shrink-0">
            @foreach ([
                ['Pages', $printJob->selected_pages_count],
                ['Total', '₱' . $printJob->total_amount],
                ['Paid', '₱' . $printJob->paid_amount],
                ['Credit', '₱' . ($kioskCreditBalance ?? 0)],
            ] as [$label, $value])
                <div class="rounded-2xl bg-white/90 border border-white p-3 shadow-lg text-center">
                    <div class="text-[10px] text-slate-500 font-black uppercase mb-1">
                        {{ $label }}
                    </div>

                    <div class="text-2xl font-black text-slate-950 leading-none">
                        {{ $value }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex-1 min-h-0">
            @if ($printJob->status === 'paid')
                <div class="h-full rounded-2xl bg-emerald-100 border border-emerald-200 p-5 text-center shadow-xl flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-xl mb-4">
                        <span class="text-5xl">✓</span>
                    </div>

                    <p class="text-4xl font-black text-emerald-800 mb-5 leading-none">
                        Payment Complete
                    </p>

                    <form method="POST" action="{{ route('kiosk.print', $printJob) }}">
                        @csrf

                        <button
                            type="submit"
                            class="rounded-2xl bg-slate-950 text-white text-2xl font-black px-10 py-4 shadow-xl active:scale-95 transition"
                        >
                            Print Now
                        </button>
                    </form>
                </div>
            @else
                <div class="h-full rounded-2xl bg-white/90 border border-white p-5 shadow-xl flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 rounded-2xl bg-slate-950 text-white flex items-center justify-center shadow-xl mb-4 animate-pulse">
                        <x-heroicon-o-banknotes class="w-10 h-10" />
                    </div>

                    <p class="text-3xl font-black text-slate-950 mb-3">
                        Please Insert Coins
                    </p>

                    <p class="text-2xl font-black text-slate-700 mb-5">
                        Remaining:
                        <span class="text-emerald-700">
                            ₱{{ max($printJob->total_amount - $printJob->paid_amount, 0) }}
                        </span>
                    </p>

                    <div class="inline-flex items-center gap-2 rounded-full bg-emerald-100 border border-emerald-200 px-4 py-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>

                        <span class="text-sm font-black text-emerald-900">
                            Waiting for coin slot payment...
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 60])
</x-kiosk-layout>
