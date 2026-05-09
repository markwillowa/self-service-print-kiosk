<x-kiosk-layout title="Piso Print">
    <div class="h-full grid grid-cols-2 gap-8 items-center">
        <div>
            <div class="text-7xl mb-6">🖨️</div>

            <h2 class="text-5xl font-black leading-tight mb-4 text-slate-950">
                Print your documents fast
            </h2>

            <p class="text-xl text-slate-600">
                Upload a file, insert coins, and print instantly.
            </p>
        </div>

        <div class="bg-white/80 rounded-[2rem] p-8 shadow-xl border border-white">
            <div class="space-y-4 mb-8 text-left">
                <div class="flex items-center gap-4">
                    <span class="w-10 h-10 rounded-full bg-slate-950 text-white flex items-center justify-center font-bold">1</span>
                    <span class="text-xl font-bold">Upload file</span>
                </div>

                <div class="flex items-center gap-4">
                    <span class="w-10 h-10 rounded-full bg-slate-950 text-white flex items-center justify-center font-bold">2</span>
                    <span class="text-xl font-bold">Insert coins</span>
                </div>

                <div class="flex items-center gap-4">
                    <span class="w-10 h-10 rounded-full bg-slate-950 text-white flex items-center justify-center font-bold">3</span>
                    <span class="text-xl font-bold">Print document(s)</span>
                </div>
            </div>

            <a
                href="{{ route('kiosk.upload') }}"
                class="block w-full rounded-3xl bg-slate-950 text-white text-3xl font-black py-6 text-center shadow-xl active:scale-95 transition"
            >
                Start Printing
            </a>
        </div>
    </div>
</x-kiosk-layout>
