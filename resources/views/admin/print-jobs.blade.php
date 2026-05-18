<x-kiosk-layout title="Print Jobs">
    <div class="h-full grid grid-cols-[180px_1fr] gap-3">
        @include('admin.partials.sidebar')

        <main class="bg-white rounded-2xl p-3 shadow-sm overflow-y-auto">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-3xl font-black text-slate-950">
                        Print Jobs
                    </h2>

                    <p class="text-sm text-slate-500 font-bold">
                        Latest kiosk print requests
                    </p>
                </div>
            </div>

            <table class="w-full text-left">
                <thead>
                <tr class="border-b text-[10px] text-slate-500 uppercase">
                    <th class="py-2">File</th>
                    <th class="py-2">Pages</th>
                    <th class="py-2">Amount</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Created</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($jobs as $job)
                    <tr class="border-b">
                        <td class="py-2 text-sm font-bold max-w-[240px] truncate">
                            {{ $job->original_filename }}
                        </td>

                        <td class="py-2 text-sm font-bold">
                            {{ $job->pages }}
                        </td>

                        <td class="py-2 text-sm font-bold">
                            ₱{{ $job->paid_amount }}
                        </td>

                        <td class="py-2">
                            <span class="rounded-full bg-slate-100 px-2 py-1 text-[11px] font-black">
                                {{ $job->status }}
                            </span>
                        </td>

                        <td class="py-2 text-xs font-bold text-slate-500">
                            {{ $job->created_at->format('M d, h:i A') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $jobs->links() }}
            </div>
        </main>
    </div>
</x-kiosk-layout>
