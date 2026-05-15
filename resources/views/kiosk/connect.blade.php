<x-kiosk-layout title="Connect to Wi-Fi">
    <div class="h-full grid grid-cols-[0.9fr_1.1fr] gap-3 items-center">
        <div class="min-w-0 pr-1">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-14 h-14 rounded-2xl bg-slate-950 text-white flex items-center justify-center shadow-xl">
                    <x-heroicon-o-wifi class="w-8 h-8" />
                </div>

                <div>
                    <h2 class="text-3xl font-black text-slate-950 leading-tight">
                        Connect to Wi-Fi
                    </h2>

                    <p class="text-sm text-slate-500 font-bold">
                        Scan using your phone camera
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="rounded-2xl bg-white/85 border border-white p-3 shadow-lg">
                    <div class="text-[10px] font-black text-slate-500 uppercase mb-1">
                        Wi-Fi Name
                    </div>

                    <div class="text-xl font-black text-slate-900">
                        {{ $wifiSsid }}
                    </div>
                </div>

                <div class="rounded-2xl bg-white/85 border border-white p-3 shadow-lg">
                    <div class="text-[10px] font-black text-slate-500 uppercase mb-1">
                        Password
                    </div>

                    <div class="text-xl font-black text-slate-900">
                        {{ $wifiPassword }}
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-blue-50 border border-blue-200 p-3 mb-3">
                <div class="flex items-start gap-2">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-blue-700 shrink-0 mt-0.5" />

                    <div>
                        <h3 class="text-base font-black text-blue-900 mb-1">
                            Instructions
                        </h3>

                        <ol class="text-sm text-blue-800 space-y-0.5 list-decimal pl-5 font-bold leading-snug">
                            <li>Scan the QR code</li>
                            <li>Connect to PisoPrint Wi-Fi</li>
                            <li>Tap Next when connected</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2">
                <a
                    href="{{ route('kiosk.home') }}"
                    class="flex items-center justify-center rounded-xl bg-red-100 text-red-700 text-base font-black py-3 active:scale-95 transition"
                >
                    Cancel
                </a>

                <a
                    href="{{ route('kiosk.home') }}"
                    class="flex items-center justify-center gap-1 rounded-xl bg-slate-200 text-slate-900 text-base font-black py-3 active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Back
                </a>

                <a
                    href="{{ route('kiosk.transfer') }}"
                    class="flex items-center justify-center gap-1 rounded-xl bg-slate-950 text-white text-base font-black py-3 shadow-xl active:scale-95 transition"
                >
                    Next
                    <x-heroicon-o-arrow-right class="w-4 h-4" />
                </a>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <div class="bg-white rounded-2xl p-4 shadow-xl border border-slate-200">
                {!! QrCode::size(260)->generate($wifiQr) !!}
            </div>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
