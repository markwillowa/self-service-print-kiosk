<x-kiosk-layout title="Print Status">
    @if (in_array($printJob->status, ['queued', 'printing'], true))
        <meta http-equiv="refresh" content="2">
    @endif

    @if ($printJob->status === 'completed')
        <meta http-equiv="refresh" content="5;url={{ route('kiosk.home') }}">
    @endif

    <div class="h-full flex items-center justify-center text-center">
        <div class="bg-white/85 rounded-[2rem] p-10 shadow-xl w-full max-w-xl">
            @if ($printJob->status === 'queued')
                <div class="text-7xl mb-6">⏳</div>
                <h2 class="text-5xl font-black mb-4">Queued</h2>
                <p class="text-2xl text-slate-600">Waiting for printer...</p>
            @elseif ($printJob->status === 'printing')
                <div class="text-7xl mb-6">🖨️</div>
                <h2 class="text-5xl font-black mb-4">Printing...</h2>
                <p class="text-2xl text-slate-600">Please wait</p>
            @elseif ($printJob->status === 'completed')
                <div class="text-7xl mb-6">✅</div>
                <h2 class="text-5xl font-black mb-4">Done</h2>
                <p class="text-2xl text-slate-600">Thank you</p>
            @elseif ($printJob->status === 'failed')
                <div class="text-7xl mb-6">⚠️</div>
                <h2 class="text-5xl font-black mb-4">Print Failed</h2>
                <p class="text-2xl text-slate-600 mb-6">Please contact the operator</p>

                <a
                    href="{{ route('kiosk.home') }}"
                    class="inline-block rounded-3xl bg-slate-950 text-white text-2xl font-black px-10 py-5"
                >
                    Back to Home
                </a>
            @endif
        </div>
    </div>
</x-kiosk-layout>
