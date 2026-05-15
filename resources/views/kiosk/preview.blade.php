<x-kiosk-layout title="Preview Document">
    @php
        $currentPrintMode = $printJob->print_mode ?: 'black';
        $currentOrientation = $printJob->orientation ?: 'portrait';
        $currentPaperSize = $printJob->paper_size ?: 'short';
        $currentPageSelection = $printJob->page_selection ?: 'all';
    @endphp

    <div class="h-full flex flex-col min-h-0 gap-6">
        <div class="flex items-center justify-between shrink-0">
            <div class="min-w-0">
                <h2 class="text-6xl font-black text-slate-950 leading-none mb-2">
                    Preview Document
                </h2>

                <p class="text-xl text-slate-600 truncate max-w-[780px] font-bold">
                    {{ $printJob->original_filename }}
                </p>
            </div>

            <div class="flex items-center gap-4">
                <div class="rounded-[2rem] bg-white px-7 py-5 shadow-xl text-center min-w-[140px]">
                    <div class="text-sm font-black text-slate-500 uppercase mb-1">
                        Pages
                    </div>

                    <div class="text-4xl font-black text-slate-950 leading-none">
                        {{ $printJob->selected_pages_count ?: $printJob->pages }}
                    </div>
                </div>

                <div class="rounded-[2rem] bg-white px-7 py-5 shadow-xl text-center min-w-[150px]">
                    <div class="text-sm font-black text-slate-500 uppercase mb-1">
                        Total
                    </div>

                    <div class="text-4xl font-black text-slate-950 leading-none">
                        ₱{{ $printJob->total_amount }}
                    </div>
                </div>

                <a
                    href="{{ route('kiosk.upload') }}"
                    class="rounded-[2rem] bg-slate-200 px-7 h-20 flex items-center justify-center text-xl font-black text-slate-900 active:scale-95 transition"
                >
                    Back
                </a>

                <form
                    method="POST"
                    action="{{ route('kiosk.cancel', $printJob) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="rounded-[2rem] bg-red-100 text-red-700 px-7 h-20 text-xl font-black active:scale-95 transition"
                    >
                        Cancel
                    </button>
                </form>

                <button
                    type="button"
                    onclick="openEditModal()"
                    class="rounded-[2rem] bg-slate-200 px-7 h-20 text-xl font-black text-slate-900 active:scale-95 transition"
                >
                    Edit
                </button>

                <form
                    method="POST"
                    action="{{ route('kiosk.confirm', $printJob) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="rounded-[2rem] bg-slate-950 text-white px-9 h-20 text-xl font-black shadow-xl active:scale-95 transition"
                    >
                        Continue
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 shrink-0">
            <div class="rounded-[2rem] bg-white/90 border border-white p-5 shadow-xl text-center">
                <div class="text-sm font-black text-slate-500 uppercase mb-1">
                    Color
                </div>

                <div class="text-2xl font-black text-slate-950">
                    {{ $currentPrintMode === 'color' ? 'Colored' : 'Black' }}
                </div>
            </div>

            <div class="rounded-[2rem] bg-white/90 border border-white p-5 shadow-xl text-center">
                <div class="text-sm font-black text-slate-500 uppercase mb-1">
                    Size
                </div>

                <div class="text-2xl font-black text-slate-950">
                    {{ $currentPaperSize === 'long' ? 'Long' : 'Short' }}
                </div>
            </div>

            <div class="rounded-[2rem] bg-white/90 border border-white p-5 shadow-xl text-center">
                <div class="text-sm font-black text-slate-500 uppercase mb-1">
                    Orientation
                </div>

                <div class="text-2xl font-black text-slate-950">
                    {{ $currentOrientation === 'landscape' ? 'Landscape' : 'Portrait' }}
                </div>
            </div>

            <div class="rounded-[2rem] bg-white/90 border border-white p-5 shadow-xl text-center">
                <div class="text-sm font-black text-slate-500 uppercase mb-1">
                    Page
                </div>

                <div class="text-2xl font-black text-slate-950 truncate">
                    {{ $currentPageSelection === 'all' ? 'All' : $currentPageSelection }}
                </div>
            </div>
        </div>

        <div class="flex-1 min-h-0 rounded-[3rem] bg-white overflow-hidden shadow-2xl border border-white">
            <iframe
                src="{{ $previewUrl }}#toolbar=0&navpanes=0&scrollbar=0"
                class="w-full h-full border-0"
            ></iframe>
        </div>
    </div>

    <div
        id="editModal"
        class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center p-8"
    >
        <div class="w-full max-w-5xl rounded-[3rem] bg-white p-10 shadow-2xl">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-5xl font-black text-slate-950 leading-none mb-2">
                        Edit Print Settings
                    </h3>

                    <p class="text-xl text-slate-500 font-bold">
                        Default: Black, Short, Portrait, All pages.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="w-16 h-16 rounded-2xl bg-slate-100 text-3xl font-black text-slate-900 active:scale-95 transition"
                >
                    ✕
                </button>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-[2rem] bg-red-100 text-red-700 p-5 text-xl font-black">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('kiosk.update-settings', $printJob) }}"
                class="grid grid-cols-2 gap-6"
            >
                @csrf

                <div>
                    <label class="block text-lg font-black mb-3 text-slate-700">
                        Color
                    </label>

                    <select
                        name="print_mode"
                        class="w-full rounded-[2rem] bg-slate-100 px-6 h-20 text-2xl font-black"
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
                    <label class="block text-lg font-black mb-3 text-slate-700">
                        Paper Size
                    </label>

                    <select
                        name="paper_size"
                        class="w-full rounded-[2rem] bg-slate-100 px-6 h-20 text-2xl font-black"
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
                    <label class="block text-lg font-black mb-3 text-slate-700">
                        Orientation
                    </label>

                    <select
                        name="orientation"
                        class="w-full rounded-[2rem] bg-slate-100 px-6 h-20 text-2xl font-black"
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
                    <label class="block text-lg font-black mb-3 text-slate-700">
                        Page Selection
                    </label>

                    <input
                        type="text"
                        name="page_selection"
                        value="{{ old('page_selection', $currentPageSelection === 'all' ? '' : $currentPageSelection) }}"
                        placeholder="All or 1-3,5"
                        class="w-full rounded-[2rem] bg-slate-100 px-6 h-20 text-2xl font-black"
                    >

                    <p class="mt-2 text-sm font-bold text-slate-500">
                        Leave blank for all pages.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="resetDefaultSettings()"
                    class="rounded-[2rem] bg-slate-200 text-slate-900 h-20 text-2xl font-black active:scale-95 transition"
                >
                    Reset Default
                </button>

                <button
                    type="submit"
                    class="rounded-[2rem] bg-slate-950 text-white h-20 text-2xl font-black shadow-xl active:scale-95 transition"
                >
                    Apply Settings
                </button>
            </form>
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
        }

        @if ($errors->any())
        openEditModal();
        @endif
    </script>
</x-kiosk-layout>
