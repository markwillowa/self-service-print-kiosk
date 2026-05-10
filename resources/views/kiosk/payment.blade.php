<x-kiosk-layout title="Insert Coins">
    <div class="h-full flex flex-col">
        <div class="flex justify-between items-start mb-5">
            <div class="min-w-0">
                <h2 class="text-4xl font-black">
                    Insert Coins
                </h2>

                <p class="text-base text-slate-600 truncate max-w-[550px]">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <a
                href="{{ route('kiosk.home') }}"
                class="rounded-3xl bg-slate-200 px-6 py-4 text-lg font-black shrink-0"
            >
                Cancel
            </a>
        </div>

        <div class="grid grid-cols-3 gap-5 mb-5">
            @foreach ([
                ['Pages', $printJob->selected_pages_count],
                ['Total', '₱' . $printJob->total_amount],
                ['Paid', '₱' . $printJob->paid_amount],
            ] as [$label, $value])
                <div class="rounded-[2rem] bg-white/90 p-6 shadow-xl text-center">
                    <div class="text-slate-500 text-sm font-black uppercase mb-2">
                        {{ $label }}
                    </div>

                    <div class="text-6xl font-black leading-none">
                        {{ $value }}
                    </div>
                </div>
            @endforeach
        </div>

        @if ($printJob->status === 'paid')
            <div class="rounded-[2.5rem] bg-emerald-100 p-8 text-center shadow-xl flex-1 flex flex-col items-center justify-center">
                <p class="text-4xl font-black text-emerald-700 mb-6">
                    Payment Complete
                </p>

                <form method="POST" action="{{ route('kiosk.print', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-3xl bg-slate-950 text-white text-3xl font-black px-16 py-6 active:scale-95 transition"
                    >
                        Print Now
                    </button>
                </form>
            </div>
        @else
            <div class="rounded-[2.5rem] bg-white/90 p-8 shadow-xl flex-1 flex flex-col justify-center">
                <p class="text-center text-3xl font-black mb-6">
                    Remaining:
                    ₱{{ max($printJob->total_amount - $printJob->paid_amount, 0) }}
                </p>

                <div class="grid grid-cols-3 gap-6">
                    @foreach ([1, 5, 10] as $amount)
                        <form
                            method="POST"
                            action="{{ route('kiosk.add-credit', [$printJob, $amount]) }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-[2rem] bg-slate-950 text-white text-5xl font-black py-10 shadow-2xl active:scale-95 transition"
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
