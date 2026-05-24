<x-kiosk-layout title="Settings">
    <div class="h-full grid grid-cols-[180px_1fr] gap-3">
        @include('admin.partials.sidebar')

        <main class="bg-white rounded-2xl p-4 shadow-sm overflow-y-auto">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <x-heroicon-o-cog-6-tooth class="w-7 h-7" />
                </div>

                <div>
                    <h2 class="text-3xl font-black text-slate-950 leading-none">
                        Settings
                    </h2>

                    <p class="text-sm text-slate-500 font-bold">
                        Kiosk configuration and pricing
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="text-sm font-black text-slate-500 uppercase mb-2">
                        Kiosk Name
                    </div>

                    <div class="text-2xl font-black text-slate-950">
                        {{ $globalCompany?->kiosk_name ?? 'Piso Print' }}
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="text-sm font-black text-slate-500 uppercase mb-2">
                        Black Price
                    </div>

                    <div class="text-3xl font-black text-slate-950">
                        ₱{{ $globalCompany?->black_price_per_page ?? 1 }}/page
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="text-sm font-black text-slate-500 uppercase mb-2">
                        Colored Price
                    </div>

                    <div class="text-3xl font-black text-slate-950">
                        ₱{{ $globalCompany?->color_price_per_page ?? 3 }}/page
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="text-sm font-black text-slate-500 uppercase mb-2">
                        Printer Mode
                    </div>

                    <div class="text-xl font-black text-slate-950">
                        {{ config('services.printer.mode') }}
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="text-sm font-black text-slate-500 uppercase mb-2">
                        Printer Name
                    </div>

                    <div class="text-xl font-black text-slate-950 break-words">
                        {{ config('services.printer.name') ?: 'Default Printer' }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="text-sm font-black text-slate-500 uppercase mb-2">
                        Wi-Fi Name
                    </div>

                    <div class="text-xl font-black text-slate-950">
                        {{ $globalKioskName ?? 'Piso Print' }}
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="text-sm font-black text-slate-500 uppercase mb-2">
                        Wi-Fi Password
                    </div>

                    <div class="text-xl font-black text-slate-950">
                        12345678
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="text-sm font-black text-slate-500 uppercase mb-2">
                        Queue Driver
                    </div>

                    <div class="text-xl font-black text-slate-950">
                        {{ config('queue.default') }}
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-slate-950 text-white p-4">
                <div class="text-sm font-black uppercase mb-2">
                    System Information
                </div>

                <div class="grid grid-cols-2 gap-2 text-sm font-bold">
                    <div>
                        App Environment:
                        <span class="text-emerald-400">
                            {{ config('app.env') }}
                        </span>
                    </div>

                    <div>
                        App Debug:
                        <span class="text-emerald-400">
                            {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>

                    <div>
                        PHP Version:
                        <span class="text-emerald-400">
                            {{ PHP_VERSION }}
                        </span>
                    </div>

                    <div>
                        Laravel Version:
                        <span class="text-emerald-400">
                            {{ app()->version() }}
                        </span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-kiosk-layout>
