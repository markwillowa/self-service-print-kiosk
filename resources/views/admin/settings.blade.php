<x-kiosk-layout title="Settings">
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
                    <x-heroicon-o-cog-6-tooth class="w-9 h-9" />
                </div>

                <div>
                    <h2 class="text-4xl font-black text-slate-950 leading-none mb-2">
                        Settings
                    </h2>

                    <p class="text-base text-slate-500 font-bold">
                        Kiosk configuration and pricing
                    </p>
                </div>
            </div>

            <section class="rounded-3xl bg-slate-100 p-5 mb-4">
                <h3 class="text-2xl font-black text-slate-950 mb-4">
                    Kiosk & Pricing
                </h3>

                <div class="grid grid-cols-3 gap-4">
                    <div class="rounded-2xl bg-white p-4 shadow-sm">
                        <div class="text-xs font-black text-slate-500 uppercase mb-2">
                            Kiosk Name
                        </div>

                        <div class="text-2xl font-black text-slate-950 break-words">
                            {{ $globalCompany?->kiosk_name ?? 'Piso Print' }}
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white p-4 shadow-sm">
                        <div class="text-xs font-black text-slate-500 uppercase mb-2">
                            Black Price
                        </div>

                        <div class="text-4xl font-black text-slate-950 leading-none">
                            ₱{{ $globalCompany?->black_price_per_page ?? 1 }}
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
                            ₱{{ $globalCompany?->color_price_per_page ?? 3 }}
                        </div>

                        <div class="text-sm font-bold text-slate-500 mt-2">
                            per page
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl bg-slate-100 p-5 mb-4">
                <h3 class="text-2xl font-black text-slate-950 mb-4">
                    Printer
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-white p-4 shadow-sm">
                        <div class="text-xs font-black text-slate-500 uppercase mb-2">
                            Printer Mode
                        </div>

                        <div class="text-2xl font-black text-slate-950">
                            {{ config('services.printer.mode') }}
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white p-4 shadow-sm">
                        <div class="text-xs font-black text-slate-500 uppercase mb-2">
                            Printer Name
                        </div>

                        <div class="text-2xl font-black text-slate-950 break-words">
                            {{ config('services.printer.name') ?: 'Default Printer' }}
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl bg-slate-100 p-5 mb-4">
                <h3 class="text-2xl font-black text-slate-950 mb-4">
                    Network & Queue
                </h3>

                <div class="grid grid-cols-3 gap-4">
                    <div class="rounded-2xl bg-white p-4 shadow-sm">
                        <div class="text-xs font-black text-slate-500 uppercase mb-2">
                            Wi-Fi Name
                        </div>

                        <div class="text-2xl font-black text-slate-950 break-words">
                            {{ $globalKioskName ?? 'Piso Print' }}
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white p-4 shadow-sm">
                        <div class="text-xs font-black text-slate-500 uppercase mb-2">
                            Wi-Fi Password
                        </div>

                        <div class="text-2xl font-black text-slate-950">
                            12345678
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white p-4 shadow-sm">
                        <div class="text-xs font-black text-slate-500 uppercase mb-2">
                            Queue Driver
                        </div>

                        <div class="text-2xl font-black text-slate-950">
                            {{ config('queue.default') }}
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl bg-slate-950 text-white p-5 mb-10">
                <h3 class="text-2xl font-black mb-4">
                    System Information
                </h3>

                <div class="grid grid-cols-2 gap-4 text-base font-bold">
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
            </section>
        </main>
    </div>
</x-kiosk-layout>
