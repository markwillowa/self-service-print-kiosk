<x-kiosk-layout title="{{ $globalKioskName ?? 'Piso Print' }}">
    <div class="h-full grid grid-cols-[1.15fr_0.85fr] gap-5 items-center">
        <div class="min-w-0 pr-3">
            <div class="mb-4">
                <div class="w-20 h-20 rounded-3xl bg-slate-950 text-white flex items-center justify-center shadow-xl">
                    <x-heroicon-o-printer class="w-11 h-11" />
                </div>
            </div>

            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-100 border border-emerald-200 px-4 py-2 mb-4">
                <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>

                <span class="text-sm font-black text-emerald-900">
                    Wireless Transfer Ready
                </span>
            </div>

            <h2 class="text-[4rem] font-black leading-[0.9] mb-4 text-slate-950 tracking-tight">
                Print your documents instantly
            </h2>

            <p class="text-lg text-slate-600 leading-relaxed mb-5 max-w-2xl font-bold">
                Transfer files from your phone using the local Wi-Fi,
                preview your document, choose print settings,
                and print directly from this kiosk.
            </p>

            <div class="grid grid-cols-2 gap-4 max-w-2xl">
                <div class="rounded-3xl bg-white border border-white p-5 shadow-lg">
                    <div class="flex items-center gap-3 mb-2">
                        <x-heroicon-o-wifi class="w-7 h-7 text-slate-900" />

                        <div class="text-xs font-black text-slate-500 uppercase">
                            Wi-Fi Network
                        </div>
                    </div>

                    <div class="text-3xl font-black text-slate-950 truncate">
                        {{ $globalKioskName ?? 'Piso Print' }}
                    </div>
                </div>

                <div class="rounded-3xl bg-white border border-white p-5 shadow-lg">
                    <div class="flex items-center gap-3 mb-2">
                        <x-heroicon-o-banknotes class="w-7 h-7 text-slate-900" />

                        <div class="text-xs font-black text-slate-500 uppercase">
                            Printing Price
                        </div>
                    </div>

                    @if (($globalCompany?->kiosk_name ?? 'Piso Print') === 'Piso Print')
                        <div class="text-3xl font-black text-slate-950">
                            ₱1/page
                        </div>
                    @else
                        <div class="text-3xl font-black text-slate-950">
                            ₱{{ $globalCompany?->black_price_per_page ?? 1 }}/page
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-xl border border-white h-full flex flex-col">
            <div class="mb-4">
                <h3 class="text-2xl font-black text-slate-950 mb-1">
                    How It Works
                </h3>

                <p class="text-sm font-bold text-slate-500">
                    Complete printing in four easy steps
                </p>
            </div>

            <div class="space-y-3 flex-1">
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
                    <div class="flex items-center gap-4 rounded-2xl bg-slate-50 p-4 border border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-slate-950 text-white flex items-center justify-center shadow shrink-0">
                            @switch($item['icon'])
                                @case('wifi')
                                    <x-heroicon-o-wifi class="w-7 h-7" />
                                    @break

                                @case('arrow-up-tray')
                                    <x-heroicon-o-arrow-up-tray class="w-7 h-7" />
                                    @break

                                @case('magnifying-glass')
                                    <x-heroicon-o-magnifying-glass class="w-7 h-7" />
                                    @break

                                @case('banknotes')
                                    <x-heroicon-o-banknotes class="w-7 h-7" />
                                    @break
                            @endswitch
                        </div>

                        <div>
                            <div class="text-xs font-black text-slate-400 uppercase mb-1">
                                Step {{ $step + 1 }}
                            </div>

                            <div class="text-lg font-black text-slate-800">
                                {{ $item['label'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 mt-4 mb-4">
                <div class="flex items-center gap-2 mb-2">
                    <x-heroicon-o-signal class="w-5 h-5 text-emerald-900" />

                    <h3 class="text-base font-black text-emerald-900">
                        Wireless Transfer
                    </h3>
                </div>

                <p class="text-sm text-emerald-800 font-bold">
                    Upload documents directly from your phone through the local Wi-Fi connection.
                </p>
            </div>

            <a
                href="{{ route('kiosk.connect') }}"
                class="flex items-center justify-center gap-3 w-full rounded-2xl bg-slate-950 text-white text-2xl font-black py-5 text-center shadow-xl active:scale-95 transition"
            >
                <x-heroicon-o-printer class="w-7 h-7" />

                Start Printing
            </a>
        </div>
    </div>
</x-kiosk-layout>
