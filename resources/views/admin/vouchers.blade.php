<x-kiosk-layout title="Vouchers">
    <style>
        .admin-panel-scroll {
            scrollbar-width: auto;
            scrollbar-color: rgba(71, 85, 105, 0.75) rgba(148, 163, 184, 0.15);
        }

        .admin-panel-scroll::-webkit-scrollbar {
            width: 22px;
        }

        .admin-panel-scroll::-webkit-scrollbar-track {
            background: rgba(148, 163, 184, 0.15);
            border-radius: 999px;
        }

        .admin-panel-scroll::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, 0.75);
            border-radius: 999px;
            border: 4px solid transparent;
            background-clip: content-box;
        }
    </style>

    <div class="h-full grid grid-cols-[240px_1fr] gap-4">
        @include('admin.partials.sidebar')

        <main class="admin-panel-scroll bg-white rounded-3xl p-5 shadow-xl overflow-y-auto">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-16 h-16 rounded-3xl bg-slate-950 text-white flex items-center justify-center shrink-0 shadow-xl">
                    <x-heroicon-o-ticket class="w-9 h-9" />
                </div>

                <div>
                    <h2 class="text-4xl font-black text-slate-950 leading-none mb-2">
                        Vouchers
                    </h2>

                    <p class="text-base text-slate-500 font-bold">
                        Create voucher codes that customers can redeem as kiosk credit.
                    </p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-2xl bg-emerald-100 text-emerald-800 p-4 text-base font-black">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-2xl bg-red-100 text-red-700 p-4 text-base font-black">
                    {{ $errors->first() }}
                </div>
            @endif

            <section class="rounded-3xl bg-slate-100 p-5 mb-4">
                <h3 class="text-2xl font-black text-slate-950 mb-4">
                    Create Voucher
                </h3>

                <form
                    method="POST"
                    action="{{ route('admin.vouchers.store') }}"
                    class="grid grid-cols-[1fr_320px] gap-5"
                >
                    @csrf

                    <div class="grid grid-cols-2 gap-4 content-start">
                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                            <label class="block text-xs font-black text-slate-500 uppercase mb-2">
                                Voucher Code
                            </label>

                            <input
                                id="voucherCodeInput"
                                type="text"
                                name="code"
                                value="{{ old('code') }}"
                                readonly
                                required
                                maxlength="12"
                                onclick="selectVoucherInput('voucherCodeInput', 'code')"
                                class="voucher-input w-full rounded-2xl bg-slate-100 px-4 h-16 text-4xl font-black text-slate-950 border-4 border-transparent cursor-pointer"
                                autocomplete="off"
                            >

                            <div class="text-sm font-bold text-slate-500 mt-2">
                                Numeric voucher code
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white p-4 shadow-sm">
                            <label class="block text-xs font-black text-slate-500 uppercase mb-2">
                                Amount
                            </label>

                            <input
                                id="voucherAmountInput"
                                type="text"
                                name="amount"
                                value="{{ old('amount') }}"
                                readonly
                                required
                                maxlength="4"
                                onclick="selectVoucherInput('voucherAmountInput', 'amount')"
                                class="voucher-input w-full rounded-2xl bg-slate-100 px-4 h-16 text-4xl font-black text-slate-950 border-4 border-transparent cursor-pointer"
                                autocomplete="off"
                            >

                            <div class="text-sm font-bold text-slate-500 mt-2">
                                kiosk credit amount
                            </div>
                        </div>

                        <div class="col-span-2 rounded-2xl bg-white p-4 shadow-sm">
                            <div class="text-xs font-black text-slate-500 uppercase mb-2">
                                Voucher Rule
                            </div>

                            <div class="text-2xl font-black text-slate-950">
                                Each voucher can only be redeemed once.
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="col-span-2 rounded-2xl bg-slate-950 text-white h-16 text-xl font-black shadow-lg active:scale-95 transition"
                        >
                            Create Voucher
                        </button>
                    </div>

                    <div class="rounded-3xl bg-white p-4 shadow-sm">
                        <div class="grid grid-cols-3 gap-2">
                            @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $number)
                                <button
                                    type="button"
                                    onclick="voucherKeyboardPress('{{ $number }}')"
                                    class="rounded-2xl bg-slate-950 text-white h-16 text-2xl font-black active:scale-95 transition"
                                >
                                    {{ $number }}
                                </button>
                            @endforeach

                            <button
                                type="button"
                                onclick="voucherKeyboardBackspace()"
                                class="rounded-2xl bg-red-100 text-red-700 h-16 text-base font-black active:scale-95 transition"
                            >
                                Delete
                            </button>

                            <button
                                type="button"
                                onclick="voucherKeyboardPress('0')"
                                class="rounded-2xl bg-slate-950 text-white h-16 text-2xl font-black active:scale-95 transition"
                            >
                                0
                            </button>

                            <button
                                type="button"
                                onclick="voucherKeyboardClear()"
                                class="rounded-2xl bg-slate-300 text-slate-950 h-16 text-base font-black active:scale-95 transition"
                            >
                                Clear
                            </button>
                        </div>
                    </div>
                </form>
            </section>

            <section class="rounded-3xl bg-slate-100 p-5 mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-black text-slate-950">
                        Voucher List
                    </h3>

                    <span class="rounded-full bg-slate-200 text-slate-700 px-4 py-2 text-xs font-black">
                        {{ $vouchers->total() }} Total
                    </span>
                </div>

                <div class="rounded-3xl bg-white p-4 shadow-sm overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                        <tr class="text-xs uppercase text-slate-500">
                            <th class="py-3 px-2">Code</th>
                            <th class="py-3 px-2">Amount</th>
                            <th class="py-3 px-2">Status</th>
                            <th class="py-3 px-2">Used At</th>
                            <th class="py-3 px-2">Created</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                        @forelse ($vouchers as $voucher)
                            <tr class="font-bold text-slate-800">
                                <td class="py-4 px-2 font-black">
                                    {{ $voucher->code }}
                                </td>

                                <td class="py-4 px-2">
                                    ₱{{ $voucher->amount }}
                                </td>

                                <td class="py-4 px-2">
                                    @if ($voucher->is_used)
                                        <span class="rounded-full bg-red-100 text-red-700 px-3 py-1 text-xs font-black">
                                                Used
                                            </span>
                                    @else
                                        <span class="rounded-full bg-emerald-100 text-emerald-700 px-3 py-1 text-xs font-black">
                                                Available
                                            </span>
                                    @endif
                                </td>

                                <td class="py-4 px-2 text-sm">
                                    {{ $voucher->used_at?->format('M d, Y h:i A') ?? '-' }}
                                </td>

                                <td class="py-4 px-2 text-sm">
                                    {{ $voucher->created_at?->format('M d, Y h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="5"
                                    class="py-8 text-center text-slate-500 font-black"
                                >
                                    No vouchers yet.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-5">
                        {{ $vouchers->links() }}
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        let activeVoucherInput = null;
        let activeVoucherMode = 'amount';

        function selectVoucherInput(inputId, mode) {
            activeVoucherInput = document.getElementById(inputId);
            activeVoucherMode = mode;

            document.querySelectorAll('.voucher-input').forEach((input) => {
                input.classList.remove(
                    'border-slate-950',
                    'bg-white',
                    'ring-4',
                    'ring-slate-300'
                );

                input.classList.add(
                    'border-transparent',
                    'bg-slate-100'
                );
            });

            activeVoucherInput.classList.remove(
                'border-transparent',
                'bg-slate-100'
            );

            activeVoucherInput.classList.add(
                'border-slate-950',
                'bg-white',
                'ring-4',
                'ring-slate-300'
            );
        }

        function voucherKeyboardPress(value) {
            if (! activeVoucherInput) {
                return;
            }

            if (! /^[0-9]$/.test(value)) {
                return;
            }

            if (
                activeVoucherMode === 'code' &&
                activeVoucherInput.value.length >= 12
            ) {
                return;
            }

            if (
                activeVoucherMode === 'amount' &&
                activeVoucherInput.value.length >= 4
            ) {
                return;
            }

            if (
                activeVoucherMode === 'amount' &&
                activeVoucherInput.value === '' &&
                value === '0'
            ) {
                return;
            }

            activeVoucherInput.value += value;
        }

        function voucherKeyboardBackspace() {
            if (! activeVoucherInput) {
                return;
            }

            activeVoucherInput.value =
                activeVoucherInput.value.slice(0, -1);
        }

        function voucherKeyboardClear() {
            if (! activeVoucherInput) {
                return;
            }

            activeVoucherInput.value = '';
        }

        document.addEventListener('DOMContentLoaded', () => {
            selectVoucherInput(
                'voucherCodeInput',
                'code'
            );
        });
    </script>
</x-kiosk-layout>
