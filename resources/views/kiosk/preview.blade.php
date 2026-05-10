<x-kiosk-layout title="Preview Document">
    <div class="h-full flex flex-col min-h-0 gap-4">
        <div class="flex items-center justify-between shrink-0">
            <div class="min-w-0">
                <h2 class="text-3xl font-black">
                    Preview Document
                </h2>

                <p class="text-sm text-slate-600 truncate max-w-[500px]">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="rounded-3xl bg-white/90 px-5 py-3 shadow-xl text-center min-w-[120px]">
                    <div class="text-xs font-black text-slate-500 uppercase mb-1">
                        Pages
                    </div>

                    <div class="text-3xl font-black leading-none">
                        {{ $printJob->selected_pages_count }}
                    </div>
                </div>

                <div class="rounded-3xl bg-white/90 px-5 py-3 shadow-xl text-center min-w-[120px]">
                    <div class="text-xs font-black text-slate-500 uppercase mb-1">
                        Total
                    </div>

                    <div class="text-3xl font-black leading-none">
                        ₱{{ $printJob->total_amount }}
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 min-h-0 rounded-[2rem] bg-white shadow-2xl overflow-hidden relative">
            <button
                type="button"
                onclick="openPreviewFullscreen()"
                class="absolute top-4 right-4 z-10 rounded-2xl bg-slate-950 text-white px-5 py-3 text-sm font-black shadow-xl active:scale-95 transition"
            >
                Maximize
            </button>

            <iframe
                src="{{ $previewUrl }}#toolbar=0&navpanes=0&scrollbar=0"
                class="w-full h-full"
            ></iframe>
        </div>

        <div class="shrink-0 rounded-[2rem] bg-white/90 p-5 shadow-2xl">
            <div class="grid grid-cols-[1fr_260px_auto_auto] gap-4 items-end">
                <div>
                    <div class="text-xs font-black text-slate-500 uppercase mb-2">
                        Print Mode
                    </div>

                    <form
                        method="POST"
                        action="{{ route('kiosk.update-print-mode', $printJob) }}"
                        class="grid grid-cols-2 gap-3"
                    >
                        @csrf

                        <button
                            type="submit"
                            name="print_mode"
                            value="black"
                            class="{{ $printJob->print_mode === 'black'
                        ? 'bg-slate-950 text-white'
                        : 'bg-slate-100 text-slate-900'
                    }} rounded-3xl h-16 text-lg font-black transition flex flex-col items-center justify-center"
                        >
                            <span>Black Only</span>

                            <span class="text-sm font-bold opacity-80">
                        ₱1/page
                    </span>
                        </button>

                        <button
                            type="submit"
                            name="print_mode"
                            value="color"
                            class="{{ $printJob->print_mode === 'color'
                        ? 'bg-blue-600 text-white'
                        : 'bg-slate-100 text-slate-900'
                    }} rounded-3xl h-16 text-lg font-black transition flex flex-col items-center justify-center"
                        >
                            <span>Colored</span>

                            <span class="text-sm font-bold opacity-80">
                        ₱2/page
                    </span>
                        </button>
                    </form>
                </div>

                <form
                    method="POST"
                    action="{{ route('kiosk.update-pages', $printJob) }}"
                    class="flex items-end gap-3"
                >
                    @csrf

                    <div class="flex-1">
                        <label class="block text-xs font-black text-slate-500 uppercase mb-2">
                            Select Pages
                        </label>

                        <input
                            type="text"
                            name="page_selection"
                            value="{{ $printJob->page_selection !== 'all' ? $printJob->page_selection : '' }}"
                            placeholder="All or 1-3,5,8"
                            class="w-full rounded-3xl bg-slate-100 px-5 h-16 text-base font-black"
                        >
                    </div>

                    <button
                        type="submit"
                        class="rounded-3xl bg-slate-200 px-6 h-16 text-base font-black whitespace-nowrap"
                    >
                        Apply
                    </button>
                </form>

                <a
                    href="{{ route('kiosk.home') }}"
                    class="rounded-3xl bg-slate-200 px-6 h-16 text-base font-black text-center whitespace-nowrap flex items-center justify-center"
                >
                    Cancel
                </a>

                <form method="POST" action="{{ route('kiosk.confirm', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-3xl bg-slate-950 text-white px-8 h-16 text-base font-black active:scale-95 transition whitespace-nowrap"
                    >
                        Continue
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div
        id="previewFullscreen"
        class="hidden bg-slate-950"
    >
        <button
            type="button"
            onclick="closePreviewFullscreen()"
            class="absolute top-4 right-4 z-50 rounded-3xl bg-white text-slate-950 px-6 py-4 text-xl font-black shadow-2xl"
        >
            Close
        </button>

        <iframe
            src="{{ $previewUrl }}#toolbar=0&navpanes=0&scrollbar=0"
            class="w-screen h-screen"
        ></iframe>
    </div>

    <script>
        async function openPreviewFullscreen() {
            const element = document.getElementById('previewFullscreen');

            element.classList.remove('hidden');

            if (element.requestFullscreen) {
                await element.requestFullscreen();
            } else if (element.webkitRequestFullscreen) {
                await element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) {
                await element.msRequestFullscreen();
            }
        }

        async function closePreviewFullscreen() {
            const element = document.getElementById('previewFullscreen');

            if (document.fullscreenElement) {
                await document.exitFullscreen();
            }

            element.classList.add('hidden');
        }

        document.addEventListener('fullscreenchange', () => {
            if (! document.fullscreenElement) {
                document
                    .getElementById('previewFullscreen')
                    .classList
                    .add('hidden');
            }
        });
    </script>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
