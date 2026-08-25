<x-kiosk-layout title="{{ __('Document Preview') }}">
    @php
        $currentPrintMode = $printJob->print_mode ?: 'black';
        $currentOrientation = $printJob->orientation ?: 'portrait';
        $currentPaperSize = $printJob->paper_size ?: 'short';
        $currentMargin = $printJob->margin ?: 'normal';
        $currentPageSelection = $printJob->page_selection ?: 'all';

        $marginLabel = match($currentMargin) {
            'narrow' => __('Narrow (0.125")'),
            'wide' => __('Wide (0.50")'),
            'none', 'no_margin' => __('No Margin (0")'),
            'fit', 'fit_to_screen' => __('Fit to Screen'),
            default => __('Normal ⭐ (0.25")'),
        };

        $paperLabel = match($currentPaperSize) {
            'long' => __('Long (8.5 x 13 in)'),
            'a4' => __('A4 (8.27 x 11.69 in)'),
            default => __('Short (8.5 x 11 in)'),
        };
    @endphp

    <div class="h-full flex flex-col min-h-0 gap-3 py-2">
        <div class="flex items-center justify-between shrink-0 gap-3">
            <div class="min-w-0">
                <h2 class="text-4xl font-black text-slate-950 leading-none mb-1">
                    {{ __('Document Preview') }}
                </h2>

                <p class="text-base text-slate-600 truncate max-w-[480px] font-bold">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @foreach ([
                    [__('Pages'), $printJob->selected_pages_count ?: $printJob->pages],
                    [__('Copies'), $printJob->copies ?: 1],
                    [__('Total Amount'), '₱' . $printJob->total_amount],
                ] as [$label, $value])
                    <div class="rounded-2xl bg-white px-4 py-3 shadow-lg text-center min-w-[88px]">
                        <div class="text-[10px] font-black text-slate-500 uppercase leading-none mb-1">
                            {{ $label }}
                        </div>

                        <div class="text-xl font-black text-slate-950 leading-none">
                            {{ $value }}
                        </div>
                    </div>
                @endforeach

                <form method="POST" action="{{ route('kiosk.preview.back', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-2xl bg-slate-200 px-4 h-14 flex items-center justify-center text-base font-black text-slate-900 active:scale-95 transition"
                    >
                        {{ __('Back') }}
                    </button>
                </form>

                <button
                    type="button"
                    onclick="openCancelModal()"
                    class="rounded-2xl bg-red-100 text-red-700 px-4 h-14 text-base font-black active:scale-95 transition"
                >
                    {{ __('Cancel') }}
                </button>

                <button
                    type="button"
                    onclick="openEditModal()"
                    class="rounded-2xl bg-slate-200 px-4 h-14 text-base font-black text-slate-900 active:scale-95 transition"
                >
                    Edit
                </button>

                <form method="POST" action="{{ route('kiosk.confirm', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-2xl bg-slate-950 text-white px-5 h-14 text-base font-black shadow-lg active:scale-95 transition"
                    >
                        {{ __('Continue') }}
                    </button>
                </form>
            </div>
        </div>

        <div
            class="rounded-2xl bg-white/90 border border-white py-3 px-2 shadow-lg flex items-center divide-x divide-slate-200 shrink-0 w-full"
            style="display: flex; flex-direction: row; align-items: center; width: 100%;"
        >
            @foreach ([
                [__('Print Mode'), $currentPrintMode === 'color' ? __('Color') : __('Black & White')],
                [__('Paper Size'), $paperLabel],
                [__('Margins'), $marginLabel],
                [__('Orientation'), $currentOrientation === 'landscape' ? __('Landscape') : __('Portrait')],
                [__('Pages to Print'), $currentPageSelection === 'all' ? __('All Pages') : $currentPageSelection],
                [__('Charged Pages'), ($printJob->selected_pages_count ?: $printJob->pages) * ($printJob->copies ?: 1)],
            ] as [$label, $value])
                <div
                    class="flex-1 min-w-0 px-2 text-center"
                    style="flex: 1 1 0%; min-width: 0;"
                >
                    <div class="text-[11px] font-black text-slate-500 uppercase leading-none mb-1 truncate">
                        {{ $label }}
                    </div>

                    <div class="text-base font-black text-slate-950 truncate" title="{{ $value }}">
                        {{ $value }}
                    </div>
                </div>
            @endforeach
        </div>

        <div id="previewContainer" class="flex-1 min-h-0 rounded-3xl bg-slate-900/10 p-3 overflow-hidden shadow-xl border border-white relative flex items-center justify-center">
            <button
                type="button"
                id="fitScreenBtn"
                onclick="toggleFitToScreen()"
                title="{{ __('Fit to Page') }}"
                class="absolute top-4 right-4 z-10 bg-slate-900/80 hover:bg-slate-950 text-white px-3.5 py-2 rounded-2xl text-xs font-black shadow-lg backdrop-blur flex items-center gap-1.5 transition active:scale-95 cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
                <span id="fitScreenText">{{ __('Fit to Page') }}</span>
            </button>

            <div class="{{ $currentOrientation === 'landscape' ? 'w-full max-w-[1000px] h-full max-h-full aspect-[1.414/1]' : 'w-full h-full' }} bg-white rounded-2xl shadow-xl overflow-hidden relative">
                <iframe
                    id="pdfPreviewIframe"
                    src="{{ $previewUrl }}?v={{ $printJob->updated_at?->timestamp ?? time() }}#toolbar=0&navpanes=0&scrollbar=0&view={{ $currentOrientation === 'landscape' ? 'FitH' : 'Fit' }}"
                    class="w-full h-full border-0 bg-white"
                ></iframe>
            </div>
        </div>
    </div>

    <div
        id="editModal"
        class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center p-4"
    >
        <div class="w-full max-w-[1100px] max-h-[650px] overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-3xl font-black text-slate-950 leading-none mb-2">
                        {{ __('Print Settings') }}
                    </h3>

                    <p class="text-sm text-slate-500 font-bold">
                        {{ __('Customize your print options') }}
                    </p>
                </div>

                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="w-12 h-12 rounded-2xl bg-slate-100 text-2xl font-black text-slate-900 active:scale-95 transition"
                >
                    ✕
                </button>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-2xl bg-red-100 text-red-700 p-4 text-base font-black">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('kiosk.update-settings', $printJob) }}"
                class="grid grid-cols-[1fr_320px] gap-5"
            >
                @csrf

                <div class="grid grid-cols-2 gap-4 content-start">
                    <div>
                        <label class="block text-sm font-black mb-2 text-slate-700">
                            {{ __('Print Mode') }}
                        </label>

                        <select
                            name="print_mode"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                        >
                            <option value="black" @selected(old('print_mode', $currentPrintMode) === 'black')>
                                {{ __('Black & White') }}
                            </option>

                            <option value="color" @selected(old('print_mode', $currentPrintMode) === 'color')>
                                {{ __('Color') }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-black mb-2 text-slate-700">
                            {{ __('Paper Size') }}
                        </label>

                        <select
                            name="paper_size"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                        >
                            <option value="short" @selected(old('paper_size', $currentPaperSize) === 'short')>
                                {{ __('Short (8.5 x 11 in)') }}
                            </option>

                            <option value="long" @selected(old('paper_size', $currentPaperSize) === 'long')>
                                {{ __('Long (8.5 x 13 in)') }}
                            </option>

                            <option value="a4" @selected(old('paper_size', $currentPaperSize) === 'a4')>
                                {{ __('A4 (8.27 x 11.69 in)') }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-black mb-2 text-slate-700">
                            {{ __('Orientation') }}
                        </label>

                        <select
                            name="orientation"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                        >
                            <option value="portrait" @selected(old('orientation', $currentOrientation) === 'portrait')>
                                {{ __('Portrait') }}
                            </option>

                            <option value="landscape" @selected(old('orientation', $currentOrientation) === 'landscape')>
                                {{ __('Landscape') }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-black mb-2 text-slate-700">
                            {{ __('Margins') }}
                        </label>

                        <select
                            name="margin"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                        >
                            <option value="normal" @selected(old('margin', $currentMargin) === 'normal')>
                                {!! __('Normal ⭐ (0.25" / 6.35 mm)') !!}
                            </option>

                            <option value="narrow" @selected(old('margin', $currentMargin) === 'narrow')>
                                {!! __('Narrow (0.125" / 3.18 mm)') !!}
                            </option>

                            <option value="wide" @selected(old('margin', $currentMargin) === 'wide')>
                                {!! __('Wide (0.50" / 12.7 mm)') !!}
                            </option>

                            <option value="none" @selected(old('margin', $currentMargin) === 'none' || old('margin', $currentMargin) === 'no_margin')>
                                {!! __('No Margin (0")') !!}
                            </option>

                            <option value="fit" @selected(old('margin', $currentMargin) === 'fit' || old('margin', $currentMargin) === 'fit_to_screen')>
                                {!! __('Fit to Screen') !!}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-black mb-2 text-slate-700">
                            {{ __('Copies') }}
                        </label>

                        <input
                            id="copiesInput"
                            type="text"
                            name="copies"
                            value="{{ old('copies', $printJob->copies ?: 1) }}"
                            readonly
                            required
                            class="keyboard-input w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black cursor-pointer border-4 border-transparent"
                            autocomplete="off"
                            spellcheck="false"
                            onclick="selectKeyboardField('copiesInput', 'copies')"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black mb-2 text-slate-700">
                            {{ __('Pages to Print') }}
                        </label>

                        <input
                            id="pageSelectionInput"
                            type="text"
                            name="page_selection"
                            value="{{ old('page_selection', $currentPageSelection === 'all' ? '' : $currentPageSelection) }}"
                            placeholder="{{ __('All Pages') }}"
                            readonly
                            class="keyboard-input w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black cursor-pointer border-4 border-transparent"
                            autocomplete="off"
                            spellcheck="false"
                            onclick="selectKeyboardField('pageSelectionInput', 'pages')"
                        >

                        <p class="mt-1 text-xs font-bold text-slate-500">
                            {{ __('Custom Range (e.g. 1-3, 5)') }}
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="resetDefaultSettings()"
                        class="rounded-2xl bg-slate-200 text-slate-900 h-14 text-lg font-black active:scale-95 transition"
                    >
                        Reset
                    </button>

                    <button
                        type="submit"
                        class="rounded-2xl bg-slate-950 text-white h-14 text-lg font-black shadow-lg active:scale-95 transition"
                    >
                        {{ __('Apply Settings') }}
                    </button>
                </div>

                <div class="rounded-3xl bg-slate-100 p-4">
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $number)
                            <button
                                type="button"
                                onclick="keyboardPress('{{ $number }}')"
                                class="rounded-2xl bg-slate-950 text-white h-16 text-2xl font-black active:scale-95 transition"
                            >
                                {{ $number }}
                            </button>
                        @endforeach

                        <button
                            type="button"
                            onclick="keyboardPress('-')"
                            class="keyboard-page-key rounded-2xl bg-slate-200 text-slate-950 h-16 text-2xl font-black active:scale-95 transition"
                        >
                            -
                        </button>

                        <button
                            type="button"
                            onclick="keyboardPress('0')"
                            class="rounded-2xl bg-slate-950 text-white h-16 text-2xl font-black active:scale-95 transition"
                        >
                            0
                        </button>

                        <button
                            type="button"
                            onclick="keyboardPress(',')"
                            class="keyboard-page-key rounded-2xl bg-slate-200 text-slate-950 h-16 text-2xl font-black active:scale-95 transition"
                        >
                            ,
                        </button>

                        <button
                            type="button"
                            onclick="keyboardBackspace()"
                            class="rounded-2xl bg-red-100 text-red-700 h-16 text-base font-black active:scale-95 transition"
                        >
                            {{ __('Delete') }}
                        </button>

                        <button
                            type="button"
                            onclick="keyboardClear()"
                            class="col-span-2 rounded-2xl bg-slate-300 text-slate-950 h-16 text-base font-black active:scale-95 transition"
                        >
                            {{ __('Clear') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div
        id="cancelModal"
        class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center p-4"
    >
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl text-center">
            <div class="w-20 h-20 rounded-3xl bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-5">
                <x-heroicon-o-exclamation-triangle class="w-12 h-12" />
            </div>

            <h3 class="text-3xl font-black text-slate-950 mb-3">
                {{ __('Cancel Print Job?') }}
            </h3>

            <p class="text-base text-slate-500 font-bold mb-6">
                {{ __('This will cancel the current print session and return to the home screen.') }}
            </p>

            <div class="grid grid-cols-2 gap-3">
                <button
                    type="button"
                    onclick="closeCancelModal()"
                    class="rounded-2xl bg-slate-200 text-slate-900 px-4 py-4 text-base font-black active:scale-95 transition"
                >
                    No
                </button>

                <form method="POST" action="{{ route('kiosk.cancel', $printJob) }}">
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-red-500 text-white px-4 py-4 text-base font-black active:scale-95 transition"
                    >
                        {{ __('Cancel') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let activeKeyboardInput = null;
        let activeKeyboardMode = 'copies';

        function openEditModal() {
            const modal = document.getElementById('editModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                selectKeyboardField('copiesInput', 'copies');
            }, 50);
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function resetDefaultSettings() {
            document.querySelector('select[name="print_mode"]').value = 'black';
            document.querySelector('select[name="paper_size"]').value = 'short';
            document.querySelector('select[name="orientation"]').value = 'portrait';
            document.querySelector('select[name="margin"]').value = 'normal';

            document.getElementById('pageSelectionInput').value = '';
            document.getElementById('copiesInput').value = '1';

            selectKeyboardField('copiesInput', 'copies');
        }

        function toggleFitToScreen() {
            const container = document.getElementById('previewContainer');
            const fitText = document.getElementById('fitScreenText');
            if (! container) return;

            if (! document.fullscreenElement && ! document.webkitFullscreenElement) {
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                } else if (container.msRequestFullscreen) {
                    container.msRequestFullscreen();
                } else {
                    container.classList.toggle('fixed');
                    container.classList.toggle('inset-0');
                    container.classList.toggle('z-50');
                }
                if (fitText) fitText.textContent = 'Exit Fit';
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
                if (fitText) fitText.textContent = 'Fit to Screen';
            }
        }

        document.addEventListener('fullscreenchange', () => {
            const fitText = document.getElementById('fitScreenText');
            if (fitText) {
                fitText.textContent = document.fullscreenElement ? 'Exit Fit' : 'Fit to Screen';
            }
        });

        document.addEventListener('webkitfullscreenchange', () => {
            const fitText = document.getElementById('fitScreenText');
            if (fitText) {
                fitText.textContent = document.webkitFullscreenElement ? 'Exit Fit' : 'Fit to Screen';
            }
        });

        function openCancelModal() {
            const modal = document.getElementById('cancelModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancelModal');

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function selectKeyboardField(inputId, mode) {
            activeKeyboardInput = document.getElementById(inputId);
            activeKeyboardMode = mode;

            document.querySelectorAll('.keyboard-input').forEach((input) => {
                input.classList.remove(
                    'border-slate-950',
                    'bg-white',
                    'ring-4',
                    'ring-slate-300'
                );

                input.classList.add(
                    'border-transparent',
                    'bg-slate-100'
                );
            });

            activeKeyboardInput.classList.remove(
                'border-transparent',
                'bg-slate-100'
            );

            activeKeyboardInput.classList.add(
                'border-slate-950',
                'bg-white',
                'ring-4',
                'ring-slate-300'
            );

            const pageKeys = document.querySelectorAll('.keyboard-page-key');

            if (mode === 'copies') {
                pageKeys.forEach((button) => {
                    button.classList.add('opacity-40', 'pointer-events-none');
                });
            } else {
                pageKeys.forEach((button) => {
                    button.classList.remove('opacity-40', 'pointer-events-none');
                });
            }
        }

        function keyboardPress(value) {
            if (! activeKeyboardInput) {
                return;
            }

            if (activeKeyboardMode === 'copies' && ! /^[0-9]$/.test(value)) {
                return;
            }

            activeKeyboardInput.value += value;
            normalizeActiveInput();
        }

        function keyboardBackspace() {
            if (! activeKeyboardInput) {
                return;
            }

            activeKeyboardInput.value = activeKeyboardInput.value.slice(0, -1);
            normalizeActiveInput();
        }

        function keyboardClear() {
            if (! activeKeyboardInput) {
                return;
            }

            activeKeyboardInput.value = '';
        }

        function normalizeActiveInput() {
            if (! activeKeyboardInput) {
                return;
            }

            if (activeKeyboardMode === 'copies') {
                activeKeyboardInput.value = activeKeyboardInput.value
                    .replace(/[^0-9]/g, '')
                    .replace(/^0+/, '');

                return;
            }

            activeKeyboardInput.value = activeKeyboardInput.value
                .replace(/[–—]/g, '-')
                .replace(/\s+/g, '')
                .replace(/[^0-9,\-]/g, '')
                .replace(/,{2,}/g, ',')
                .replace(/-{2,}/g, '-')
                .replace(/^,/, '')
                .replace(/,$/, '');
        }

        document
            .querySelector('form[action="{{ route('kiosk.update-settings', $printJob) }}"]')
            .addEventListener('submit', function () {
                const copiesInput = document.getElementById('copiesInput');
                const pageSelectionInput = document.getElementById('pageSelectionInput');

                copiesInput.value = copiesInput.value
                    .replace(/[^0-9]/g, '')
                    .replace(/^0+/, '');

                if (copiesInput.value === '') {
                    copiesInput.value = '1';
                }

                pageSelectionInput.value = pageSelectionInput.value
                    .replace(/[–—]/g, '-')
                    .replace(/\s+/g, '')
                    .replace(/[^0-9,\-]/g, '')
                    .replace(/,{2,}/g, ',')
                    .replace(/-{2,}/g, '-')
                    .replace(/^,/, '')
                    .replace(/,$/, '');
            });

        @if ($errors->any())
        openEditModal();
        @endif
    </script>
</x-kiosk-layout>
