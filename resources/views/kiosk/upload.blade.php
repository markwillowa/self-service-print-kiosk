<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
<main class="w-[800px] h-[480px] bg-white flex flex-col items-center justify-center text-center p-8">
    <h1 class="text-4xl font-bold mb-6">Upload PDF</h1>

    @if ($errors->any())
        <div class="mb-4 text-red-600 text-xl">
            {{ $errors->first() }}
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('kiosk.store') }}"
        enctype="multipart/form-data"
        class="space-y-6"
    >
        @csrf

        <input
            type="file"
            name="document"
            accept="application/pdf"
            required
            class="text-xl"
        >

        <button
            type="submit"
            class="rounded-2xl bg-black text-white text-2xl font-bold px-10 py-5"
        >
            Continue
        </button>
    </form>
</main>
</body>
</html>
