<x-kiosk-layout title="Admin Dashboard">
    <div class="h-full grid grid-cols-[180px_1fr] gap-3">
        @include('admin.partials.sidebar')

        <main class="min-w-0 flex flex-col gap-3">
            <div class="bg-white rounded-2xl p-3 shadow-sm shrink-0">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-3xl font-black text-slate-950 leading-none mb-1">
                            Dashboard
                        </h2>

                        <p class="text-sm text-slate-500 font-bold">
                            Reports by day, week, and month
                        </p>
                    </div>

                    <div class="flex gap-2 shrink-0">
                        @foreach ([
                            'day' => 'Today',
                            'week' => 'Week',
                            'month' => 'Month',
                        ] as $key => $label)
                            <a
                                href="{{ route('admin.dashboard', ['period' => $key]) }}"
                                class="rounded-xl px-3 py-2 text-sm font-black {{ $period === $key ? 'bg-slate-950 text-white' : 'bg-slate-100 text-slate-900' }}"
                            >
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-5 gap-2 shrink-0">
                @foreach ([
                    ['Credits', '₱' . $totalCredits, 'text-slate-950'],
                    ['Completed', $completedJobs, 'text-emerald-700'],
                    ['Queued', $queuedJobs, 'text-amber-600'],
                    ['Failed', $failedJobs, 'text-red-600'],
                    ['Cancelled', $cancelledJobs, 'text-slate-600'],
                ] as [$label, $value, $color])
                    <div class="bg-white rounded-2xl p-3 shadow-sm">
                        <div class="text-[10px] text-slate-500 font-black uppercase mb-1">
                            {{ $label }}
                        </div>

                        <div class="text-2xl font-black {{ $color }} leading-none">
                            {{ $value }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl p-3 shadow-sm flex-1 min-h-0">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xl font-black text-slate-950">
                        Recent Jobs
                    </h3>

                    <span class="text-[10px] font-black text-slate-400 uppercase">
                        Latest 10
                    </span>
                </div>

                <div class="h-[calc(100%-2rem)] overflow-y-auto">
                    <table class="w-full text-left">
                        <thead>
                        <tr class="border-b text-[10px] text-slate-500 uppercase">
                            <th class="py-2">File</th>
                            <th class="py-2">Pages</th>
                            <th class="py-2">Paid</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Created</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse ($recentJobs as $job)
                            <tr class="border-b last:border-b-0">
                                <td class="py-2 font-bold text-sm text-slate-900 max-w-[220px] truncate">
                                    {{ $job->original_filename }}
                                </td>

                                <td class="py-2 text-sm font-bold">
                                    {{ $job->pages }}
                                </td>

                                <td class="py-2 text-sm font-bold">
                                    ₱{{ $job->paid_amount }}
                                </td>

                                <td class="py-2">
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-[11px] font-black text-slate-700">
                                        {{ $job->status }}
                                    </span>
                                </td>

                                <td class="py-2 text-xs font-bold text-slate-500">
                                    {{ $job->created_at->format('M d, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="5"
                                    class="py-8 text-center text-slate-400 font-black"
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
