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

        <main class="bg-white rounded-3xl p-5 shadow-xl overflow-hidden flex flex-col min-h-0">
            <div class="flex items-center justify-between mb-5 shrink-0">
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

            <div class="grid grid-cols-5 gap-4 mb-5 shrink-0">
                @foreach ([
                    ['Credits', '₱' . $totalCredits, 'text-slate-950'],
                    ['Completed', $completedJobs, 'text-emerald-700'],
                    ['Queued', $queuedJobs, 'text-amber-600'],
                    ['Failed', $failedJobs, 'text-red-600'],
                    ['Cancelled', $cancelledJobs, 'text-slate-600'],
                ] as [$label, $value, $color])
                    <div class="bg-slate-100 rounded-3xl p-5 shadow-sm">
                        <div class="text-xs text-slate-500 font-black uppercase mb-2">
                            {{ $label }}
                        </div>

                        <div class="text-4xl font-black {{ $color }} leading-none">
                            {{ $value }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between mb-4 shrink-0">
                <h3 class="text-2xl font-black text-slate-950">
                    Recent Jobs
                </h3>

                <div class="rounded-2xl bg-slate-100 px-4 py-3">
                    <div class="text-xs font-black uppercase text-slate-500">
                        Total Records
                    </div>

                    <div class="text-2xl font-black text-slate-950">
                        {{ $recentJobs->total() }}
                    </div>
                </div>
            </div>

            <div class="admin-scroll flex-1 min-h-0 overflow-y-auto pr-2">
                <table class="w-full text-left">
                    <thead class="sticky top-0 bg-white z-10">
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
                            <td class="py-4 font-bold text-base text-slate-900 max-w-[320px] truncate">
                                {{ $job->original_filename }}
                            </td>

                            <td class="py-4 text-base font-bold">
                                {{ $job->pages }}
                            </td>

                            <td class="py-4 text-base font-bold">
                                ₱{{ $job->paid_amount }}
                            </td>

                            <td class="py-4">
                                @php
                                    $statusColor = match ($job->status) {
                                        'completed' => 'bg-emerald-100 text-emerald-700',
                                        'queued', 'printing' => 'bg-amber-100 text-amber-700',
                                        'failed' => 'bg-red-100 text-red-700',
                                        'cancelled' => 'bg-slate-200 text-slate-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp

                                <span class="rounded-full px-3 py-2 text-xs font-black {{ $statusColor }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>

                            <td class="py-4 text-sm font-bold text-slate-500">
                                {{ $job->created_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="5"
                                class="py-16 text-center text-slate-400 font-black text-lg"
                            >
                                No print jobs yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-5 border-t border-slate-200 mt-4 shrink-0">
                <div class="flex items-center justify-between">
                    <div class="text-base font-black text-slate-500">
                        Page {{ $recentJobs->currentPage() }}
                        of
                        {{ $recentJobs->lastPage() }}
                    </div>

                    <div class="flex items-center gap-3">
                        @if ($recentJobs->onFirstPage())
                            <span class="rounded-2xl bg-slate-100 text-slate-400 px-6 py-3 text-base font-black">
                                Previous
                            </span>
                        @else
                            <a
                                href="{{ $recentJobs->previousPageUrl() }}"
                                class="rounded-2xl bg-slate-200 text-slate-900 px-6 py-3 text-base font-black active:scale-95 transition"
                            >
                                Previous
                            </a>
                        @endif

                        @if ($recentJobs->hasMorePages())
                            <a
                                href="{{ $recentJobs->nextPageUrl() }}"
                                class="rounded-2xl bg-slate-950 text-white px-6 py-3 text-base font-black shadow-lg active:scale-95 transition"
                            >
                                Next
                            </a>
                        @else
                            <span class="rounded-2xl bg-slate-100 text-slate-400 px-6 py-3 text-base font-black">
                                Next
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-kiosk-layout>
