<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Upload File</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
<main class="w-full max-w-md bg-white rounded-[2rem] p-6 shadow-2xl">
    <div class="text-center mb-6">
        <div class="flex justify-center mb-3">
            <div class="w-20 h-20 rounded-[2rem] bg-slate-950 text-white flex items-center justify-center shadow-xl">
                <x-heroicon-o-arrow-up-tray class="w-10 h-10" />
            </div>
        </div>

        <h1 class="text-3xl font-black text-slate-950 mb-2">
            Upload File
        </h1>

        <p class="text-sm text-slate-500">
            Upload your document to PisoPrint
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-2xl bg-red-100 text-red-700 p-4 text-sm font-bold">
            {{ $errors->first() }}
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('kiosk.mobile-store') }}"
        enctype="multipart/form-data"
        class="space-y-4"
    >
        @csrf

        <label class="block">
                <span class="block text-sm font-black text-slate-600 mb-2">
                    Select Document
                </span>

            <input
                type="file"
                name="document"
                accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.txt"
                required
                class="block w-full rounded-2xl bg-slate-100 p-4 text-sm font-bold text-slate-700"
            >
        </label>

        <button
            type="submit"
            class="w-full rounded-2xl bg-slate-950 text-white text-base font-black py-4 active:scale-95 transition"
        >
            Upload File
        </button>
    </form>

    <p class="text-center text-xs text-slate-400 mt-5 leading-relaxed">
        After uploading, return to the kiosk screen and tap Refresh.
    </p>
</main>
</body>
</html>
