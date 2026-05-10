<x-kiosk-layout title="Piso Print">
    <div class="h-full grid grid-cols-2 gap-8 items-center">
        <div class="pr-4">
            <div class="text-7xl mb-4">🖨️</div>

            <h2 class="text-5xl font-black leading-tight mb-4 text-slate-950">
                Print your documents instantly
            </h2>

            <p class="text-xl text-slate-600 leading-relaxed">
                Upload files, preview documents,
                insert coins, and print in seconds.
            </p>
        </div>

        <div class="bg-white/85 rounded-[2.5rem] p-6 shadow-2xl border border-white self-start">
            <div class="space-y-3 mb-5">
                @foreach ([
                    'Upload file',
                    'Preview document',
                    'Insert coins',
                    'Print instantly',
                ] as $step => $label)
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-full bg-slate-950 text-white flex items-center justify-center text-base font-black shadow-lg shrink-0">
                            {{ $step + 1 }}
                        </div>

                        <div class="text-lg font-black text-slate-800">
                            {{ $label }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="rounded-3xl bg-blue-50 p-4 border border-blue-200 mb-5">
                <h3 class="text-sm font-black mb-1 text-blue-900">
                    Bluetooth Printing
                </h3>

                <p class="text-xs text-blue-800 leading-relaxed">
                    Connect via Bluetooth and send your file directly to the kiosk.
                </p>
            </div>

            <a
                href="{{ route('kiosk.upload') }}"
                class="block w-full rounded-3xl bg-slate-950 text-white text-xl font-black py-4 text-center shadow-xl active:scale-95 transition"
            >
                Start Printing
            </a>
        </div>
    </div>
</x-kiosk-layout>
