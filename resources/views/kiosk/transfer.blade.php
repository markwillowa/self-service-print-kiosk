<x-kiosk-layout title="Upload Your File">
    <div class="h-full grid grid-cols-[0.9fr_1.1fr] gap-10 items-center">
        <div class="min-w-0 pr-4">
            <div class="flex items-center gap-5 mb-8">
                <div class="w-20 h-20 rounded-[2rem] bg-slate-950 text-white flex items-center justify-center shadow-2xl">
                    <x-heroicon-o-arrow-up-tray class="w-11 h-11" />
                </div>

                <div>
                    <h2 class="text-5xl font-black text-slate-950 leading-tight">
                        Upload Your File
                    </h2>

                    <p class="text-lg text-slate-500 font-bold">
                        Open the upload page on your phone
                    </p>
                </div>
            </div>

            <div class="rounded-[2rem] bg-white/85 border border-white p-6 shadow-xl mb-6">
                <div class="text-sm font-black text-slate-500 uppercase mb-2">
                    Upload URL
                </div>

                <div class="text-2xl font-black text-slate-900 break-all leading-snug">
                    {{ $uploadUrl }}
                </div>
            </div>

            <div class="rounded-[2rem] bg-blue-50 border border-blue-200 p-6 mb-8">
                <div class="flex items-start gap-4">
                    <x-heroicon-o-information-circle class="w-8 h-8 text-blue-700 shrink-0 mt-1" />

                    <div>
                        <h3 class="text-2xl font-black text-blue-900 mb-3">
                            Instructions
                        </h3>

                        <ol class="text-xl text-blue-800 space-y-2 list-decimal pl-6 font-bold">
                            <li>Scan the QR code</li>
                            <li>Open the upload page</li>
                            <li>Select your document</li>
                            <li>Upload from your phone</li>
                            <li>Continue on the kiosk</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <a
                    href="{{ route('kiosk.home') }}"
                    class="flex items-center justify-center gap-3 rounded-[2rem] bg-red-100 text-red-700 text-2xl font-black py-6 active:scale-95 transition"
                >
                    Cancel
                </a>

                <a
                    href="{{ route('kiosk.connect') }}"
                    class="flex items-center justify-center gap-3 rounded-[2rem] bg-slate-200 text-slate-900 text-2xl font-black py-6 active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-left class="w-7 h-7" />
                    Back
                </a>

                <a
                    href="{{ route('kiosk.upload') }}"
                    class="flex items-center justify-center gap-3 rounded-[2rem] bg-slate-950 text-white text-2xl font-black py-6 shadow-2xl active:scale-95 transition"
                >
                    Continue
                    <x-heroicon-o-arrow-right class="w-7 h-7" />
                </a>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <div class="bg-white rounded-[3rem] p-10 shadow-2xl border border-slate-200">
                {!! QrCode::size(460)->generate($uploadUrl) !!}
            </div>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
