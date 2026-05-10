<x-kiosk-layout title="Preview Document">
    <div class="h-full flex flex-col min-h-0 gap-3">
        <div class="flex items-center justify-between shrink-0">
            <div class="min-w-0">
                <h2 class="text-2xl font-black">
                    Preview Document
                </h2>

                <p class="text-xs text-slate-600 truncate max-w-[320px]">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <div class="rounded-2xl bg-white/90 px-4 py-2 shadow text-center">
                    <div class="text-[10px] font-bold text-slate-500 uppercase">
                        Pages
                    </div>

                    <div class="text-xl font-black leading-none">
                        {{ $printJob->selected_pages_count }}
                    </div>
                </div>

                <div class="rounded-2xl bg-white/90 px-4 py-2 shadow text-center">
                    <div class="text-[10px] font-bold text-slate-500 uppercase">
                        Total
                    </div>

                    <div class="text-xl font-black leading-none">
                        ₱{{ $printJob->total_amount }}
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 min-h-0 rounded-3xl bg-white shadow-lg overflow-hidden relative">
            <button
                type="button"
                onclick="openPreviewFullscreen()"
                class="absolute top-3 right-3 z-10 rounded-2xl bg-slate-950 text-white px-4 py-2 text-xs font-black shadow-xl"
            >
                Maximize
            </button>

            <iframe
                src="{{ route('kiosk.preview-file', $printJob) }}#toolbar=0&navpanes=0&scrollbar=0"
                class="w-full h-full"
            ></iframe>
        </div>

        <div class="shrink-0 rounded-3xl bg-white/90 p-4 shadow-lg">
            <div class="grid grid-cols-[1fr_auto_auto] gap-3 items-end">
                <form
                    method="POST"
                    action="{{ route('kiosk.update-pages', $printJob) }}"
                    class="flex items-end gap-3"
                >
                    @csrf

                    <div class="flex-1">
                        <label class="block text-xs font-black text-slate-500 mb-1">
                            Select Pages
                        </label>

                        <input
                            type="text"
                            name="page_selection"
                            value="{{ $printJob->page_selection !== 'all' ? $printJob->page_selection : '' }}"
                            placeholder="All pages or 1-3,5,8"
                            class="w-full rounded-2xl bg-slate-100 px-4 py-3 text-sm font-bold"
                        >
                    </div>

                    <button
                        type="submit"
                        class="rounded-2xl bg-slate-200 px-5 py-3 text-sm font-black whitespace-nowrap"
                    >
                        Apply
                    </button>
                </form>

                <a
                    href="{{ route('kiosk.home') }}"
                    class="rounded-2xl bg-slate-200 px-5 py-3 text-sm font-black text-center"
                >
                    Cancel
                </a>

                <form method="POST" action="{{ route('kiosk.confirm', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-2xl bg-slate-950 text-white px-6 py-3 text-sm font-black active:scale-95 transition whitespace-nowrap"
                    >
                        Looks Good
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
            class="absolute top-4 right-4 z-50 rounded-2xl bg-white text-slate-950 px-6 py-4 text-xl font-black shadow-xl"
        >
            Close
        </button>

        <iframe
            src="{{ route('kiosk.preview-file', $printJob) }}#toolbar=0&navpanes=0&scrollbar=0"
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
