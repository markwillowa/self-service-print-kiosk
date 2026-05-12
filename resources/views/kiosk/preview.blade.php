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

                <div class="rounded-3xl bg-white px-6 py-4 shadow-xl text-center">
                    <div class="text-xs font-black text-slate-500 uppercase">
                        Pages
                    </div>

                    <div class="text-3xl font-black">
                        {{ $printJob->selected_pages_count }}
                    </div>
                </div>

                <div class="rounded-3xl bg-white px-6 py-4 shadow-xl text-center">
                    <div class="text-xs font-black text-slate-500 uppercase">
                        Total
                    </div>

                    <div class="text-3xl font-black">
                        ₱{{ $printJob->total_amount }}
                    </div>
                </div>

                <a
                    href="{{ route('kiosk.upload') }}"
                    class="rounded-3xl bg-slate-200 px-6 h-16 flex items-center justify-center text-base font-black"
                >
                    Back
                </a>

                <button
                    type="button"
                    onclick="openEditModal()"
                    class="rounded-3xl bg-slate-200 px-6 h-16 text-base font-black"
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
                        class="rounded-3xl bg-slate-950 text-white px-8 h-16 text-base font-black"
                    >
                        Continue
                    </button>
                </form>

            </div>

        </div>

        <div class="flex-1 min-h-0 rounded-[2rem] bg-white overflow-hidden shadow-2xl">

            <iframe
                src="{{ $previewUrl }}#toolbar=0&navpanes=0&scrollbar=0"
                class="w-full h-full"
            ></iframe>

        </div>

    </div>

    <div
        id="editModal"
        class="hidden fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-8"
    >

        <div class="w-full max-w-2xl rounded-[2rem] bg-white p-8 shadow-2xl">

            <div class="flex items-center justify-between mb-8">

                <h3 class="text-3xl font-black">
                    Edit Print Settings
                </h3>

                <button
                    onclick="closeEditModal()"
                    class="text-2xl font-black"
                >
                    ✕
                </button>

            </div>

            <form
                method="POST"
                action="{{ route('kiosk.update-settings', $printJob) }}"
                class="space-y-6"
            >
                @csrf

                <div>
                    <label class="block text-sm font-black mb-3">
                        Print Mode
                    </label>

                    <select
                        name="print_mode"
                        class="w-full rounded-3xl bg-slate-100 px-5 h-16 font-black"
                    >
                        <option value="black">
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
                    <label class="block text-sm font-black mb-3">
                        Page Selection
                    </label>

                    <input
                        type="text"
                        name="page_selection"
                        value="{{ $printJob->page_selection !== 'all' ? $printJob->page_selection : '' }}"
                        placeholder="All or 1-3,5"
                        class="w-full rounded-3xl bg-slate-100 px-5 h-16 font-black"
                    >
                </div>

                <div>
                    <label class="block text-sm font-black mb-3">
                        Orientation
                    </label>

                    <select
                        name="orientation"
                        class="w-full rounded-3xl bg-slate-100 px-5 h-16 font-black"
                    >
                        <option value="portrait">
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
                    <label class="block text-sm font-black mb-3">
                        Paper Size
                    </label>

                    <select
                        name="paper_size"
                        class="w-full rounded-3xl bg-slate-100 px-5 h-16 font-black"
                    >
                        <option value="short">
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
                    type="submit"
                    class="w-full rounded-3xl bg-slate-950 text-white h-16 text-lg font-black"
                >
                    Apply Settings
                </button>

            </form>

        </div>

    </div>

    <script>
        function openEditModal() {
            document
                .getElementById('editModal')
                .classList
                .remove('hidden');
        }

        function closeEditModal() {
            document
                .getElementById('editModal')
                .classList
                .add('hidden');
        }
    </script>

</x-kiosk-layout>
