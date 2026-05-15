<x-kiosk-layout title="Uploaded Files">
    <meta http-equiv="refresh" content="5">

    <div class="h-full flex flex-col min-h-0">
        <div class="flex items-center justify-between mb-8 shrink-0">
            <div>
                <h1 class="text-6xl font-black text-slate-950 mb-2 leading-none">
                    Uploaded Files
                </h1>

                <p class="text-xl text-slate-500 font-bold">
                    Select a file uploaded from your phone
                </p>
            </div>

            <div class="flex items-center gap-4">
                <button
                    onclick="
                        const icon = document.getElementById('refresh-icon');

                        icon.classList.add('animate-spin');

                        setTimeout(() => {
                            window.location.reload();
                        }, 400);
                    "
                    type="button"
                    class="flex items-center gap-3 rounded-[2rem] bg-white border border-slate-200 px-7 py-5 text-xl font-black text-slate-900 shadow-xl active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-path
                        id="refresh-icon"
                        class="w-7 h-7"
                    />

                    Refresh
                </button>

                <a
                    href="{{ route('kiosk.home') }}"
                    class="flex items-center gap-3 rounded-[2rem] bg-red-100 px-7 py-5 text-xl font-black text-red-700 active:scale-95 transition"
                >
                    Cancel
                </a>

                <a
                    href="{{ route('kiosk.transfer') }}"
                    class="flex items-center gap-3 rounded-[2rem] bg-slate-200 px-7 py-5 text-xl font-black text-slate-900 active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-left class="w-7 h-7" />
                    Back
                </a>
            </div>
        </div>

        <div class="flex-1 min-h-0 overflow-hidden">
            <div class="bg-white/90 rounded-[3rem] border border-white shadow-2xl h-full overflow-y-auto p-6">
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
                            <div class="flex items-center justify-between rounded-[2rem] bg-slate-50 border border-slate-200 p-6 mb-5 active:scale-[0.99] transition shadow-lg">
                                <div class="flex items-center gap-6 min-w-0">
                                    <div class="w-20 h-20 rounded-[2rem] bg-slate-950 text-white flex items-center justify-center shrink-0 shadow-xl">
                                        <x-heroicon-o-document-text class="w-10 h-10" />
                                    </div>

                                    <div class="min-w-0">
                                        <div class="text-3xl font-black text-slate-900 truncate leading-tight">
                                            {{ $printJob->original_filename }}
                                        </div>

                                        <div class="text-lg text-slate-500 mt-2 font-bold">
                                            {{ $printJob->pages }} pages
                                            •
                                            {{ strtoupper($printJob->original_extension) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 rounded-[1.5rem] bg-slate-950 text-white px-6 py-4 text-xl font-black shrink-0 shadow-xl">
                                    Select

                                    <x-heroicon-o-arrow-right class="w-6 h-6" />
                                </div>
                            </div>
                        </button>
                    </form>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-center px-10">
                        <x-heroicon-o-inbox class="w-32 h-32 text-slate-300 mb-6" />

                        <h2 class="text-5xl font-black text-slate-700 mb-4">
                            No Uploaded Files
                        </h2>

                        <p class="text-2xl text-slate-500 max-w-2xl leading-relaxed">
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
