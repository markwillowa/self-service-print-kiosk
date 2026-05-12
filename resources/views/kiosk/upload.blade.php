<x-kiosk-layout title="Uploaded Files">
    <meta http-equiv="refresh" content="5">

    <div class="h-full flex flex-col">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h1 class="text-4xl font-black text-slate-950 mb-1">
                    Uploaded Files
                </h1>

                <p class="text-sm text-slate-500">
                    Select a file uploaded from your phone
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button
                    onclick="
                        const icon = document.getElementById('refresh-icon');

                        icon.classList.add('animate-spin');

                        setTimeout(() => {
                            window.location.reload();
                        }, 400);
                    "
                    type="button"
                    class="flex items-center gap-2 rounded-2xl bg-white border border-slate-200 px-5 py-3 text-sm font-black text-slate-900 shadow-lg active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-path
                        id="refresh-icon"
                        class="w-4 h-4"
                    />

                    Refresh
                </button>

                <a
                    href="{{ route('kiosk.transfer') }}"
                    class="flex items-center gap-2 rounded-2xl bg-slate-200 px-5 py-3 text-sm font-black text-slate-900 active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-left class="w-4 h-4" />

                    Back
                </a>
            </div>
        </div>

        <div class="flex-1 overflow-hidden">
            <div class="bg-white/90 rounded-[2rem] border border-white shadow-2xl h-full overflow-y-auto p-4">
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
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 border border-slate-200 p-4 mb-3 active:scale-[0.99] transition">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                                        <x-heroicon-o-document-text class="w-7 h-7" />
                                    </div>

                                    <div class="min-w-0">
                                        <div class="text-lg font-black text-slate-900 truncate">
                                            {{ $printJob->original_filename }}
                                        </div>

                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ $printJob->pages }} pages
                                            •
                                            {{ strtoupper($printJob->original_extension) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 rounded-xl bg-slate-950 text-white px-4 py-2 text-sm font-black shrink-0">
                                    <x-heroicon-o-arrow-right class="w-4 h-4" />

                                    Select
                                </div>
                            </div>
                        </button>
                    </form>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-center">
                        <x-heroicon-o-inbox class="w-20 h-20 text-slate-300 mb-4" />

                        <h2 class="text-2xl font-black text-slate-700 mb-2">
                            No Uploaded Files
                        </h2>

                        <p class="text-sm text-slate-500 max-w-sm">
                            Upload a document from your phone first
                            using the PisoPrint Wi-Fi upload page.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
