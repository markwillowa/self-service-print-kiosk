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
                        ₱{{ $totalCredits }}
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
                    @foreach ($transactions as $transaction)
                        <tr class="border-b last:border-b-0">
                            <td class="py-4 text-base font-bold text-slate-900">
                                ₱{{ $transaction->amount }}
                            </td>

                            <td class="py-4 text-base font-bold text-slate-900">
                                {{ $transaction->source }}
                            </td>

                            <td class="py-4 text-sm font-bold text-slate-500">
                                {{ $transaction->created_at->format('M d, h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </main>
    </div>
</x-kiosk-layout>
