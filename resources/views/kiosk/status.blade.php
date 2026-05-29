<x-kiosk-layout title="Print Status">
    @if (in_array($printJob->status, ['queued', 'printing'], true))
        <meta http-equiv="refresh" content="2">
    @endif

    @if ($printJob->status === 'completed')
        <meta http-equiv="refresh" content="5;url={{ route('kiosk.home') }}">
    @endif

    <div class="h-full flex items-center justify-center py-4">
        <div class="w-full max-w-4xl rounded-[2rem] bg-white/90 border border-white shadow-2xl p-10 text-center">
            @if ($printJob->status === 'queued')
                <div class="w-32 h-32 rounded-[2rem] bg-amber-100 text-amber-900 flex items-center justify-center shadow-lg mx-auto mb-6 animate-pulse">
                    <span class="text-7xl">⏳</span>
                </div>

                <h2 class="text-6xl font-black text-slate-950 leading-none mb-4">
                    Queued
                </h2>

                <p class="text-2xl text-slate-600 leading-snug max-w-2xl mx-auto mb-8 font-bold">
                    Your document is waiting for the printer.
                </p>

                <form method="POST" action="{{ route('kiosk.cancel', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-3xl bg-red-100 text-red-700 text-2xl font-black px-10 py-5 shadow-lg active:scale-95 transition"
                    >
                        Cancel Print Job
                    </button>
                </form>
            @elseif ($printJob->status === 'printing')
                <div class="w-32 h-32 rounded-[2rem] bg-slate-950 text-white flex items-center justify-center shadow-lg mx-auto mb-6 animate-pulse">
                    <span class="text-7xl">🖨️</span>
                </div>

                <h2 class="text-6xl font-black text-slate-950 leading-none mb-4">
                    Printing...
                </h2>

                <p class="text-2xl text-slate-600 leading-snug max-w-2xl mx-auto font-bold">
                    Please wait while your document is being printed.
                </p>
            @elseif ($printJob->status === 'completed')
                <div class="w-32 h-32 rounded-[2rem] bg-emerald-100 text-emerald-900 flex items-center justify-center shadow-lg mx-auto mb-6">
                    <span class="text-7xl">✅</span>
                </div>

                <h2 class="text-6xl font-black text-slate-950 leading-none mb-4">
                    Done
                </h2>

                <p class="text-2xl text-slate-600 leading-snug max-w-2xl mx-auto mb-6 font-bold">
                    Your document has been printed successfully.
                    Thank you for using {{ $globalKioskName ?? 'Piso Print' }}.
                </p>

                <div class="inline-flex items-center gap-3 rounded-full bg-emerald-100 border border-emerald-200 px-6 py-3">
                    <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>

                    <span class="text-lg font-black text-emerald-900">
                        Returning to home screen...
                    </span>
                </div>
            @elseif ($printJob->status === 'failed')
                <div class="w-32 h-32 rounded-[2rem] bg-red-100 text-red-900 flex items-center justify-center shadow-lg mx-auto mb-6">
                    <span class="text-7xl">⚠️</span>
                </div>

                <h2 class="text-6xl font-black text-slate-950 leading-none mb-4">
                    Print Failed
                </h2>

                <p class="text-2xl text-slate-600 leading-snug max-w-2xl mx-auto mb-8 font-bold">
                    Something went wrong while printing your document.
                    Please contact the operator.
                </p>

                <a
                    href="{{ route('kiosk.home') }}"
                    class="inline-flex items-center justify-center rounded-3xl bg-slate-950 text-white text-2xl font-black px-10 py-5 shadow-lg active:scale-95 transition"
                >
                    Back to Home
                </a>
            @endif
        </div>
    </div>
</x-kiosk-layout>
