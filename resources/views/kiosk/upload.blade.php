<x-kiosk-layout title="Upload File">
    <div class="h-full flex items-center justify-center">
        <div class="w-full max-w-2xl bg-white/90 rounded-[2.5rem] p-8 shadow-2xl border border-white">
            <div class="text-center mb-6">
                <div class="text-6xl mb-2">📄</div>

                <h2 class="text-4xl font-black mb-2">
                    Upload your file
                </h2>

                <p class="text-base text-slate-600">
                    Supports PDF, Word, Excel, PowerPoint, Images, and Text Files
                </p>

                <p class="text-sm text-slate-500 mt-1">
                    Maximum file size: 100MB
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-4 rounded-3xl bg-red-100 text-red-700 p-4 text-base font-black">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('kiosk.store') }}"
                enctype="multipart/form-data"
                class="space-y-5"
            >
                @csrf

                <input
                    type="file"
                    name="document"
                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.txt"
                    required
                    class="block w-full rounded-3xl bg-slate-100 p-5 text-base font-black"
                >

                <div class="grid grid-cols-2 gap-4">
                    <a
                        href="{{ route('kiosk.home') }}"
                        class="rounded-3xl bg-slate-200 text-slate-900 text-xl font-black py-4 text-center"
                    >
                        Back
                    </a>

                    <button
                        type="submit"
                        class="rounded-3xl bg-slate-950 text-white text-xl font-black py-4 active:scale-95 transition"
                    >
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('kiosk.partials.auto-reset', ['seconds' => 60])
</x-kiosk-layout>
