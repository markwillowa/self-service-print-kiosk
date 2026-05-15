<x-kiosk-layout title="Print Status">
    @if (in_array($printJob->status, ['queued', 'printing'], true))
        <meta http-equiv="refresh" content="2">
    @endif

    @if ($printJob->status === 'completed')
        <meta http-equiv="refresh" content="5;url={{ route('kiosk.home') }}">
    @endif

    <div class="h-full flex items-center justify-center">
        <div class="w-full max-w-4xl rounded-[3rem] bg-white/90 border border-white shadow-2xl p-16 text-center">
            @if ($printJob->status === 'queued')
                <div class="w-40 h-40 rounded-[3rem] bg-amber-100 text-amber-900 flex items-center justify-center shadow-xl mx-auto mb-10 animate-pulse">
                    <span class="text-8xl">⏳</span>
                </div>

                <h2 class="text-7xl font-black text-slate-950 leading-none mb-6">
                    Queued
                </h2>

                <p class="text-3xl text-slate-600 leading-relaxed max-w-2xl mx-auto mb-10">
                    Your document is waiting for the printer to become available.
                </p>

                <form method="POST" action="{{ route('kiosk.cancel', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-[2rem] bg-red-100 text-red-700 text-3xl font-black px-12 py-6 shadow-2xl active:scale-95 transition"
                    >
                        Cancel
                    </button>
                </form>
            @elseif ($printJob->status === 'printing')
                <div class="w-40 h-40 rounded-[3rem] bg-slate-950 text-white flex items-center justify-center shadow-xl mx-auto mb-10 animate-pulse">
                    <span class="text-8xl">🖨️</span>
                </div>

                <h2 class="text-7xl font-black text-slate-950 leading-none mb-6">
                    Printing...
                </h2>

                <p class="text-3xl text-slate-600 leading-relaxed max-w-2xl mx-auto mb-10">
                    Please wait while your document is being printed.
                </p>
            @elseif ($printJob->status === 'completed')
                <div class="w-40 h-40 rounded-[3rem] bg-emerald-100 text-emerald-900 flex items-center justify-center shadow-xl mx-auto mb-10">
                    <span class="text-8xl">✅</span>
                </div>

                <h2 class="text-7xl font-black text-slate-950 leading-none mb-6">
                    Done
                </h2>

                <p class="text-3xl text-slate-600 leading-relaxed max-w-2xl mx-auto mb-10">
                    Your document has been printed successfully.
                    Thank you for using Piso Print.
                </p>

                <div class="text-xl font-black text-slate-400">
                    Returning to home screen...
                </div>
            @elseif ($printJob->status === 'failed')
                <div class="w-40 h-40 rounded-[3rem] bg-red-100 text-red-900 flex items-center justify-center shadow-xl mx-auto mb-10">
                    <span class="text-8xl">⚠️</span>
                </div>

                <h2 class="text-7xl font-black text-slate-950 leading-none mb-6">
                    Print Failed
                </h2>

                <p class="text-3xl text-slate-600 leading-relaxed max-w-2xl mx-auto mb-12">
                    Something went wrong while printing your document.
                    Please contact the operator.
                </p>

                <a
                    href="{{ route('kiosk.home') }}"
                    class="inline-flex items-center justify-center rounded-[2rem] bg-slate-950 text-white text-3xl font-black px-12 py-6 shadow-2xl active:scale-95 transition"
                >
                    Back to Home
                </a>
            @endif
        </div>
    </div>
</x-kiosk-layout>
