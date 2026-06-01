<x-kiosk-layout title="Profile">
    <style>
        .admin-panel-scroll {
            scrollbar-width: auto;
            scrollbar-color: rgba(71, 85, 105, 0.75) rgba(148, 163, 184, 0.15);
        }

        .admin-panel-scroll::-webkit-scrollbar {
            width: 22px;
        }

        .admin-panel-scroll::-webkit-scrollbar-track {
            background: rgba(148, 163, 184, 0.15);
            border-radius: 999px;
        }

        .admin-panel-scroll::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, 0.75);
            border-radius: 999px;
            border: 4px solid transparent;
            background-clip: content-box;
        }
    </style>

    <div class="h-full grid grid-cols-[240px_1fr] gap-4">
        @include('admin.partials.sidebar')

        <main class="admin-panel-scroll bg-white rounded-3xl p-5 shadow-xl overflow-y-auto">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-16 h-16 rounded-3xl bg-slate-950 text-white flex items-center justify-center shrink-0 shadow-xl">
                    <x-heroicon-o-building-office-2 class="w-9 h-9" />
                </div>

                <div>
                    <h2 class="text-4xl font-black text-slate-950 leading-none mb-2">
                        Profile
                    </h2>

                    <p class="text-base text-slate-500 font-bold">
                        Company, kiosk, pricing, and organization information
                    </p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-2xl bg-emerald-100 text-emerald-800 p-4 text-base font-black">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-2xl bg-red-100 text-red-700 p-4 text-base font-black">
                    {{ $errors->first() }}
                </div>
            @endif

            <section class="rounded-3xl bg-slate-100 p-5 mb-4">
                <h3 class="text-2xl font-black text-slate-950 mb-4">
                    Company
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    @foreach ([
                        ['Kiosk Name', $company?->kiosk_name ?? 'Piso Print'],
                        ['Company Name', $company?->name ?? 'Not set'],
                        ['Owner', $company?->owner ?? 'Not set'],
                        ['Contact Number', $company?->contact_number ?? 'Not set'],
                        ['Email', $company?->email ?? 'Not set'],
                        ['Address', $company?->address ?? 'Not set'],
                    ] as [$label, $value])
                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                            <div class="text-xs font-black text-slate-500 uppercase mb-2">
                                {{ $label }}
                            </div>

                            <div class="text-xl font-black text-slate-950 break-words">
                                {{ $value }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-3xl bg-slate-100 p-5 mb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-black text-slate-950">
                        Pricing
                    </h3>

                    @if (($company?->kiosk_name ?? 'Piso Print') === 'Self-Service Print')
                        <span class="rounded-full bg-emerald-100 text-emerald-700 px-4 py-2 text-xs font-black">
                            Editable
                        </span>
                    @else
                        <span class="rounded-full bg-slate-200 text-slate-600 px-4 py-2 text-xs font-black">
                            Fixed Piso Print Pricing
                        </span>
                    @endif
                </div>

                @if (($company?->kiosk_name ?? 'Piso Print') === 'Self-Service Print')
                    <form
                        method="POST"
                        action="{{ route('admin.profile.pricing.update') }}"
                        class="grid grid-cols-[1fr_320px] gap-5"
                    >
                        @csrf

                        <div class="grid grid-cols-2 gap-4 content-start">
                            <div class="rounded-2xl bg-white p-4 shadow-sm">
                                <label class="block text-xs font-black text-slate-500 uppercase mb-2">
                                    Black Price
                                </label>

                                <input
                                    id="blackPriceInput"
                                    type="text"
                                    name="black_price_per_page"
                                    value="{{ old('black_price_per_page', $company?->black_price_per_page ?? 1) }}"
                                    readonly
                                    required
                                    class="price-input w-full rounded-2xl bg-slate-100 px-4 h-16 text-4xl font-black text-slate-950 border-4 border-transparent cursor-pointer"
                                    onclick="selectPriceInput('blackPriceInput')"
                                >

                                <div class="text-sm font-bold text-slate-500 mt-2">
                                    per page
                                </div>
                            </div>

                            <div class="rounded-2xl bg-white p-4 shadow-sm">
                                <label class="block text-xs font-black text-slate-500 uppercase mb-2">
                                    Colored Price
                                </label>

                                <input
                                    id="colorPriceInput"
                                    type="text"
                                    name="color_price_per_page"
                                    value="{{ old('color_price_per_page', $company?->color_price_per_page ?? 3) }}"
                                    readonly
                                    required
                                    class="price-input w-full rounded-2xl bg-slate-100 px-4 h-16 text-4xl font-black text-slate-950 border-4 border-transparent cursor-pointer"
                                    onclick="selectPriceInput('colorPriceInput')"
                                >

                                <div class="text-sm font-bold text-slate-500 mt-2">
                                    per page
                                </div>
                            </div>

                            <div class="col-span-2 rounded-2xl bg-white p-4 shadow-sm">
                                <div class="text-xs font-black text-slate-500 uppercase mb-2">
                                    Custom Pricing
                                </div>

                                <div class="text-2xl font-black text-slate-950">
                                    Enabled
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="col-span-2 rounded-2xl bg-slate-950 text-white h-16 text-xl font-black shadow-lg active:scale-95 transition"
                            >
                                Save Pricing
                            </button>
                        </div>

                        <div class="rounded-3xl bg-white p-4 shadow-sm">
                            <div class="grid grid-cols-3 gap-2">
                                @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $number)
                                    <button
                                        type="button"
                                        onclick="priceKeyboardPress('{{ $number }}')"
                                        class="rounded-2xl bg-slate-950 text-white h-16 text-2xl font-black active:scale-95 transition"
                                    >
                                        {{ $number }}
                                    </button>
                                @endforeach

                                <button
                                    type="button"
                                    onclick="priceKeyboardBackspace()"
                                    class="rounded-2xl bg-red-100 text-red-700 h-16 text-base font-black active:scale-95 transition"
                                >
                                    Delete
                                </button>

                                <button
                                    type="button"
                                    onclick="priceKeyboardPress('0')"
                                    class="rounded-2xl bg-slate-950 text-white h-16 text-2xl font-black active:scale-95 transition"
                                >
                                    0
                                </button>

                                <button
                                    type="button"
                                    onclick="priceKeyboardClear()"
                                    class="rounded-2xl bg-slate-300 text-slate-950 h-16 text-base font-black active:scale-95 transition"
                                >
                                    Clear
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="grid grid-cols-3 gap-4">
                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                            <div class="text-xs font-black text-slate-500 uppercase mb-2">
                                Black Price
                            </div>

                            <div class="text-4xl font-black text-slate-950 leading-none">
                                ₱{{ $company?->black_price_per_page ?? 1 }}
                            </div>

                            <div class="text-sm font-bold text-slate-500 mt-2">
                                per page
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                            <div class="text-xs font-black text-slate-500 uppercase mb-2">
                                Colored Price
                            </div>

                            <div class="text-4xl font-black text-slate-950 leading-none">
                                ₱{{ $company?->color_price_per_page ?? 3 }}
                            </div>

                            <div class="text-sm font-bold text-slate-500 mt-2">
                                per page
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                            <div class="text-xs font-black text-slate-500 uppercase mb-2">
                                Custom Pricing
                            </div>

                            <div class="text-2xl font-black text-slate-950">
                                Disabled
                            </div>
                        </div>
                    </div>
                @endif
            </section>

            <section class="rounded-3xl bg-slate-100 p-5 mb-10">
                <h3 class="text-2xl font-black text-slate-950 mb-4">
                    Organization
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    @foreach ([
                        ['School Name', $organization?->school_name ?? 'Not set'],
                        ['Serial Number', $organization?->unit_serial_number ?? 'Not set'],
                        ['Contact Person', $organization?->contact_person ?? 'Not set'],
                        ['Contact Number', $organization?->contact_number ?? 'Not set'],
                        ['Address', $organization?->address ?? 'Not set'],
                    ] as [$label, $value])
                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                            <div class="text-xs font-black text-slate-500 uppercase mb-2">
                                {{ $label }}
                            </div>

                            <div class="text-xl font-black text-slate-950 break-words">
                                {{ $value }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </main>
    </div>

    <script>
        let activePriceInput = null;

        function selectPriceInput(inputId) {
            activePriceInput = document.getElementById(inputId);

            document.querySelectorAll('.price-input').forEach((input) => {
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

            activePriceInput.classList.remove(
                'border-transparent',
                'bg-slate-100'
            );

            activePriceInput.classList.add(
                'border-slate-950',
                'bg-white',
                'ring-4',
                'ring-slate-300'
            );
        }

        function priceKeyboardPress(value) {
            if (! activePriceInput) {
                return;
            }

            if (activePriceInput.value.length >= 3) {
                return;
            }

            activePriceInput.value += value;
        }

        function priceKeyboardBackspace() {
            if (! activePriceInput) {
                return;
            }

            activePriceInput.value =
                activePriceInput.value.slice(0, -1);
        }

        function priceKeyboardClear() {
            if (! activePriceInput) {
                return;
            }

            activePriceInput.value = '';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const blackInput = document.getElementById('blackPriceInput');

            if (blackInput) {
                selectPriceInput('blackPriceInput');
            }
        });
    </script>
</x-kiosk-layout>
