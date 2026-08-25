<x-kiosk-layout title="{{ __('Paper Check') }}">
    <div class="h-full flex flex-col min-h-0 gap-4 py-2">
        <div class="flex justify-between items-start shrink-0">
            <div class="min-w-0">
                <h2 class="text-4xl font-black text-slate-950 leading-none mb-2">
                    {{ __('Paper Check') }}
                </h2>

                <p class="text-base text-slate-600 truncate max-w-[600px] font-bold">
                    {{ $printJob->original_filename }}
                </p>
            </div>

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

        <div class="flex-1 min-h-0 rounded-3xl bg-white border border-white p-8 shadow-xl flex flex-col items-center justify-center text-center">
            <div class="w-32 h-32 rounded-3xl bg-amber-100 text-amber-700 flex items-center justify-center shadow-xl mb-6">
                <x-heroicon-o-document-text class="w-20 h-20" />
            </div>

            <p class="text-6xl font-black text-slate-950 mb-5 leading-none">
                {{ __('Paper Check Required') }}
            </p>

            <p class="text-3xl font-black text-slate-700 mb-4">
                {{ __('Please ensure the correct paper size is loaded in the printer.') }}
            </p>

            <div class="grid grid-cols-3 gap-4 w-full max-w-3xl mb-8">
                <div class="rounded-3xl bg-slate-100 p-5">
                    <div class="text-xs text-slate-500 font-black uppercase mb-2">
                        {{ __('Pages') }}
                    </div>

                    <div class="text-4xl font-black text-slate-950">
                        {{ $printJob->selected_pages_count }}
                    </div>
                </div>

                <div class="rounded-3xl bg-slate-100 p-5">
                    <div class="text-xs text-slate-500 font-black uppercase mb-2">
                        {{ __('Copies') }}
                    </div>

                    <div class="text-4xl font-black text-slate-950">
                        {{ $printJob->copies }}
                    </div>
                </div>

                <div class="rounded-3xl bg-slate-100 p-5">
                    <div class="text-xs text-slate-500 font-black uppercase mb-2">
                        {{ __('Total Amount') }}
                    </div>

                    <div class="text-4xl font-black text-emerald-700">
                        ₱{{ $printJob->total_amount }}
                    </div>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('kiosk.print', $printJob) }}"
                class="w-full max-w-xl"
            >
                @csrf

                <button
                    type="submit"
                    class="w-full rounded-3xl bg-slate-950 text-white h-20 text-3xl font-black shadow-xl active:scale-95 transition"
                >
                    {{ __('Proceed to Print') }}
                </button>
            </form>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 60])
</x-kiosk-layout>
