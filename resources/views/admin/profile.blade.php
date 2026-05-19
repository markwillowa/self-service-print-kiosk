<x-kiosk-layout title="Profile">
    <div class="h-full grid grid-cols-[180px_1fr] gap-3">
        @include('admin.partials.sidebar')

        <main class="bg-white rounded-2xl p-4 shadow-sm overflow-y-auto">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <x-heroicon-o-building-office-2 class="w-7 h-7" />
                </div>

                <div>
                    <h2 class="text-3xl font-black text-slate-950 leading-none">
                        Profile
                    </h2>

                    <p class="text-sm text-slate-500 font-bold">
                        Company and kiosk information
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <section class="rounded-2xl bg-slate-100 p-4">
                    <h3 class="text-xl font-black text-slate-950 mb-3">
                        Company
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <div class="text-xs font-black text-slate-500 uppercase">
                                Name
                            </div>

                            <div class="text-lg font-black text-slate-950">
                                {{ $company?->name ?? 'Not set' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-black text-slate-500 uppercase">
                                Owner
                            </div>

                            <div class="text-lg font-black text-slate-950">
                                {{ $company?->owner ?? 'Not set' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-black text-slate-500 uppercase">
                                Contact Number
                            </div>

                            <div class="text-lg font-black text-slate-950">
                                {{ $company?->contact_number ?? 'Not set' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-black text-slate-500 uppercase">
                                Email
                            </div>

                            <div class="text-lg font-black text-slate-950 break-words">
                                {{ $company?->email ?? 'Not set' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-black text-slate-500 uppercase">
                                Address
                            </div>

                            <div class="text-sm font-bold text-slate-800">
                                {{ $company?->address ?? 'Not set' }}
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl bg-slate-100 p-4">
                    <h3 class="text-xl font-black text-slate-950 mb-3">
                        Kiosk / Organization
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <div class="text-xs font-black text-slate-500 uppercase">
                                School Name
                            </div>

                            <div class="text-lg font-black text-slate-950">
                                {{ $organization?->school_name ?? 'Not set' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-black text-slate-500 uppercase">
                                Serial Number
                            </div>

                            <div class="text-lg font-black text-slate-950">
                                {{ $organization?->unit_serial_number ?? 'Not set' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-black text-slate-500 uppercase">
                                Contact Person
                            </div>

                            <div class="text-lg font-black text-slate-950">
                                {{ $organization?->contact_person ?? 'Not set' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-black text-slate-500 uppercase">
                                Contact Number
                            </div>

                            <div class="text-lg font-black text-slate-950">
                                {{ $organization?->contact_number ?? 'Not set' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-black text-slate-500 uppercase">
                                Address
                            </div>

                            <div class="text-sm font-bold text-slate-800">
                                {{ $organization?->address ?? 'Not set' }}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
</x-kiosk-layout>
