<x-kiosk-layout title="Piso Print">
    <div class="h-full grid grid-cols-[1.1fr_0.9fr] gap-10 items-center">
        <div class="min-w-0 pr-4">
            <div class="mb-6">
                <div class="w-24 h-24 rounded-[2rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl">
                    <x-heroicon-o-printer class="w-14 h-14" />
                </div>
            </div>

            <div class="inline-flex items-center gap-3 rounded-full bg-emerald-100 border border-emerald-200 px-5 py-3 mb-7">
                <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>

                <span class="text-base font-black text-emerald-900">
                    Wireless File Transfer Ready
                </span>
            </div>

            <h2 class="text-7xl font-black leading-[0.95] mb-7 text-slate-950 tracking-tight">
                Print your documents instantly
            </h2>

            <p class="text-2xl text-slate-600 leading-relaxed mb-8 max-w-3xl">
                Transfer files from your phone using PisoPrint Wi-Fi,
                then preview and print directly on this kiosk.
            </p>

            <div class="grid grid-cols-2 gap-4 max-w-3xl">
                <div class="rounded-[2rem] bg-white/80 border border-white p-6 shadow-xl">
                    <div class="flex items-center gap-4 mb-3">
                        <x-heroicon-o-wifi class="w-9 h-9 text-slate-900" />

                        <div class="text-sm font-black text-slate-500 uppercase">
                            Wi-Fi Network
                        </div>
                    </div>

                    <div class="text-4xl font-black text-slate-950">
                        PisoPrint
                    </div>
                </div>

                <div class="rounded-[2rem] bg-white/80 border border-white p-6 shadow-xl">
                    <div class="flex items-center gap-4 mb-3">
                        <x-heroicon-o-banknotes class="w-9 h-9 text-slate-900" />

                        <div class="text-sm font-black text-slate-500 uppercase">
                            Starting Price
                        </div>
                    </div>

                    <div class="text-4xl font-black text-slate-950">
                        ₱1/page
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/90 rounded-[3rem] p-8 shadow-2xl border border-white">
            <div class="space-y-5 mb-8">
                @foreach ([
                    [
                        'icon' => 'wifi',
                        'label' => 'Connect to PisoPrint Wi-Fi',
                    ],
                    [
                        'icon' => 'arrow-up-tray',
                        'label' => 'Transfer your file',
                    ],
                    [
                        'icon' => 'magnifying-glass',
                        'label' => 'Select and preview your file',
                    ],
                    [
                        'icon' => 'banknotes',
                        'label' => 'Insert coins and print',
                    ],
                ] as $step => $item)
                    <div class="flex items-center gap-5 rounded-[2rem] bg-slate-50 p-5 border border-slate-100">
                        <div class="w-16 h-16 rounded-2xl bg-slate-950 text-white flex items-center justify-center shadow-lg shrink-0">
                            @switch($item['icon'])
                                @case('wifi')
                                    <x-heroicon-o-wifi class="w-8 h-8" />
                                    @break

                                @case('arrow-up-tray')
                                    <x-heroicon-o-arrow-up-tray class="w-8 h-8" />
                                    @break

                                @case('magnifying-glass')
                                    <x-heroicon-o-magnifying-glass class="w-8 h-8" />
                                    @break

                                @case('banknotes')
                                    <x-heroicon-o-banknotes class="w-8 h-8" />
                                    @break
                            @endswitch
                        </div>

                        <div>
                            <div class="text-xs font-black text-slate-400 uppercase mb-1">
                                Step {{ $step + 1 }}
                            </div>

                            <div class="text-2xl font-black text-slate-800 leading-tight">
                                {{ $item['label'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="rounded-[2rem] bg-emerald-50 p-6 border border-emerald-200 mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <x-heroicon-o-signal class="w-7 h-7 text-emerald-900" />

                    <h3 class="text-xl font-black text-emerald-900">
                        Wireless Transfer
                    </h3>
                </div>

                <p class="text-base text-emerald-800 leading-relaxed">
                    Upload documents directly from your phone through
                    the PisoPrint local Wi-Fi network.
                </p>
            </div>

            <a
                href="{{ route('kiosk.connect') }}"
                class="flex items-center justify-center gap-4 w-full rounded-[2rem] bg-slate-950 text-white text-3xl font-black py-7 text-center shadow-2xl active:scale-95 transition"
            >
                <x-heroicon-o-printer class="w-9 h-9" />

                Start Printing
            </a>
        </div>
    </div>
</x-kiosk-layout>
