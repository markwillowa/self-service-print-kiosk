<x-kiosk-layout title="{{ $globalKioskName ?? 'Piso Print' }}">
    <div class="h-full grid grid-cols-[1.1fr_0.9fr] gap-3 items-center">
        <div class="min-w-0 pr-1">
            <div class="mb-2">
                <div class="w-14 h-14 rounded-2xl bg-slate-950 text-white flex items-center justify-center shadow-xl">
                    <x-heroicon-o-printer class="w-8 h-8" />
                </div>
            </div>

            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-100 border border-emerald-200 px-3 py-1.5 mb-3">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>

                <span class="text-xs font-black text-emerald-900">
                    Wireless Transfer Ready
                </span>
            </div>

            <h2 class="text-[2.4rem] font-black leading-[0.95] mb-3 text-slate-950 tracking-tight">
                Print your documents instantly
            </h2>

            <p class="text-sm text-slate-600 leading-snug mb-3 max-w-md font-bold">
                Transfer files from your phone using the local Wi-Fi,
                then preview and print directly on this kiosk.
            </p>

            <div class="grid grid-cols-2 gap-2 max-w-md">
                <div class="rounded-2xl bg-white/80 border border-white p-3 shadow-lg">
                    <div class="flex items-center gap-2 mb-1">
                        <x-heroicon-o-wifi class="w-5 h-5 text-slate-900" />

                        <div class="text-[10px] font-black text-slate-500 uppercase">
                            Wi-Fi
                        </div>
                    </div>

                    <div class="text-xl font-black text-slate-950">
                        {{ $globalKioskName ?? 'Piso Print' }}
                    </div>
                </div>

                <div class="rounded-2xl bg-white/80 border border-white p-3 shadow-lg">
                    <div class="flex items-center gap-2 mb-1">
                        <x-heroicon-o-banknotes class="w-5 h-5 text-slate-900" />

                        <div class="text-[10px] font-black text-slate-500 uppercase">
                            Price
                        </div>
                    </div>

                    <div class="text-xl font-black text-slate-950">
                        ₱1/page
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white/90 rounded-2xl p-3 shadow-xl border border-white">
            <div class="space-y-2 mb-3">
                @foreach ([
                    [
                        'icon' => 'wifi',
                        'label' => 'Connect to local Wi-Fi',
                    ],
                    [
                        'icon' => 'arrow-up-tray',
                        'label' => 'Transfer your file',
                    ],
                    [
                        'icon' => 'magnifying-glass',
                        'label' => 'Select and preview',
                    ],
                    [
                        'icon' => 'banknotes',
                        'label' => 'Insert coins and print',
                    ],
                ] as $step => $item)
                    <div class="flex items-center gap-2 rounded-xl bg-slate-50 p-2 border border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-slate-950 text-white flex items-center justify-center shadow shrink-0">
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

                        <div>
                            <div class="text-[9px] font-black text-slate-400 uppercase leading-none mb-0.5">
                                Step {{ $step + 1 }}
                            </div>

                            <div class="text-sm font-black text-slate-800 leading-tight">
                                {{ $item['label'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="rounded-xl bg-emerald-50 p-2 border border-emerald-200 mb-3">
                <div class="flex items-center gap-2 mb-1">
                    <x-heroicon-o-signal class="w-4 h-4 text-emerald-900" />

                    <h3 class="text-sm font-black text-emerald-900">
                        Wireless Transfer
                    </h3>
                </div>

                <p class="text-[11px] text-emerald-800 leading-snug font-bold">
                    Upload documents from your phone through local Wi-Fi.
                </p>
            </div>

            <a
                href="{{ route('kiosk.connect') }}"
                class="flex items-center justify-center gap-2 w-full rounded-xl bg-slate-950 text-white text-lg font-black py-3 text-center shadow-xl active:scale-95 transition"
            >
                <x-heroicon-o-printer class="w-5 h-5" />

                Start Printing
            </a>
        </div>
    </div>
</x-kiosk-layout>
