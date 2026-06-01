<x-kiosk-layout title="Coins">
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
                        Coins & Credits
                    </h2>

                    <p class="text-base text-slate-500 font-bold">
                        Coin transaction history
                    </p>
                </div>

                <div class="rounded-3xl bg-emerald-100 px-5 py-4 shadow-sm">
                    <div class="text-xs uppercase font-black text-emerald-700 mb-1">
                        Total Credits
                    </div>

                    <div class="text-4xl font-black text-emerald-900 leading-none">
                        ₱{{ number_format($totalCredits, 2) }}
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4 shrink-0">
                <h3 class="text-2xl font-black text-slate-950">
                    Transactions
                </h3>

                <div class="rounded-2xl bg-slate-100 px-4 py-3">
                    <div class="text-xs font-black uppercase text-slate-500">
                        Total Records
                    </div>

                    <div class="text-2xl font-black text-slate-950">
                        {{ $transactions->total() }}
                    </div>
                </div>
            </div>

            <div class="admin-scroll flex-1 min-h-0 overflow-y-auto pr-2">
                <table class="w-full text-left">
                    <thead class="sticky top-0 bg-white z-10">
                    <tr class="border-b text-xs text-slate-500 uppercase">
                        <th class="py-3">Amount</th>
                        <th class="py-3">Source</th>
                        <th class="py-3">Date</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="border-b last:border-b-0">
                            <td class="py-4 text-base font-bold text-emerald-700">
                                ₱{{ number_format($transaction->amount, 2) }}
                            </td>

                            <td class="py-4 text-base font-bold text-slate-900">
                                {{ $transaction->source }}
                            </td>

                            <td class="py-4 text-sm font-bold text-slate-500">
                                {{ $transaction->created_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="3"
                                class="py-16 text-center text-slate-400 font-black text-lg"
                            >
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-5 border-t border-slate-200 mt-4 shrink-0">
                <div class="flex items-center justify-between">
                    <div class="text-base font-black text-slate-500">
                        Page {{ $transactions->currentPage() }}
                        of
                        {{ $transactions->lastPage() }}
                    </div>

                    <div class="flex items-center gap-3">
                        @if ($transactions->onFirstPage())
                            <span class="rounded-2xl bg-slate-100 text-slate-400 px-6 py-3 text-base font-black">
                                Previous
                            </span>
                        @else
                            <a
                                href="{{ $transactions->previousPageUrl() }}"
                                class="rounded-2xl bg-slate-200 text-slate-900 px-6 py-3 text-base font-black active:scale-95 transition"
                            >
                                Previous
                            </a>
                        @endif

                        @if ($transactions->hasMorePages())
                            <a
                                href="{{ $transactions->nextPageUrl() }}"
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
