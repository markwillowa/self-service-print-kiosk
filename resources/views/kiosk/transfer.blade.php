<x-kiosk-layout title="Upload Your File">
    <div class="h-full grid grid-cols-[0.9fr_1.1fr] gap-3 items-center">
        <div class="min-w-0 pr-1">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-14 h-14 rounded-2xl bg-slate-950 text-white flex items-center justify-center shadow-xl">
                    <x-heroicon-o-arrow-up-tray class="w-8 h-8" />
                </div>

                <div>
                    <h2 class="text-3xl font-black text-slate-950 leading-tight">
                        Upload Your File
                    </h2>

                    <p class="text-sm text-slate-500 font-bold">
                        Open the upload page on your phone
                    </p>
                </div>
            </div>

            <div class="rounded-2xl bg-white/85 border border-white p-3 shadow-lg mb-3">
                <div class="text-[10px] font-black text-slate-500 uppercase mb-1">
                    Upload URL
                </div>

                <div class="text-sm font-black text-slate-900 break-all leading-snug">
                    {{ $uploadUrl }}
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
                            <li>Open the upload page</li>
                            <li>Select your document</li>
                            <li>Upload from your phone</li>
                            <li>Continue on the kiosk</li>
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
                    href="{{ route('kiosk.connect') }}"
                    class="flex items-center justify-center gap-1 rounded-xl bg-slate-200 text-slate-900 text-base font-black py-3 active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Back
                </a>

                <a
                    href="{{ route('kiosk.upload') }}"
                    class="flex items-center justify-center gap-1 rounded-xl bg-slate-950 text-white text-base font-black py-3 shadow-xl active:scale-95 transition"
                >
                    Continue
                    <x-heroicon-o-arrow-right class="w-4 h-4" />
                </a>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <div class="bg-white rounded-2xl p-4 shadow-xl border border-slate-200">
                {!! QrCode::size(260)->generate($uploadUrl) !!}
            </div>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
