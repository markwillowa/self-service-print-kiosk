<x-kiosk-layout title="Admin Dashboard">
    <style>
        .admin-scroll::-webkit-scrollbar {
            width: 18px;
        }

        .admin-scroll::-webkit-scrollbar-track {
            background: rgba(148, 163, 184, 0.15);
            border-radius: 999px;
        }

        .admin-scroll::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, 0.75);
            border-radius: 999px;
        }
    </style>

    <div class="h-full grid grid-cols-[240px_1fr] gap-4">
        @include('admin.partials.sidebar')

        <main class="admin-scroll min-w-0 overflow-y-auto pr-2">
            <div class="flex flex-col gap-4">
                <div class="bg-white rounded-3xl p-5 shadow-xl shrink-0">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-4xl font-black text-slate-950 leading-none mb-2">
                                Dashboard
                            </h2>

                            <p class="text-base text-slate-500 font-bold">
                                Reports by day, week, and month
                            </p>
                        </div>

                        <div class="flex gap-3 shrink-0">
                            @foreach ([
                                'day' => 'Today',
                                'week' => 'Week',
                                'month' => 'Month',
                            ] as $key => $label)
                                <a
                                    href="{{ route('admin.dashboard', ['period' => $key]) }}"
                                    class="rounded-2xl px-5 py-3 text-base font-black {{ $period === $key ? 'bg-slate-950 text-white' : 'bg-slate-100 text-slate-900' }}"
                                >
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-5 gap-4">
                    @foreach ([
                        ['Credits', '₱' . $totalCredits, 'text-slate-950'],
                        ['Completed', $completedJobs, 'text-emerald-700'],
                        ['Queued', $queuedJobs, 'text-amber-600'],
                        ['Failed', $failedJobs, 'text-red-600'],
                        ['Cancelled', $cancelledJobs, 'text-slate-600'],
                    ] as [$label, $value, $color])
                        <div class="bg-white rounded-3xl p-5 shadow-xl">
                            <div class="text-xs text-slate-500 font-black uppercase mb-2">
                                {{ $label }}
                            </div>

                            <div class="text-4xl font-black {{ $color }} leading-none">
                                {{ $value }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-3xl p-5 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-black text-slate-950">
                            Recent Jobs
                        </h3>

                        <span class="text-xs font-black text-slate-400 uppercase">
                            Latest 10
                        </span>
                    </div>

                    <table class="w-full text-left">
                        <thead>
                        <tr class="border-b text-xs text-slate-500 uppercase">
                            <th class="py-3">File</th>
                            <th class="py-3">Pages</th>
                            <th class="py-3">Paid</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Created</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse ($recentJobs as $job)
                            <tr class="border-b last:border-b-0">
                                <td class="py-3 font-bold text-base text-slate-900 max-w-[320px] truncate">
                                    {{ $job->original_filename }}
                                </td>

                                <td class="py-3 text-base font-bold">
                                    {{ $job->pages }}
                                </td>

                                <td class="py-3 text-base font-bold">
                                    ₱{{ $job->paid_amount }}
                                </td>

                                <td class="py-3">
                                    <span class="rounded-full bg-slate-100 px-3 py-2 text-xs font-black text-slate-700">
                                        {{ $job->status }}
                                    </span>
                                </td>

                                <td class="py-3 text-sm font-bold text-slate-500">
                                    {{ $job->created_at->format('M d, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="5"
                                    class="py-12 text-center text-slate-400 font-black text-lg"
                                >
                                    No print jobs yet.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</x-kiosk-layout>
