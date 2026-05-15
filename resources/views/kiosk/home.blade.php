<x-kiosk-layout title="Piso Print">
    <div class="h-full grid grid-cols-[1fr_290px] gap-3">
        <section class="min-w-0 rounded-2xl bg-white p-4 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-14 h-14 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                    <x-heroicon-o-printer class="w-8 h-8" />
                </div>

                <div class="min-w-0">
                    <div class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 mb-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>

                        <span class="text-xs font-black text-emerald-900">
                            Ready
                        </span>
                    </div>

                    <h2 class="text-4xl font-black leading-none text-slate-950">
                        Print instantly
                    </h2>
                </div>
            </div>

            <p class="text-base text-slate-600 font-bold leading-snug mb-4">
                Connect to PisoPrint Wi-Fi, upload your file, preview,
                insert coins, and print.
            </p>

            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-2xl bg-slate-100 p-3">
                    <div class="text-xs font-black text-slate-500 uppercase mb-1">
                        Wi-Fi
                    </div>

                    <div class="text-2xl font-black text-slate-950">
                        PisoPrint
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-100 p-3">
                    <div class="text-xs font-black text-slate-500 uppercase mb-1">
                        Price
                    </div>

                    <div class="text-2xl font-black text-slate-950">
                        ₱1/page
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-2xl bg-white p-3 shadow-sm flex flex-col">
            <div class="space-y-2 flex-1">
                @foreach ([
                    'Connect to Wi-Fi',
                    'Upload your file',
                    'Preview document',
                    'Insert coins',
                ] as $step => $label)
                    <div class="flex items-center gap-3 rounded-xl bg-slate-100 p-2">
                        <div class="w-8 h-8 rounded-xl bg-slate-950 text-white flex items-center justify-center text-sm font-black shrink-0">
                            {{ $step + 1 }}
                        </div>

                        <div class="text-sm font-black text-slate-800 leading-tight">
                            {{ $label }}
                        </div>
                    </div>
                @endforeach
            </div>

            <a
                href="{{ route('kiosk.connect') }}"
                class="mt-3 h-14 rounded-2xl bg-slate-950 text-white flex items-center justify-center gap-2 text-xl font-black active:scale-95 transition"
            >
                <x-heroicon-o-printer class="w-6 h-6" />
                Start
            </a>
        </section>
    </div>
</x-kiosk-layout>
