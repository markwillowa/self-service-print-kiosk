<x-kiosk-layout title="Print Jobs">
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
                        Print Jobs
                    </h2>

                    <p class="text-base text-slate-500 font-bold">
                        Latest kiosk print requests
                    </p>
                </div>
            </div>

            <div class="admin-scroll flex-1 min-h-0 overflow-y-auto pr-2">
                <table class="w-full text-left">
                    <thead class="sticky top-0 bg-white z-10">
                    <tr class="border-b text-xs text-slate-500 uppercase">
                        <th class="py-3">File</th>
                        <th class="py-3">Pages</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Created</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($jobs as $job)
                        <tr class="border-b last:border-b-0">
                            <td class="py-4 text-base font-bold text-slate-900 max-w-[360px] truncate">
                                {{ $job->original_filename }}
                            </td>

                            <td class="py-4 text-base font-bold">
                                {{ $job->pages }}
                            </td>

                            <td class="py-4 text-base font-bold">
                                ₱{{ $job->paid_amount }}
                            </td>

                            <td class="py-4">
                                <span class="rounded-full bg-slate-100 px-3 py-2 text-xs font-black text-slate-700">
                                    {{ $job->status }}
                                </span>
                            </td>

                            <td class="py-4 text-sm font-bold text-slate-500">
                                {{ $job->created_at->format('M d, h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $jobs->links() }}
                </div>
            </div>
        </main>
    </div>
</x-kiosk-layout>
