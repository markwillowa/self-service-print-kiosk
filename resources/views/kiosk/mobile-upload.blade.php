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
            <x-heroicon-o-arrow-up-tray class="w-14 h-14 text-slate-900" />
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

        <input
            type="file"
            name="document"
            accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.txt"
            required
            class="block w-full rounded-2xl bg-slate-100 p-4 text-sm font-bold"
        >

        <button
            type="submit"
            class="w-full rounded-2xl bg-slate-950 text-white text-base font-black py-4"
        >
            Upload File
        </button>
    </form>
</main>
</body>
</html>
