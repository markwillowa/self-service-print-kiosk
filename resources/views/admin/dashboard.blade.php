<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen p-8">
<main class="max-w-6xl mx-auto">
    <h1 class="text-4xl font-bold mb-8">Piso Print Admin</h1>

    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-6">
            <div class="text-gray-500">Total Credits</div>
            <div class="text-4xl font-bold">₱{{ $totalCredits }}</div>
        </div>

        <div class="bg-white rounded-2xl p-6">
            <div class="text-gray-500">Completed</div>
            <div class="text-4xl font-bold">{{ $completedJobs }}</div>
        </div>

        <div class="bg-white rounded-2xl p-6">
            <div class="text-gray-500">Queued</div>
            <div class="text-4xl font-bold">{{ $queuedJobs }}</div>
        </div>

        <div class="bg-white rounded-2xl p-6">
            <div class="text-gray-500">Failed</div>
            <div class="text-4xl font-bold">{{ $failedJobs }}</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6">
        <h2 class="text-2xl font-bold mb-4">Recent Jobs</h2>

        <table class="w-full text-left">
            <thead>
            <tr class="border-b">
                <th class="py-3">File</th>
                <th class="py-3">Pages</th>
                <th class="py-3">Paid</th>
                <th class="py-3">Status</th>
                <th class="py-3">Created</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($recentJobs as $job)
                <tr class="border-b">
                    <td class="py-3">{{ $job->original_filename }}</td>
                    <td class="py-3">{{ $job->pages }}</td>
                    <td class="py-3">₱{{ $job->paid_amount }}</td>
                    <td class="py-3">{{ $job->status }}</td>
                    <td class="py-3">{{ $job->created_at->format('M d, Y h:i A') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
