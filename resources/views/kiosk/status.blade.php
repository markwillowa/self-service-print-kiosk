<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Status</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (in_array($printJob->status, ['queued', 'printing'], true))
        <meta http-equiv="refresh" content="2">
    @endif

    @if ($printJob->status === 'completed')
        <meta http-equiv="refresh" content="5;url={{ route('kiosk.home') }}">
    @endif
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
<main class="w-[800px] h-[480px] bg-white flex flex-col items-center justify-center text-center p-8">
    @if ($printJob->status === 'queued')
        <div class="text-7xl mb-8">⏳</div>
        <h1 class="text-5xl font-bold mb-4">Queued</h1>
        <p class="text-2xl text-gray-600">Waiting for printer...</p>
    @elseif ($printJob->status === 'printing')
        <div class="text-7xl mb-8">🖨️</div>
        <h1 class="text-5xl font-bold mb-4">Printing...</h1>
        <p class="text-2xl text-gray-600">Please wait</p>
    @elseif ($printJob->status === 'completed')
        <div class="text-7xl mb-8">✅</div>
        <h1 class="text-5xl font-bold mb-4">Done</h1>
        <p class="text-2xl text-gray-600">Thank you</p>
    @elseif ($printJob->status === 'failed')
        <div class="text-7xl mb-8">⚠️</div>
        <h1 class="text-5xl font-bold mb-4">Print Failed</h1>
        <p class="text-2xl text-gray-600 mb-6">Please contact the operator</p>

        <a
            href="{{ route('kiosk.home') }}"
            class="rounded-2xl bg-black text-white text-2xl font-bold px-10 py-5"
        >
            Back to Home
        </a>
    @endif
</main>
</body>
</html>
