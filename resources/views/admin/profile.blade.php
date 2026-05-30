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

    <div class="h-full grid grid-cols-[240px_1fr] gap-4">
        @include('admin.partials.sidebar')

        <main class="bg-white rounded-3xl p-5 shadow-xl overflow-hidden flex flex-col min-h-0">
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

            <div class="admin-scroll flex-1 min-h-0 overflow-y-auto pr-2">
                <div class="grid grid-cols-2 gap-4">
                    <section class="rounded-3xl bg-slate-100 p-5">
                        <h3 class="text-2xl font-black text-slate-950 mb-4">
                            Company
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase mb-1">
                                    Kiosk Name
                                </div>

                                <div class="text-xl font-black text-slate-950">
                                    {{ $company?->kiosk_name ?? 'Piso Print' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase mb-1">
                                    Company Name
                                </div>

                                <div class="text-xl font-black text-slate-950">
                                    {{ $company?->name ?? 'Not set' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase mb-1">
                                    Owner
                                </div>

                                <div class="text-xl font-black text-slate-950">
                                    {{ $company?->owner ?? 'Not set' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase mb-1">
                                    Contact Number
                                </div>

                                <div class="text-xl font-black text-slate-950">
                                    {{ $company?->contact_number ?? 'Not set' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase mb-1">
                                    Email
                                </div>

                                <div class="text-xl font-black text-slate-950 break-words">
                                    {{ $company?->email ?? 'Not set' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase mb-1">
                                    Address
                                </div>

                                <div class="text-base font-bold text-slate-800 leading-snug">
                                    {{ $company?->address ?? 'Not set' }}
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-3xl bg-slate-100 p-5">
                        <h3 class="text-2xl font-black text-slate-950 mb-4">
                            Kiosk / Pricing
                        </h3>

                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div class="rounded-2xl bg-white p-4 shadow-sm">
                                <div class="text-xs font-black text-slate-500 uppercase mb-2">
                                    Black Price
                                </div>

                                <div class="text-3xl font-black text-slate-950 leading-none">
                                    ₱{{ $company?->black_price_per_page ?? 1 }}
                                </div>

                                <div class="text-sm font-bold text-slate-500 mt-1">
                                    per page
                                </div>
                            </div>

                            <div class="rounded-2xl bg-white p-4 shadow-sm">
                                <div class="text-xs font-black text-slate-500 uppercase mb-2">
                                    Colored Price
                                </div>

                                <div class="text-3xl font-black text-slate-950 leading-none">
                                    ₱{{ $company?->color_price_per_page ?? 3 }}
                                </div>

                                <div class="text-sm font-bold text-slate-500 mt-1">
                                    per page
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white p-4 shadow-sm mb-5">
                            <div class="text-xs font-black text-slate-500 uppercase mb-2">
                                Custom Pricing
                            </div>

                            <div class="text-xl font-black text-slate-950">
                                {{ $company?->allow_custom_pricing ? 'Enabled' : 'Disabled' }}
                            </div>
                        </div>

                        <h3 class="text-2xl font-black text-slate-950 mb-4">
                            Organization
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase mb-1">
                                    School Name
                                </div>

                                <div class="text-xl font-black text-slate-950">
                                    {{ $organization?->school_name ?? 'Not set' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase mb-1">
                                    Serial Number
                                </div>

                                <div class="text-xl font-black text-slate-950">
                                    {{ $organization?->unit_serial_number ?? 'Not set' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase mb-1">
                                    Contact Person
                                </div>

                                <div class="text-xl font-black text-slate-950">
                                    {{ $organization?->contact_person ?? 'Not set' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase mb-1">
                                    Contact Number
                                </div>

                                <div class="text-xl font-black text-slate-950">
                                    {{ $organization?->contact_number ?? 'Not set' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-xs font-black text-slate-500 uppercase mb-1">
                                    Address
                                </div>

                                <div class="text-base font-bold text-slate-800 leading-snug">
                                    {{ $organization?->address ?? 'Not set' }}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</x-kiosk-layout>
