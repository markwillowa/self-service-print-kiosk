<x-kiosk-layout title="Preview Document">
    @php
        $currentPrintMode = $printJob->print_mode ?: 'black';
        $currentOrientation = $printJob->orientation ?: 'portrait';
        $currentPaperSize = $printJob->paper_size ?: 'short';
        $currentPageSelection = $printJob->page_selection ?: 'all';
    @endphp

    <div class="h-full flex flex-col min-h-0 gap-3 py-2">
        <div class="flex items-center justify-between shrink-0 gap-3">
            <div class="min-w-0">
                <h2 class="text-4xl font-black text-slate-950 leading-none mb-1">
                    Preview Document
                </h2>

                <p class="text-base text-slate-600 truncate max-w-[480px] font-bold">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @foreach ([
                    ['Pages', $printJob->selected_pages_count ?: $printJob->pages],
                    ['Copies', $printJob->copies ?: 1],
                    ['Total', '₱' . $printJob->total_amount],
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
                        Back
                    </button>
                </form>

                <button
                    type="button"
                    onclick="openCancelModal()"
                    class="rounded-2xl bg-red-100 text-red-700 px-4 h-14 text-base font-black active:scale-95 transition"
                >
                    Cancel
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
                        Continue
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-5 gap-3 shrink-0">
            @foreach ([
                ['Color', $currentPrintMode === 'color' ? 'Colored' : 'Black'],
                ['Size', $currentPaperSize === 'long' ? 'Long' : 'Short'],
                ['Orientation', $currentOrientation === 'landscape' ? 'Landscape' : 'Portrait'],
                ['Page', $currentPageSelection === 'all' ? 'All' : $currentPageSelection],
                ['Charged Pages', ($printJob->selected_pages_count ?: $printJob->pages) * ($printJob->copies ?: 1)],
            ] as [$label, $value])
                <div class="rounded-2xl bg-white/90 border border-white p-3 shadow-lg text-center">
                    <div class="text-[10px] font-black text-slate-500 uppercase leading-none mb-1">
                        {{ $label }}
                    </div>

                    <div class="text-base font-black text-slate-950 truncate">
                        {{ $value }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex-1 min-h-0 rounded-3xl bg-white overflow-hidden shadow-xl border border-white">
            <iframe
                src="{{ $previewUrl }}#toolbar=0&navpanes=0&scrollbar=0"
                class="w-full h-full border-0"
            ></iframe>
        </div>
    </div>

    <div
        id="editModal"
        class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center p-4"
    >
        <div class="w-full max-w-[860px] max-h-[620px] overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-3xl font-black text-slate-950 leading-none mb-2">
                        Edit Print Settings
                    </h3>

                    <p class="text-sm text-slate-500 font-bold">
                        Default: Black, Short, Portrait, All pages, 1 copy.
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
                class="grid grid-cols-2 gap-4"
            >
                @csrf

                <div>
                    <label class="block text-sm font-black mb-2 text-slate-700">
                        Color
                    </label>

                    <select
                        name="print_mode"
                        class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                    >
                        <option
                            value="black"
                            @selected(old('print_mode', $currentPrintMode) === 'black')
                        >
                            Black
                        </option>

                        <option
                            value="color"
                            @selected(old('print_mode', $currentPrintMode) === 'color')
                        >
                            Colored
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-black mb-2 text-slate-700">
                        Paper Size
                    </label>

                    <select
                        name="paper_size"
                        class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                    >
                        <option
                            value="short"
                            @selected(old('paper_size', $currentPaperSize) === 'short')
                        >
                            Short
                        </option>

                        <option
                            value="long"
                            @selected(old('paper_size', $currentPaperSize) === 'long')
                        >
                            Long
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-black mb-2 text-slate-700">
                        Orientation
                    </label>

                    <select
                        name="orientation"
                        class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                    >
                        <option
                            value="portrait"
                            @selected(old('orientation', $currentOrientation) === 'portrait')
                        >
                            Portrait
                        </option>

                        <option
                            value="landscape"
                            @selected(old('orientation', $currentOrientation) === 'landscape')
                        >
                            Landscape
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-black mb-2 text-slate-700">
                        Copies
                    </label>

                    <input
                        type="number"
                        name="copies"
                        value="{{ old('copies', $printJob->copies ?: 1) }}"
                        min="1"
                        max="99"
                        required
                        inputmode="numeric"
                        pattern="[0-9]*"
                        class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                        onfocus="openKeyboard()"
                        autocomplete="off"
                        spellcheck="false"
                    >
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-black mb-2 text-slate-700">
                        Page Selection
                    </label>

                    <input
                        type="text"
                        name="page_selection"
                        inputmode="numeric"
                        value="{{ old('page_selection', $currentPageSelection === 'all' ? '' : $currentPageSelection) }}"
                        placeholder="All or 1-3,5"
                        class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                        onfocus="openKeyboard()"
                        autocomplete="off"
                        spellcheck="false"
                    >

                    <p class="mt-2 text-xs font-bold text-slate-500">
                        Leave blank for all pages.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="resetDefaultSettings()"
                    class="rounded-2xl bg-slate-200 text-slate-900 h-14 text-lg font-black active:scale-95 transition"
                >
                    Reset Default
                </button>

                <button
                    type="submit"
                    class="rounded-2xl bg-slate-950 text-white h-14 text-lg font-black shadow-lg active:scale-95 transition"
                >
                    Apply Settings
                </button>
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
                Cancel Print Job?
            </h3>

            <p class="text-base text-slate-500 font-bold mb-6">
                This will cancel the current print session and return to the home screen.
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
                        Yes, Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal() {
            const modal = document.getElementById('editModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
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

            document.querySelector('input[name="page_selection"]').value = '';

            document.querySelector('input[name="copies"]').value = '1';
        }

        function openCancelModal() {
            const modal = document.getElementById(
                'cancelModal'
            );

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCancelModal() {
            const modal = document.getElementById(
                'cancelModal'
            );

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        @if ($errors->any())
        openEditModal();
        @endif
    </script>

    <script>
        function openKeyboard() {
            fetch('/open-keyboard', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            }).catch(() => {});
        }
    </script>
</x-kiosk-layout>
