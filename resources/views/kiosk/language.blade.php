<x-kiosk-layout title="{{ __('Select Language') }}">
    <div class="h-full flex flex-col justify-between py-4 max-w-4xl mx-auto">
        <div class="text-center pt-2 mb-4">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-slate-950 text-white mb-3 shadow-xl">
                <x-heroicon-o-language class="w-9 h-9" />
            </div>

            <h2 class="text-4xl font-black text-slate-950 tracking-tight mb-2">
                Select Language / Pumili ng Wika
            </h2>

            <p class="text-base text-slate-600 font-bold max-w-lg mx-auto">
                Choose your preferred language / Pumili ng nais mong wika
            </p>
        </div>

        <div class="grid grid-cols-2 gap-6 my-auto">
            <!-- English Option -->
            <form method="POST" action="{{ route('kiosk.set-language') }}" class="h-full">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button
                    type="submit"
                    class="w-full h-full text-left rounded-3xl p-6 transition transform active:scale-95 shadow-xl border-4 flex flex-col justify-between min-h-[260px] relative overflow-hidden group {{ app()->getLocale() === 'en' ? 'bg-slate-950 text-white border-slate-950 ring-4 ring-slate-900/20' : 'bg-white text-slate-900 border-white hover:border-slate-300' }}"
                >
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-5xl shadow-sm rounded-2xl p-2 bg-slate-100/10">🇬🇧</span>

                        @if(app()->getLocale() === 'en')
                            <span class="inline-flex items-center gap-1 bg-emerald-500 text-white text-xs font-black px-3 py-1.5 rounded-full uppercase tracking-wider">
                                <x-heroicon-o-check-circle class="w-4 h-4" />
                                Selected
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-3xl font-black mb-2 tracking-tight">
                            English
                        </h3>
                        <p class="text-sm font-bold opacity-80 leading-snug">
                            Use English for all instructions, options, and status screens
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t {{ app()->getLocale() === 'en' ? 'border-slate-800' : 'border-slate-100' }} flex items-center gap-2 font-black text-sm">
                        <span>Select English</span>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </div>
                </button>
            </form>

            <!-- Tagalog Option -->
            <form method="POST" action="{{ route('kiosk.set-language') }}" class="h-full">
                @csrf
                <input type="hidden" name="locale" value="tl">
                <button
                    type="submit"
                    class="w-full h-full text-left rounded-3xl p-6 transition transform active:scale-95 shadow-xl border-4 flex flex-col justify-between min-h-[260px] relative overflow-hidden group {{ app()->getLocale() === 'tl' ? 'bg-slate-950 text-white border-slate-950 ring-4 ring-slate-900/20' : 'bg-white text-slate-900 border-white hover:border-slate-300' }}"
                >
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-5xl shadow-sm rounded-2xl p-2 bg-slate-100/10">🇵🇭</span>

                        @if(app()->getLocale() === 'tl')
                            <span class="inline-flex items-center gap-1 bg-emerald-500 text-white text-xs font-black px-3 py-1.5 rounded-full uppercase tracking-wider">
                                <x-heroicon-o-check-circle class="w-4 h-4" />
                                Naipili
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-3xl font-black mb-2 tracking-tight">
                            Tagalog (Filipino)
                        </h3>
                        <p class="text-sm font-bold opacity-80 leading-snug">
                            Gamitin ang wikang Tagalog para sa lahat ng panuto at opsyon sa pag-print
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t {{ app()->getLocale() === 'tl' ? 'border-slate-800' : 'border-slate-100' }} flex items-center gap-2 font-black text-sm">
                        <span>Piliin ang Tagalog</span>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </div>
                </button>
            </form>
        </div>
    </div>
</x-kiosk-layout>
