<x-kiosk-layout title="Insert Coins">
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

                <form
                    method="POST"
                    action="{{ route('kiosk.cancel', $printJob) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="rounded-[2rem] bg-slate-200 px-8 h-20 text-xl font-black text-slate-900 shadow-xl active:scale-95 transition"
                    >
                        Cancel
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6 shrink-0">
            @foreach ([
                ['Pages', $printJob->selected_pages_count],
                ['Total', '₱' . $printJob->total_amount],
                ['Paid', '₱' . $printJob->paid_amount],
            ] as [$label, $value])
                <div class="rounded-[3rem] bg-white/90 border border-white p-8 shadow-2xl text-center">
                    <div class="text-lg text-slate-500 font-black uppercase mb-3">
                        {{ $label }}
                    </div>

                    <div class="text-7xl font-black text-slate-950 leading-none">
                        {{ $value }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex-1 min-h-0">
            @if ($printJob->status === 'paid')
                <div class="h-full rounded-[3rem] bg-emerald-100 border border-emerald-200 p-12 text-center shadow-2xl flex flex-col items-center justify-center">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-emerald-600 text-white flex items-center justify-center shadow-2xl mb-8">
                        <span class="text-7xl">
                            ✓
                        </span>
                    </div>

                    <p class="text-6xl font-black text-emerald-800 mb-10 leading-none">
                        Payment Complete
                    </p>

                    <form
                        method="POST"
                        action="{{ route('kiosk.print', $printJob) }}"
                    >
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
                <div class="h-full rounded-[3rem] bg-white/90 border border-white p-10 shadow-2xl flex flex-col justify-center">
                    <p class="text-center text-5xl font-black text-slate-950 mb-10">
                        Remaining:
                        <span class="text-emerald-700">
                            ₱{{ max($printJob->total_amount - $printJob->paid_amount, 0) }}
                        </span>
                    </p>

                    <div class="grid grid-cols-3 gap-8">
                        @foreach ([1, 5, 10] as $amount)
                            <form
                                method="POST"
                                action="{{ route('kiosk.add-credit', [$printJob, $amount]) }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="w-full rounded-[3rem] bg-slate-950 text-white text-7xl font-black py-16 shadow-2xl active:scale-95 transition"
                                >
                                    ₱{{ $amount }}
                                </button>
                            </form>
                        @endforeach
                    </div>

                    <p class="text-center text-xl text-slate-500 font-bold mt-10">
                        Insert coins or use the test buttons above.
                    </p>
                </div>
            @endif
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 60])
</x-kiosk-layout>
