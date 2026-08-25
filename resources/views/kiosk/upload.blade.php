<x-kiosk-layout title="{{ __('Select Document') }}">
    <meta http-equiv="refresh" content="5">

    <div class="h-full flex flex-col min-h-0 gap-4 py-2">
        <div class="flex items-center justify-between shrink-0 gap-4">
            <div class="min-w-0">
                <h1 class="text-4xl font-black text-slate-950 mb-2 leading-none">
                    {{ __('Select Document') }}
                </h1>

                <p class="text-base text-slate-500 font-bold">
                    {{ __('Choose an uploaded file to print or upload directly') }}
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <button
                    onclick="
                        const icon = document.getElementById('refresh-icon');

                        icon.classList.add('animate-spin');

                        setTimeout(() => {
                            window.location.reload();
                        }, 300);
                    "
                    type="button"
                    class="flex items-center gap-2 rounded-2xl bg-white border border-slate-200 px-5 h-14 text-base font-black text-slate-900 shadow-lg active:scale-95 transition"
                >
                    <x-heroicon-o-arrow-path
                        id="refresh-icon"
                        class="w-5 h-5"
                    />

                    Refresh
                </button>

                <a
                    href="{{ route('kiosk.language') }}"
                    class="flex items-center justify-center rounded-2xl bg-red-100 px-5 h-14 text-base font-black text-red-700 active:scale-95 transition shadow-lg"
                >
                    {{ __('Cancel') }}
                </a>

                <a
                    href="{{ route('kiosk.transfer') }}"
                    class="flex items-center justify-center gap-2 rounded-2xl bg-slate-200 px-5 h-14 text-base font-black text-slate-900 active:scale-95 transition shadow-lg"
                >
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                    {{ __('Back') }}
                </a>
            </div>
        </div>

        <div class="flex-1 min-h-0 overflow-hidden">
            <div class="bg-white/90 rounded-3xl border border-white shadow-xl h-full overflow-y-auto p-4">
                @forelse ($printJobs as $printJob)
                    <form
                        method="POST"
                        action="{{ route('kiosk.select-upload', $printJob) }}"
                        onsubmit="handleUploadSubmit(this)"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="upload-card-btn w-full text-left"
                        >
                            <div class="flex items-center justify-between rounded-3xl bg-slate-50 border border-slate-200 p-4 mb-3 active:scale-[0.99] transition shadow">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0 shadow">
                                        <x-heroicon-o-document-text class="w-9 h-9" />
                                    </div>

                                    <div class="min-w-0">
                                        <div class="text-2xl font-black text-slate-900 truncate leading-tight">
                                            {{ $printJob->original_filename }}
                                        </div>

                                        <div class="text-base text-slate-500 mt-1 font-bold">
                                            @if ($printJob->conversion_status === 'completed')
                                                {{ $printJob->pages }} {{ __('Pages') }}
                                                •
                                                {{ strtoupper($printJob->original_extension) }}
                                            @else
                                                Ready
                                                •
                                                {{ strtoupper($printJob->original_extension) }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="upload-btn-badge flex items-center gap-2 rounded-2xl bg-slate-950 text-white px-6 py-3 text-base font-black shrink-0 shadow">
                                    <span class="upload-btn-text">
                                        @if ($printJob->conversion_status === 'completed')
                                            {{ __('Select & Preview') }}
                                        @else
                                            Prepare
                                        @endif
                                    </span>

                                    <x-heroicon-o-arrow-right class="upload-btn-icon w-5 h-5" />
                                </div>
                            </div>
                        </button>
                    </form>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-center px-6">
                        <x-heroicon-o-inbox class="w-28 h-28 text-slate-300 mb-5" />

                        <h2 class="text-5xl font-black text-slate-700 mb-4">
                            {{ __('No files uploaded yet') }}
                        </h2>

                        <p class="text-xl text-slate-500 max-w-2xl leading-snug font-bold">
                            {{ __('Upload your document using your phone or drag and drop here.') }}
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function handleUploadSubmit(form) {
            document.querySelectorAll('.upload-card-btn').forEach((btn) => {
                btn.disabled = true;
                btn.classList.add('pointer-events-none', 'opacity-50');
            });

            const badge = form.querySelector('.upload-btn-badge');
            if (badge) {
                badge.classList.remove('bg-slate-950');
                badge.classList.add('bg-blue-600');
                badge.innerHTML = `
                    <svg class="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>${'{{ __("Applying Settings...") }}'}</span>
                `;
            }
        }
    </script>

    @include('kiosk.partials.auto-reset', ['seconds' => 90])
</x-kiosk-layout>
