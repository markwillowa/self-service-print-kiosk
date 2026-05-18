<x-kiosk-layout title="Settings">
    <div class="h-full grid grid-cols-[180px_1fr] gap-3">
        @include('admin.partials.sidebar')

        <main class="bg-white rounded-2xl p-5 shadow-sm overflow-y-auto">
            <h2 class="text-3xl font-black text-slate-950 mb-2">
                Settings
            </h2>

            <p class="text-sm text-slate-500 font-bold mb-6">
                Kiosk configuration and pricing
            </p>

            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="text-sm font-black text-slate-500 uppercase mb-1">
                        Black Price
                    </div>

                    <div class="text-3xl font-black text-slate-950">
                        ₱1/page
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-100 p-4">
                    <div class="text-sm font-black text-slate-500 uppercase mb-1">
                        Colored Price
                    </div>

                    <div class="text-3xl font-black text-slate-950">
                        ₱2/page
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-kiosk-layout>
