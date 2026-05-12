<x-kiosk-layout title="Insert Coins">
    <div class="max-w-6xl mx-auto w-full flex flex-col gap-5">
        <div class="flex justify-between items-start">
            <div class="min-w-0">
                <h2 class="text-4xl font-black">
                    Insert Coins
                </h2>

                <p class="text-base text-slate-600 truncate max-w-[550px]">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a
                    href="{{ route('kiosk.preview', $printJob) }}"
                    class="rounded-3xl bg-white px-6 py-4 text-lg font-black shadow-xl border border-slate-200"
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
                        class="rounded-3xl bg-slate-200 px-6 py-4 text-lg font-black"
                    >
                        Cancel
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-5">
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
            <div class="rounded-[2.5rem] bg-emerald-100 p-10 text-center shadow-xl">
                <p class="text-4xl font-black text-emerald-700 mb-8">
                    Payment Complete
                </p>

                <form
                    method="POST"
                    action="{{ route('kiosk.print', $printJob) }}"
                >
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
            <div class="rounded-[2.5rem] bg-white/90 p-8 shadow-xl">
                <p class="text-center text-3xl font-black mb-8">
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
                                class="w-full rounded-[2rem] bg-slate-950 text-white text-4xl font-black py-5 shadow-2xl active:scale-95 transition"
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
