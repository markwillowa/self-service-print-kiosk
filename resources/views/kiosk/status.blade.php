<x-kiosk-layout title="Print Status">
    @if (in_array($printJob->status, ['queued', 'printing'], true))
        <meta http-equiv="refresh" content="2">
    @endif

    @if ($printJob->status === 'completed')
        <meta http-equiv="refresh" content="5;url={{ route('kiosk.home') }}">
    @endif

    <div class="h-full flex items-center justify-center">
        <div class="w-full max-w-xl rounded-2xl bg-white/90 border border-white shadow-xl p-5 text-center">
            @if ($printJob->status === 'queued')
                <div class="w-20 h-20 rounded-2xl bg-amber-100 text-amber-900 flex items-center justify-center shadow-lg mx-auto mb-4 animate-pulse">
                    <span class="text-5xl">⏳</span>
                </div>

                <h2 class="text-4xl font-black text-slate-950 leading-none mb-3">
                    Queued
                </h2>

                <p class="text-lg text-slate-600 leading-snug max-w-md mx-auto mb-5 font-bold">
                    Your document is waiting for the printer.
                </p>

                <form method="POST" action="{{ route('kiosk.cancel', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-red-100 text-red-700 text-xl font-black px-8 py-4 shadow-lg active:scale-95 transition"
                    >
                        Cancel
                    </button>
                </form>
            @elseif ($printJob->status === 'printing')
                <div class="w-20 h-20 rounded-2xl bg-slate-950 text-white flex items-center justify-center shadow-lg mx-auto mb-4 animate-pulse">
                    <span class="text-5xl">🖨️</span>
                </div>

                <h2 class="text-4xl font-black text-slate-950 leading-none mb-3">
                    Printing...
                </h2>

                <p class="text-lg text-slate-600 leading-snug max-w-md mx-auto mb-4 font-bold">
                    Please wait while your document is being printed.
                </p>
            @elseif ($printJob->status === 'completed')
                <div class="w-20 h-20 rounded-2xl bg-emerald-100 text-emerald-900 flex items-center justify-center shadow-lg mx-auto mb-4">
                    <span class="text-5xl">✅</span>
                </div>

                <h2 class="text-4xl font-black text-slate-950 leading-none mb-3">
                    Done
                </h2>

                <p class="text-lg text-slate-600 leading-snug max-w-md mx-auto mb-4 font-bold">
                    Your document has been printed successfully.
                    Thank you for using Piso Print.
                </p>

                <div class="text-sm font-black text-slate-400">
                    Returning to home screen...
                </div>
            @elseif ($printJob->status === 'failed')
                <div class="w-20 h-20 rounded-2xl bg-red-100 text-red-900 flex items-center justify-center shadow-lg mx-auto mb-4">
                    <span class="text-5xl">⚠️</span>
                </div>

                <h2 class="text-4xl font-black text-slate-950 leading-none mb-3">
                    Print Failed
                </h2>

                <p class="text-lg text-slate-600 leading-snug max-w-md mx-auto mb-5 font-bold">
                    Something went wrong while printing your document.
                    Please contact the operator.
                </p>

                <a
                    href="{{ route('kiosk.home') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-950 text-white text-xl font-black px-8 py-4 shadow-lg active:scale-95 transition"
                >
                    Back to Home
                </a>
            @endif
        </div>
    </div>
</x-kiosk-layout>
