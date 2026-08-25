<x-kiosk-layout title="{{ __('Insert Coins') }}">
    <meta http-equiv="refresh" content="1">

    <div class="h-full flex flex-col min-h-0 gap-4 py-2">
        <div class="flex justify-between items-start shrink-0">
            <div class="min-w-0">
                <h2 class="text-4xl font-black text-slate-950 leading-none mb-2">
                    {{ __('Insert Coins') }}
                </h2>

                <p class="text-base text-slate-600 truncate max-w-[600px] font-bold">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a
                    href="{{ route('kiosk.preview', $printJob) }}"
                    class="rounded-2xl bg-slate-200 px-5 h-14 flex items-center justify-center text-base font-black text-slate-900 shadow-lg active:scale-95 transition"
                >
                    {{ __('Back') }}
                </a>

                <form method="POST" action="{{ route('kiosk.cancel', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-2xl bg-red-100 px-5 h-14 text-base font-black text-red-700 shadow-lg active:scale-95 transition"
                    >
                        {{ __('Cancel') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 shrink-0">
            @foreach ([
                [__('Pages'), $printJob->selected_pages_count],
                [__('Total Amount'), '₱' . $printJob->total_amount],
                [__('Paid Amount'), '₱' . $printJob->paid_amount],
                [__('Credit'), '₱' . ($kioskCreditBalance ?? 0)],
            ] as [$label, $value])
                <div class="rounded-3xl bg-white border border-white p-5 shadow-lg text-center">
                    <div class="text-xs text-slate-500 font-black uppercase mb-2">
                        {{ $label }}
                    </div>

                    <div class="text-4xl font-black text-slate-950 leading-none">
                        {{ $value }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex-1 min-h-0">
            @if ($printJob->status === 'paid')
                <div class="h-full rounded-3xl bg-emerald-100 border border-emerald-200 p-8 text-center shadow-xl flex flex-col items-center justify-center">
                    <div class="w-28 h-28 rounded-3xl bg-emerald-600 text-white flex items-center justify-center shadow-xl mb-6">
                        <x-heroicon-o-check class="w-16 h-16" />
                    </div>

                    <p class="text-6xl font-black text-emerald-800 mb-4 leading-none">
                        {{ __('Payment Complete') }}
                    </p>

                    <p class="text-2xl font-black text-emerald-700">
                        {{ __('Printing automatically...') }}
                    </p>

                    <script>
                        setTimeout(() => {
                            window.location.href = '{{ route('kiosk.paper-check', $printJob) }}';
                        }, 800);
                    </script>
                </div>
            @else
                <div class="h-full rounded-3xl bg-white border border-white p-8 shadow-xl flex flex-col items-center justify-center text-center">
                    <div class="w-28 h-28 rounded-3xl bg-slate-950 text-white flex items-center justify-center shadow-xl mb-6 animate-pulse">
                        <x-heroicon-o-banknotes class="w-14 h-14" />
                    </div>

                    <p class="text-5xl font-black text-slate-950 mb-4 leading-none">
                        {{ __('Please Insert Coins') }}
                    </p>

                    <p class="text-4xl font-black text-slate-700 mb-6">
                        {{ __('Remaining Balance') }}:
                        <span class="text-emerald-700">
                            ₱{{ max($printJob->total_amount - $printJob->paid_amount, 0) }}
                        </span>
                    </p>

                    <div class="inline-flex items-center gap-3 rounded-full bg-emerald-100 border border-emerald-200 px-6 py-3">
                        <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>

                        <span class="text-lg font-black text-emerald-900">
                            {{ __('Waiting for coin slot payment...') }}
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 60])
</x-kiosk-layout>
