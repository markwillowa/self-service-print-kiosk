<x-kiosk-layout title="Connect to Wi-Fi">
    <div class="h-full grid grid-cols-[0.9fr_1.1fr] gap-5 items-center">
        <div class="pr-2">
            <div class="flex items-center gap-3 mb-4">
                <x-heroicon-o-wifi class="w-8 h-8 text-slate-900" />

                <div>
                    <h2 class="text-2xl font-black text-slate-950">
                        Connect to Wi-Fi
                    </h2>

                    <p class="text-xs text-slate-500">
                        Scan using your phone camera
                    </p>
                </div>
            </div>

            <div class="space-y-3 mb-4">
                <div class="rounded-2xl bg-white/80 border border-white p-3 shadow-lg">
                    <div class="text-[10px] font-bold text-slate-500 mb-1">
                        Wi-Fi Name
                    </div>

                    <div class="text-lg font-black text-slate-900">
                        {{ $wifiSsid }}
                    </div>
                </div>

                <div class="rounded-2xl bg-white/80 border border-white p-3 shadow-lg">
                    <div class="text-[10px] font-bold text-slate-500 mb-1">
                        Password
                    </div>

                    <div class="text-lg font-black text-slate-900">
                        {{ $wifiPassword }}
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-blue-50 border border-blue-200 p-3 mb-4">
                <div class="flex items-start gap-2">
                    <x-heroicon-o-information-circle class="w-4 h-4 text-blue-700 shrink-0 mt-0.5" />

                    <div>
                        <h3 class="text-xs font-black text-blue-900 mb-1">
                            Instructions
                        </h3>

                        <ol class="text-[11px] text-blue-800 space-y-0.5 list-decimal pl-3">
                            <li>Scan QR code</li>
                            <li>Connect to PisoPrint Wi-Fi</li>
                            <li>Tap Next when connected</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a
                    href="{{ route('kiosk.home') }}"
                    class="flex items-center justify-center gap-2 rounded-2xl bg-slate-200 text-slate-900 text-sm font-black py-3"
                >
                    <x-heroicon-o-arrow-left class="w-4 h-4" />

                    Back
                </a>

                <a
                    href="{{ route('kiosk.transfer') }}"
                    class="flex items-center justify-center gap-2 rounded-2xl bg-slate-950 text-white text-sm font-black py-3 shadow-xl active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-right class="w-4 h-4" />

                    Next
                </a>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <div class="bg-white rounded-[2rem] p-5 shadow-2xl border border-slate-200">
                {!! QrCode::size(280)->generate($wifiQr) !!}
            </div>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
