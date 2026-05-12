<x-kiosk-layout title="Upload Your File">
    <div class="h-full grid grid-cols-[0.9fr_1.1fr] gap-5 items-center">
        <div class="pr-2">
            <div class="flex items-center gap-3 mb-4">
                <x-heroicon-o-arrow-up-tray class="w-8 h-8 text-slate-900" />

                <div>
                    <h2 class="text-2xl font-black text-slate-950">
                        Upload Your File
                    </h2>

                    <p class="text-xs text-slate-500">
                        Open the upload page on your phone
                    </p>
                </div>
            </div>

            <div class="space-y-3 mb-4">
                <div class="rounded-2xl bg-white/80 border border-white p-3 shadow-lg">
                    <div class="text-[10px] font-bold text-slate-500 mb-1">
                        Upload URL
                    </div>

                    <div class="text-sm font-black text-slate-900 break-all">
                        {{ $uploadUrl }}
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
                            <li>Open upload page</li>
                            <li>Select your document</li>
                            <li>Upload from your phone</li>
                            <li>Continue on kiosk</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a
                    href="{{ route('kiosk.connect') }}"
                    class="flex items-center justify-center gap-2 rounded-2xl bg-slate-200 text-slate-900 text-sm font-black py-3"
                >
                    <x-heroicon-o-arrow-left class="w-4 h-4" />

                    Back
                </a>

                <a
                    href="{{ route('kiosk.upload') }}"
                    class="flex items-center justify-center gap-2 rounded-2xl bg-slate-950 text-white text-sm font-black py-3 shadow-xl active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-right class="w-4 h-4" />

                    Continue
                </a>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <div class="bg-white rounded-[2rem] p-5 shadow-2xl border border-slate-200">
                {!! QrCode::size(280)->generate($uploadUrl) !!}
            </div>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
