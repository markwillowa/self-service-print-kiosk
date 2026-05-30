<x-kiosk-layout title="Profile">
    <style>
        .admin-scroll::-webkit-scrollbar {
            width: 18px;
        }

        .admin-scroll::-webkit-scrollbar-track {
            background: rgba(148, 163, 184, 0.15);
            border-radius: 999px;
        }

        .admin-scroll::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, 0.75);
            border-radius: 999px;
        }
    </style>

    <div class="h-full min-h-0 grid grid-cols-[240px_1fr] gap-4">
        @include('admin.partials.sidebar')

        <main class="bg-white rounded-3xl p-5 shadow-xl overflow-hidden flex flex-col h-full">
            <div class="flex items-center gap-4 mb-5 shrink-0">
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

            <div class="admin-scroll h-full overflow-y-auto pr-2">
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
                    <h3 class="text-2xl font-black text-slate-950 mb-4">
                        Pricing
                    </h3>

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
                                {{ $company?->allow_custom_pricing ? 'Enabled' : 'Disabled' }}
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl bg-slate-100 p-5 mb-4">
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
            </div>
        </main>
    </div>
</x-kiosk-layout>
