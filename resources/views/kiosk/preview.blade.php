<x-kiosk-layout title="Preview Document">
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
                        {{ $printJob->selected_pages_count }}
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
                        Changes will update the preview and price.
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

            <form
                method="POST"
                action="{{ route('kiosk.update-settings', $printJob) }}"
                class="grid grid-cols-2 gap-6"
            >
                @csrf

                <div>
                    <label class="block text-lg font-black mb-3 text-slate-700">
                        Print Mode
                    </label>

                    <select
                        name="print_mode"
                        class="w-full rounded-[2rem] bg-slate-100 px-6 h-20 text-2xl font-black"
                    >
                        <option
                            value="black"
                            @selected($printJob->print_mode === 'black')
                        >
                            Black Only
                        </option>

                        <option
                            value="color"
                            @selected($printJob->print_mode === 'color')
                        >
                            Colored
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
                        value="{{ $printJob->page_selection !== 'all' ? $printJob->page_selection : '' }}"
                        placeholder="All or 1-3,5"
                        class="w-full rounded-[2rem] bg-slate-100 px-6 h-20 text-2xl font-black"
                    >
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
                            @selected($printJob->orientation === 'portrait')
                        >
                            Portrait
                        </option>

                        <option
                            value="landscape"
                            @selected($printJob->orientation === 'landscape')
                        >
                            Landscape
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
                            @selected($printJob->paper_size === 'short')
                        >
                            Short
                        </option>

                        <option
                            value="long"
                            @selected($printJob->paper_size === 'long')
                        >
                            Long
                        </option>
                    </select>
                </div>

                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="rounded-[2rem] bg-slate-200 text-slate-900 h-20 text-2xl font-black active:scale-95 transition"
                >
                    Cancel
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
    </script>
</x-kiosk-layout>
