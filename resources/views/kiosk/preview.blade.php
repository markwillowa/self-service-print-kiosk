<x-kiosk-layout title="Preview Document">
    <div class="h-full flex flex-col min-h-0">
        <div class="flex justify-between items-center mb-3 shrink-0">
            <div class="min-w-0">
                <h2 class="text-2xl font-black">
                    Preview Document
                </h2>

                <p class="text-xs text-slate-600 truncate max-w-[330px]">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div class="rounded-2xl bg-white/90 px-5 py-2 shadow text-center">
                    <div class="text-xs font-bold text-slate-500">
                        Pages
                    </div>

                    <div class="text-2xl font-black leading-none">
                        {{ $printJob->pages }}
                    </div>
                </div>

                <div class="rounded-2xl bg-white/90 px-5 py-2 shadow text-center">
                    <div class="text-xs font-bold text-slate-500">
                        Total
                    </div>

                    <div class="text-2xl font-black leading-none">
                        ₱{{ $printJob->total_amount }}
                    </div>
                </div>

                <a
                    href="{{ route('kiosk.home') }}"
                    class="rounded-2xl bg-slate-200 px-5 py-3 text-base font-black"
                >
                    Cancel
                </a>

                <form method="POST" action="{{ route('kiosk.confirm', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-2xl bg-slate-950 text-white text-base font-black px-6 py-3 active:scale-95 transition"
                    >
                        Looks Good
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 min-h-0 rounded-3xl bg-white shadow-lg overflow-hidden">
            <iframe
                src="{{ route('kiosk.preview-file', $printJob) }}#toolbar=0&navpanes=0&scrollbar=0"
                class="w-full h-full"
            ></iframe>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
