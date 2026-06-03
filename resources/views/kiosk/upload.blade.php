<x-kiosk-layout title="Uploaded Files">
    <meta http-equiv="refresh" content="5">

    <div class="h-full flex flex-col min-h-0 gap-4 py-2">
        <div class="flex items-center justify-between shrink-0 gap-4">
            <div class="min-w-0">
                <h1 class="text-4xl font-black text-slate-950 mb-2 leading-none">
                    Uploaded Files
                </h1>

                <p class="text-base text-slate-500 font-bold">
                    Select a file uploaded from your phone
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <button
                    onclick="
                        const icon = document.getElementById('refresh-icon');

                        icon.classList.add('animate-spin');

                        setTimeout(() => {
                            window.location.reload();
                        }, 300);
                    "
                    type="button"
                    class="flex items-center gap-2 rounded-2xl bg-white border border-slate-200 px-5 h-14 text-base font-black text-slate-900 shadow-lg active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-path
                        id="refresh-icon"
                        class="w-5 h-5"
                    />

                    Refresh
                </button>

                <a
                    href="{{ route('kiosk.home') }}"
                    class="flex items-center justify-center rounded-2xl bg-red-100 px-5 h-14 text-base font-black text-red-700 active:scale-95 transition shadow-lg"
                >
                    Cancel
                </a>

                <a
                    href="{{ route('kiosk.transfer') }}"
                    class="flex items-center justify-center gap-2 rounded-2xl bg-slate-200 px-5 h-14 text-base font-black text-slate-900 active:scale-95 transition shadow-lg"
                >
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                    Back
                </a>
            </div>
        </div>

        <div class="flex-1 min-h-0 overflow-hidden">
            <div class="bg-white/90 rounded-3xl border border-white shadow-xl h-full overflow-y-auto p-4">
                @forelse ($printJobs as $printJob)
                    <form
                        method="POST"
                        action="{{ route('kiosk.select-upload', $printJob) }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="w-full text-left"
                        >
                            <div class="flex items-center justify-between rounded-3xl bg-slate-50 border border-slate-200 p-4 mb-3 active:scale-[0.99] transition shadow">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0 shadow">
                                        <x-heroicon-o-document-text class="w-9 h-9" />
                                    </div>

                                    <div class="min-w-0">
                                        <div class="text-2xl font-black text-slate-900 truncate leading-tight">
                                            {{ $printJob->original_filename }}
                                        </div>

                                        <div class="text-base text-slate-500 mt-1 font-bold">
                                            @if ($printJob->conversion_status === 'completed')
                                                {{ $printJob->pages }} pages
                                                •
                                                {{ strtoupper($printJob->original_extension) }}
                                            @else
                                                Ready
                                                •
                                                {{ strtoupper($printJob->original_extension) }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 rounded-2xl bg-slate-950 text-white px-6 py-3 text-base font-black shrink-0 shadow">
                                    @if ($printJob->conversion_status === 'completed')
                                        Select
                                    @else
                                        Prepare
                                    @endif

                                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                                </div>
                            </div>
                        </button>
                    </form>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-center px-6">
                        <x-heroicon-o-inbox class="w-28 h-28 text-slate-300 mb-5" />

                        <h2 class="text-5xl font-black text-slate-700 mb-4">
                            No Uploaded Files
                        </h2>

                        <p class="text-xl text-slate-500 max-w-2xl leading-snug font-bold">
                            Upload a document from your phone first
                            using the {{ $globalKioskName ?? 'Piso Print' }} Wi-Fi upload page.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
