<x-kiosk-layout title="Maintenance">
    <div class="h-full grid grid-cols-[180px_1fr] gap-3">
        @include('admin.partials.sidebar')

        <main class="bg-white rounded-2xl p-3 shadow-sm overflow-hidden flex flex-col">
            <div class="flex items-center justify-between mb-3 shrink-0">
                <div>
                    <h2 class="text-3xl font-black text-slate-950">
                        Maintenance
                    </h2>

                    <p class="text-sm text-slate-500 font-bold">
                        Staff visits and service records
                    </p>
                </div>

                <button
                    type="button"
                    onclick="openMaintenanceModal()"
                    class="rounded-xl bg-slate-950 text-white px-4 py-2 text-sm font-black"
                >
                    Add Record
                </button>
            </div>

            @if (session('success'))
                <div class="mb-3 rounded-xl bg-emerald-100 text-emerald-800 p-3 text-sm font-black">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex-1 min-h-0 overflow-y-auto">
                <table class="w-full text-left">
                    <thead>
                    <tr class="border-b text-[10px] text-slate-500 uppercase">
                        <th class="py-2">Action</th>
                        <th class="py-2">Type</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Issue</th>
                        <th class="py-2">Cost</th>
                        <th class="py-2">Performed</th>
                        <th class="py-2">Next</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($maintenances as $maintenance)
                        <tr class="border-b last:border-b-0">
                            <td class="py-2">
                                <a
                                    href="{{ route('admin.maintenance.report', $maintenance) }}"
                                    target="_blank"
                                    class="inline-flex rounded-xl bg-slate-950 text-white px-3 py-2 text-xs font-black"
                                >
                                    Print
                                </a>
                            </td>
                            <td class="py-2 text-sm font-black text-slate-900">
                                {{ $maintenance->maintenance_type }}
                            </td>

                            <td class="py-2">
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-[11px] font-black text-slate-700">
                                    {{ $maintenance->status }}
                                </span>
                            </td>

                            <td class="py-2 text-xs font-bold text-slate-600 max-w-[220px] truncate">
                                {{ $maintenance->issue_reported ?: 'No issue reported' }}
                            </td>

                            <td class="py-2 text-sm font-bold">
                                ₱{{ $maintenance->cost }}
                            </td>

                            <td class="py-2 text-xs font-bold text-slate-500">
                                {{ $maintenance->performed_at?->format('M d, Y') ?? '-' }}
                            </td>

                            <td class="py-2 text-xs font-bold text-slate-500">
                                {{ $maintenance->next_maintenance_at?->format('M d, Y') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="6"
                                class="py-8 text-center text-slate-400 font-black"
                            >
                                No maintenance records yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $maintenances->links() }}
                </div>
            </div>
        </main>
    </div>

    <div
        id="maintenanceModal"
        class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center p-3"
    >
        <div class="w-full max-w-[720px] max-h-[455px] overflow-y-auto rounded-2xl bg-white p-4 shadow-2xl">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-2xl font-black text-slate-950">
                        Add Maintenance Record
                    </h3>

                    <p class="text-xs text-slate-500 font-bold">
                        Record staff visit, repair, cleaning, or inspection.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="closeMaintenanceModal()"
                    class="w-10 h-10 rounded-xl bg-slate-100 text-xl font-black text-slate-900"
                >
                    ✕
                </button>
            </div>

            @if ($errors->any())
                <div class="mb-3 rounded-xl bg-red-100 text-red-700 p-3 text-sm font-black">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('admin.maintenance.store') }}"
                class="grid grid-cols-2 gap-3"
            >
                @csrf

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Maintenance Type
                    </label>

                    <select
                        name="maintenance_type"
                        required
                        class="w-full rounded-xl bg-slate-100 px-3 h-11 text-sm font-black"
                    >
                        @foreach ([
                            'Preventive Maintenance',
                            'Repair',
                            'Inspection',
                            'Cleaning',
                            'Software Update',
                            'Hardware Replacement',
                            'Emergency Service',
                        ] as $type)
                            <option value="{{ $type }}">
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Status
                    </label>

                    <select
                        name="status"
                        required
                        class="w-full rounded-xl bg-slate-100 px-3 h-11 text-sm font-black"
                    >
                        @foreach ([
                            'Pending',
                            'Ongoing',
                            'Completed',
                            'Cancelled',
                        ] as $status)
                            <option value="{{ $status }}">
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Performed At
                    </label>

                    <input
                        type="date"
                        name="performed_at"
                        value="{{ old('performed_at', now()->format('Y-m-d')) }}"
                        class="w-full rounded-xl bg-slate-100 px-3 h-11 text-sm font-bold"
                    >
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Next Maintenance
                    </label>

                    <input
                        type="date"
                        name="next_maintenance_at"
                        value="{{ old('next_maintenance_at') }}"
                        class="w-full rounded-xl bg-slate-100 px-3 h-11 text-sm font-bold"
                    >
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Cost
                    </label>

                    <input
                        type="number"
                        name="cost"
                        value="{{ old('cost', 0) }}"
                        min="0"
                        class="w-full rounded-xl bg-slate-100 px-3 h-11 text-sm font-bold"
                    >
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Printer Status
                    </label>

                    <input
                        type="text"
                        name="printer_status"
                        value="{{ old('printer_status') }}"
                        placeholder="Good / Needs cleaning / Error"
                        class="w-full rounded-xl bg-slate-100 px-3 h-11 text-sm font-bold"
                    >
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Coin Acceptor Status
                    </label>

                    <input
                        type="text"
                        name="coin_acceptor_status"
                        value="{{ old('coin_acceptor_status') }}"
                        placeholder="Working / Needs calibration"
                        class="w-full rounded-xl bg-slate-100 px-3 h-11 text-sm font-bold"
                    >
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Paper Stock
                    </label>

                    <input
                        type="text"
                        name="paper_stock"
                        value="{{ old('paper_stock') }}"
                        placeholder="Full / Half / Low"
                        class="w-full rounded-xl bg-slate-100 px-3 h-11 text-sm font-bold"
                    >
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Ink Status
                    </label>

                    <input
                        type="text"
                        name="ink_status"
                        value="{{ old('ink_status') }}"
                        placeholder="Good / Low / Replaced"
                        class="w-full rounded-xl bg-slate-100 px-3 h-11 text-sm font-bold"
                    >
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Network Status
                    </label>

                    <input
                        type="text"
                        name="network_status"
                        value="{{ old('network_status') }}"
                        placeholder="Online / Offline / Weak"
                        class="w-full rounded-xl bg-slate-100 px-3 h-11 text-sm font-bold"
                    >
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Issue Reported
                    </label>

                    <textarea
                        name="issue_reported"
                        rows="2"
                        class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold resize-none"
                    >{{ old('issue_reported') }}</textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Action Taken
                    </label>

                    <textarea
                        name="action_taken"
                        rows="2"
                        class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold resize-none"
                    >{{ old('action_taken') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Parts Replaced
                    </label>

                    <textarea
                        name="parts_replaced"
                        rows="2"
                        class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold resize-none"
                    >{{ old('parts_replaced') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        rows="2"
                        class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold resize-none"
                    >{{ old('notes') }}</textarea>
                </div>

                <button
                    type="button"
                    onclick="closeMaintenanceModal()"
                    class="rounded-xl bg-slate-200 text-slate-900 h-12 text-base font-black"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-slate-950 text-white h-12 text-base font-black"
                >
                    Save Record
                </button>
            </form>
        </div>
    </div>

    <script>
        function openMaintenanceModal() {
            const modal = document.getElementById(
                'maintenanceModal'
            );

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeMaintenanceModal() {
            const modal = document.getElementById(
                'maintenanceModal'
            );

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        @if ($errors->any())
        openMaintenanceModal();
        @endif
    </script>
</x-kiosk-layout>
