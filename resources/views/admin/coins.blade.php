<x-kiosk-layout title="Coins">
    <div class="h-full grid grid-cols-[180px_1fr] gap-3">
        @include('admin.partials.sidebar')

        <main class="bg-white rounded-2xl p-3 shadow-sm overflow-y-auto">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-3xl font-black text-slate-950">
                        Coins & Credits
                    </h2>

                    <p class="text-sm text-slate-500 font-bold">
                        Coin transaction history
                    </p>
                </div>

                <div class="rounded-xl bg-emerald-100 px-4 py-3">
                    <div class="text-[10px] uppercase font-black text-emerald-700">
                        Total Credits
                    </div>

                    <div class="text-2xl font-black text-emerald-900">
                        ₱{{ $totalCredits }}
                    </div>
                </div>
            </div>

            <table class="w-full text-left">
                <thead>
                <tr class="border-b text-[10px] text-slate-500 uppercase">
                    <th class="py-2">Amount</th>
                    <th class="py-2">Source</th>
                    <th class="py-2">Date</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($transactions as $transaction)
                    <tr class="border-b">
                        <td class="py-2 text-sm font-bold">
                            ₱{{ $transaction->amount }}
                        </td>

                        <td class="py-2 text-sm font-bold">
                            {{ $transaction->source }}
                        </td>

                        <td class="py-2 text-xs font-bold text-slate-500">
                            {{ $transaction->created_at->format('M d, h:i A') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $transactions->links() }}
            </div>
        </main>
    </div>
</x-kiosk-layout>
