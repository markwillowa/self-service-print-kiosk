<x-kiosk-layout title="Upload Your File">
    <div class="h-full grid grid-cols-[0.95fr_1.05fr] gap-5 items-center py-4">
        <div class="min-w-0">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-20 h-20 rounded-3xl bg-slate-950 text-white flex items-center justify-center shadow-xl">
                    <x-heroicon-o-arrow-up-tray class="w-11 h-11" />
                </div>

                <div>
                    <h2 class="text-[3rem] font-black text-slate-950 leading-none mb-2">
                        Upload Your File
                    </h2>

                    <p class="text-lg text-slate-500 font-bold">
                        Open the upload page on your phone
                    </p>
                </div>
            </div>

            <div class="rounded-3xl bg-white border border-white p-5 shadow-lg mb-4">
                <div class="text-xs font-black text-slate-500 uppercase mb-2">
                    Upload URL
                </div>

                <div class="text-lg font-black text-slate-900 break-all leading-snug">
                    {{ $uploadUrl }}
                </div>
            </div>

            <div class="rounded-3xl bg-blue-50 border border-blue-200 p-4 mb-4">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-information-circle class="w-7 h-7 text-blue-700 shrink-0 mt-0.5" />

                    <div>
                        <h3 class="text-xl font-black text-blue-900 mb-2">
                            Instructions
                        </h3>

                        <ol class="text-base text-blue-800 space-y-1 list-decimal pl-5 font-bold leading-snug">
                            <li>Scan the QR code</li>
                            <li>Open the upload page</li>
                            <li>Select your document</li>
                            <li>Upload from your phone</li>
                            <li>Continue on the kiosk</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <a
                    href="{{ route('kiosk.home') }}"
                    class="flex items-center justify-center rounded-2xl bg-red-100 text-red-700 text-lg font-black py-4 active:scale-95 transition"
                >
                    Cancel
                </a>

                <a
                    href="{{ route('kiosk.connect') }}"
                    class="flex items-center justify-center gap-2 rounded-2xl bg-slate-200 text-slate-900 text-lg font-black py-4 active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                    Back
                </a>

                <a
                    href="{{ route('kiosk.upload') }}"
                    class="flex items-center justify-center gap-2 rounded-2xl bg-slate-950 text-white text-lg font-black py-4 shadow-xl active:scale-95 transition"
                >
                    Continue
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-slate-200">
                {!! QrCode::size(360)->generate($uploadUrl) !!}
            </div>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
