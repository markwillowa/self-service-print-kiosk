<x-kiosk-layout title="Piso Print">
    <div class="h-full grid grid-cols-2 gap-6 items-center">
        <div class="pr-2">
            <div class="mb-3">
                <x-heroicon-o-printer class="w-16 h-16 text-slate-900" />
            </div>

            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-100 border border-emerald-200 px-3 py-1.5 mb-4">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>

                <span class="text-xs font-black text-emerald-900">
                    Wireless File Transfer Ready
                </span>
            </div>

            <h2 class="text-4xl font-black leading-tight mb-4 text-slate-950">
                Print your documents instantly
            </h2>

            <p class="text-lg text-slate-600 leading-relaxed mb-5">
                Transfer files from your phone using PisoPrint Wi-Fi,
                then search, preview, and print directly on the kiosk.
            </p>

            <div class="rounded-[1.75rem] bg-white/70 border border-white p-4 shadow-lg">
                <div class="flex items-center gap-3 mb-2">
                    <x-heroicon-o-wifi class="w-6 h-6 text-slate-900" />

                    <div class="text-xs font-bold text-slate-500">
                        Wi-Fi Network
                    </div>
                </div>

                <div class="text-2xl font-black text-slate-950">
                    PisoPrint
                </div>

                <div class="text-xs text-slate-500 mt-1">
                    Connect your phone to upload files wirelessly
                </div>
            </div>
        </div>

        <div class="bg-white/85 rounded-[2rem] p-5 shadow-2xl border border-white">
            <div class="space-y-3 mb-5">
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
                        'label' => 'Search and preview your file here',
                    ],
                    [
                        'icon' => 'banknotes',
                        'label' => 'Insert coins and print your documents',
                    ],
                ] as $step => $item)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-950 text-white flex items-center justify-center shadow-lg shrink-0">
                            @switch($item['icon'])
                                @case('wifi')
                                    <x-heroicon-o-wifi class="w-5 h-5" />
                                    @break

                                @case('arrow-up-tray')
                                    <x-heroicon-o-arrow-up-tray class="w-5 h-5" />
                                    @break

                                @case('magnifying-glass')
                                    <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                                    @break

                                @case('banknotes')
                                    <x-heroicon-o-banknotes class="w-5 h-5" />
                                    @break
                            @endswitch
                        </div>

                        <div class="text-base font-black text-slate-800 leading-snug">
                            {{ $item['label'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 mb-5">
                <div class="flex items-center gap-2 mb-1">
                    <x-heroicon-o-signal class="w-5 h-5 text-emerald-900" />

                    <h3 class="text-sm font-black text-emerald-900">
                        Wireless Transfer
                    </h3>
                </div>

                <p class="text-xs text-emerald-800 leading-relaxed">
                    Upload documents directly from your phone
                    through the PisoPrint local Wi-Fi network.
                </p>
            </div>

            <a
                href="{{ route('kiosk.connect') }}"
                class="flex items-center justify-center gap-2 w-full rounded-2xl bg-slate-950 text-white text-lg font-black py-4 text-center shadow-xl active:scale-95 transition"
            >
                <x-heroicon-o-printer class="w-5 h-5" />

                Start Printing
            </a>
        </div>
    </div>
</x-kiosk-layout>
