<x-kiosk-layout title="{{ __('Transfer Your Document') }}">
    <div class="h-full grid grid-cols-3 gap-6 items-center py-4">
        <div class="col-span-2 min-w-0">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-20 h-20 rounded-3xl bg-slate-950 text-white flex items-center justify-center shadow-xl">
                    <x-heroicon-o-arrow-up-tray class="w-11 h-11" />
                </div>

                <div>
                    <h2 class="text-[3rem] font-black text-slate-950 leading-none mb-2">
                        {{ __('Transfer Your Document') }}
                    </h2>

                    <p class="text-lg text-slate-500 font-bold">
                        {{ __('Scan QR or visit link on your phone') }}
                    </p>
                </div>
            </div>

            <div class="rounded-3xl bg-white border border-white p-5 shadow-lg mb-4">
                <div class="text-xs font-black text-slate-500 uppercase mb-2">
                    {{ __('Upload Link') }}
                </div>

                <div class="text-lg font-black text-slate-900 break-all leading-snug">
                    {{ $uploadUrl }}
                </div>
            </div>

            <div class="rounded-2xl bg-blue-50 border border-blue-200 p-4 mb-4">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-information-circle class="w-6 h-6 text-blue-700 shrink-0 mt-0.5" />

                    <div class="pr-2 min-w-0">
                        <h3 class="text-base font-black text-blue-900 mb-1.5">
                            {{ __('Instructions') }}
                        </h3>

                        <ol class="text-sm text-blue-800 space-y-1 list-decimal pl-4 pr-2 font-bold leading-snug">
                            <li>{{ __('Stay connected to kiosk Wi-Fi') }}</li>
                            <li>{{ __('Scan the QR code using your phone camera') }}</li>
                            <li>{{ __('If scanning is not working, open Chrome or any browser on your phone and manually type the Upload Link above') }}</li>
                            <li>{{ __('Choose and upload your document') }}</li>
                            <li>{{ __('Tap Next to select your file') }}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <a
                    href="{{ route('kiosk.language') }}"
                    class="flex items-center justify-center rounded-2xl bg-red-100 text-red-700 text-lg font-black py-4 active:scale-95 transition"
                >
                    {{ __('Cancel') }}
                </a>

                <a
                    href="{{ route('kiosk.connect') }}"
                    class="flex items-center justify-center gap-2 rounded-2xl bg-slate-200 text-slate-900 text-lg font-black py-4 active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                    {{ __('Back') }}
                </a>

                <a
                    href="{{ route('kiosk.upload') }}"
                    class="flex items-center justify-center gap-2 rounded-2xl bg-slate-950 text-white text-lg font-black py-4 shadow-xl active:scale-95 transition"
                >
                    {{ __('Next') }}
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
            </div>
        </div>

        <div class="col-span-1 flex items-center justify-center">
            <div class="bg-white rounded-[2rem] p-5 shadow-xl border border-slate-200">
                {!! QrCode::size(320)->generate($uploadUrl) !!}
            </div>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
