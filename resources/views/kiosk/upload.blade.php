<x-kiosk-layout title="Upload PDF">
    <div class="h-full flex items-center justify-center">
        <div class="w-full max-w-xl bg-white/85 rounded-[2rem] p-6 shadow-xl border border-white text-center">
            <div class="text-5xl mb-2">📄</div>

            <h2 class="text-3xl font-black mb-2">
                Upload your file
            </h2>

            <p class="text-base text-slate-600 mb-4">
                Maximum file size: 100MB
            </p>

            @if ($errors->any())
                <div class="mb-3 rounded-2xl bg-red-100 text-red-700 p-3 text-base font-bold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('kiosk.store') }}"
                enctype="multipart/form-data"
                class="space-y-4"
            >
                @csrf

                <input
                    type="file"
                    name="document"
                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.txt"
                    required
                    class="block w-full rounded-2xl bg-slate-100 p-4 text-base font-bold"
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
