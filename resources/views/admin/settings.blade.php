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

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-banknotes class="w-5 h-5 text-slate-700" />

                        <div class="text-sm font-black text-slate-500 uppercase">
                            Black Price
                        </div>
                    </div>

                    <div class="text-3xl font-black text-slate-950">
                        ₱1/page
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-banknotes class="w-5 h-5 text-slate-700" />

                        <div class="text-sm font-black text-slate-500 uppercase">
                            Colored Price
                        </div>
                    </div>

                    <div class="text-3xl font-black text-slate-950">
                        ₱2/page
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-printer class="w-5 h-5 text-slate-700" />

                        <div class="text-sm font-black text-slate-500 uppercase">
                            Printer Mode
                        </div>
                    </div>

                    <div class="text-xl font-black text-slate-950">
                        {{ config('services.printer.mode') }}
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-printer class="w-5 h-5 text-slate-700" />

                        <div class="text-sm font-black text-slate-500 uppercase">
                            Printer Name
                        </div>
                    </div>

                    <div class="text-xl font-black text-slate-950 break-words">
                        {{ config('services.printer.name') ?: 'Default Printer' }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-wifi class="w-5 h-5 text-slate-700" />

                        <div class="text-sm font-black text-slate-500 uppercase">
                            Wi-Fi Name
                        </div>
                    </div>

                    <div class="text-xl font-black text-slate-950">
                        PisoPrint
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-lock-closed class="w-5 h-5 text-slate-700" />

                        <div class="text-sm font-black text-slate-500 uppercase">
                            Wi-Fi Password
                        </div>
                    </div>

                    <div class="text-xl font-black text-slate-950">
                        12345678
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-circle-stack class="w-5 h-5 text-slate-700" />

                        <div class="text-sm font-black text-slate-500 uppercase">
                            Queue Driver
                        </div>
                    </div>

                    <div class="text-xl font-black text-slate-950">
                        {{ config('queue.default') }}
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-slate-950 text-white p-4">
                <div class="flex items-center gap-2 mb-2">
                    <x-heroicon-o-information-circle class="w-5 h-5" />

                    <div class="text-sm font-black uppercase">
                        System Information
                    </div>
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
