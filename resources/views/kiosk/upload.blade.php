<x-kiosk-layout title="Uploaded Files">
    <meta http-equiv="refresh" content="5">

    <div class="h-full flex flex-col min-h-0">
        <div class="flex items-center justify-between mb-3 shrink-0">
            <div>
                <h1 class="text-3xl font-black text-slate-950 mb-1 leading-none">
                    Uploaded Files
                </h1>

                <p class="text-sm text-slate-500 font-bold">
                    Select a file uploaded from your phone
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button
                    onclick="
                        const icon = document.getElementById('refresh-icon');

                        icon.classList.add('animate-spin');

                        setTimeout(() => {
                            window.location.reload();
                        }, 300);
                    "
                    type="button"
                    class="flex items-center gap-1 rounded-xl bg-white border border-slate-200 px-3 py-3 text-sm font-black text-slate-900 shadow-lg active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-path
                        id="refresh-icon"
                        class="w-4 h-4"
                    />

                    Refresh
                </button>

                <a
                    href="{{ route('kiosk.home') }}"
                    class="flex items-center rounded-xl bg-red-100 px-3 py-3 text-sm font-black text-red-700 active:scale-95 transition"
                >
                    Cancel
                </a>

                <a
                    href="{{ route('kiosk.transfer') }}"
                    class="flex items-center gap-1 rounded-xl bg-slate-200 px-3 py-3 text-sm font-black text-slate-900 active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Back
                </a>
            </div>
        </div>

        <div class="flex-1 min-h-0 overflow-hidden">
            <div class="bg-white/90 rounded-2xl border border-white shadow-xl h-full overflow-y-auto p-3">
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
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 border border-slate-200 p-3 mb-2 active:scale-[0.99] transition shadow">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-11 h-11 rounded-xl bg-slate-950 text-white flex items-center justify-center shrink-0 shadow">
                                        <x-heroicon-o-document-text class="w-6 h-6" />
                                    </div>

                                    <div class="min-w-0">
                                        <div class="text-lg font-black text-slate-900 truncate leading-tight">
                                            {{ $printJob->original_filename }}
                                        </div>

                                        <div class="text-xs text-slate-500 mt-1 font-bold">
                                            {{ $printJob->pages }} pages
                                            •
                                            {{ strtoupper($printJob->original_extension) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 rounded-xl bg-slate-950 text-white px-4 py-2 text-sm font-black shrink-0 shadow">
                                    Select

                                    <x-heroicon-o-arrow-right class="w-4 h-4" />
                                </div>
                            </div>
                        </button>
                    </form>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-center px-6">
                        <x-heroicon-o-inbox class="w-20 h-20 text-slate-300 mb-3" />

                        <h2 class="text-3xl font-black text-slate-700 mb-2">
                            No Uploaded Files
                        </h2>

                        <p class="text-base text-slate-500 max-w-md leading-snug font-bold">
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
