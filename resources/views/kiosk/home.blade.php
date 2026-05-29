<x-kiosk-layout title="{{ $globalKioskName ?? 'Piso Print' }}">
    <div class="h-full grid grid-cols-[1fr_1fr] gap-3 items-stretch">
        <div class="min-w-0 flex flex-col justify-center pr-1">
            <div class="mb-3">
                <div class="w-16 h-16 rounded-2xl bg-slate-950 text-white flex items-center justify-center shadow-xl">
                    <x-heroicon-o-printer class="w-9 h-9" />
                </div>
            </div>

            <div class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-100 border border-emerald-200 px-4 py-2 mb-3">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>

                <span class="text-sm font-black text-emerald-900">
                    Wireless Transfer Ready
                </span>
            </div>

            <h2 class="text-[2.9rem] font-black leading-[0.95] mb-3 text-slate-950 tracking-tight">
                Print your documents instantly
            </h2>

            <p class="text-base text-slate-600 leading-snug mb-4 max-w-lg font-bold">
                Transfer files from your phone using the local Wi-Fi,
                then preview and print directly on this kiosk.
            </p>

            <div class="grid grid-cols-2 gap-3 max-w-lg">
                <div class="rounded-2xl bg-white/90 border border-white p-4 shadow-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-wifi class="w-6 h-6 text-slate-900" />

                        <div class="text-xs font-black text-slate-500 uppercase">
                            Wi-Fi
                        </div>
                    </div>

                    <div class="text-2xl font-black text-slate-950 truncate">
                        {{ $globalKioskName ?? 'Piso Print' }}
                    </div>
                </div>

                <div class="rounded-2xl bg-white/90 border border-white p-4 shadow-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-banknotes class="w-6 h-6 text-slate-900" />

                        <div class="text-xs font-black text-slate-500 uppercase">
                            Price
                        </div>
                    </div>

                    @if (($globalCompany?->kiosk_name ?? 'Piso Print') === 'Piso Print')
                        <div class="text-2xl font-black text-slate-950">
                            ₱1/page
                        </div>
                    @else
                        <div class="text-2xl font-black text-slate-950 leading-tight">
                            ₱{{ $globalCompany?->black_price_per_page ?? 1 }}/page
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white/90 rounded-2xl p-4 shadow-xl border border-white flex flex-col justify-between">
            <div class="space-y-3">
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
                    <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3 border border-slate-100">
                        <div class="w-12 h-12 rounded-2xl bg-slate-950 text-white flex items-center justify-center shadow shrink-0">
                            @switch($item['icon'])
                                @case('wifi')
                                    <x-heroicon-o-wifi class="w-6 h-6" />
                                    @break

                                @case('arrow-up-tray')
                                    <x-heroicon-o-arrow-up-tray class="w-6 h-6" />
                                    @break

                                @case('magnifying-glass')
                                    <x-heroicon-o-magnifying-glass class="w-6 h-6" />
                                    @break

                                @case('banknotes')
                                    <x-heroicon-o-banknotes class="w-6 h-6" />
                                    @break
                            @endswitch
                        </div>

                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase leading-none mb-1">
                                Step {{ $step + 1 }}
                            </div>

                            <div class="text-lg font-black text-slate-800 leading-tight">
                                {{ $item['label'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                <div class="rounded-2xl bg-emerald-50 p-3 border border-emerald-200 mb-3">
                    <div class="flex items-center gap-2 mb-1">
                        <x-heroicon-o-signal class="w-5 h-5 text-emerald-900" />

                        <h3 class="text-base font-black text-emerald-900">
                            Wireless Transfer
                        </h3>
                    </div>

                    <p class="text-sm text-emerald-800 leading-snug font-bold">
                        Upload documents from your phone through local Wi-Fi.
                    </p>
                </div>

                <a
                    href="{{ route('kiosk.connect') }}"
                    class="flex items-center justify-center gap-2 w-full rounded-2xl bg-slate-950 text-white text-2xl font-black py-4 text-center shadow-xl active:scale-95 transition"
                >
                    <x-heroicon-o-printer class="w-7 h-7" />

                    Start Printing
                </a>
            </div>
        </div>
    </div>
</x-kiosk-layout>
