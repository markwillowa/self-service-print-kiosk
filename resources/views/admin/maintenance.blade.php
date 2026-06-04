<x-kiosk-layout title="Maintenance">
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

        .modal-scroll {
            scrollbar-width: auto;
            scrollbar-color: rgba(71, 85, 105, 0.90) rgba(148, 163, 184, 0.20);
        }

        .modal-scroll::-webkit-scrollbar {
            width: 22px;
        }

        .modal-scroll::-webkit-scrollbar-track {
            background: rgba(148, 163, 184, 0.20);
            border-radius: 999px;
        }

        .modal-scroll::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, 0.90);
            border-radius: 999px;
            border: 3px solid transparent;
            background-clip: content-box;
        }
    </style>

    <div class="h-full grid grid-cols-[240px_1fr] gap-4">
        @include('admin.partials.sidebar')

        <main class="bg-white rounded-3xl p-5 shadow-xl overflow-hidden flex flex-col min-h-0">
            <div class="flex items-center justify-between mb-5 shrink-0">
                <div>
                    <h2 class="text-4xl font-black text-slate-950 leading-none mb-2">
                        Maintenance
                    </h2>

                    <p class="text-base text-slate-500 font-bold">
                        Staff visits and service records
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        onclick="openSystemUpdateModal()"
                        class="rounded-2xl bg-blue-600 text-white px-6 h-14 text-base font-black shadow-lg active:scale-95 transition"
                    >
                        System Update
                    </button>

                    <button
                        type="button"
                        onclick="openMaintenanceModal()"
                        class="rounded-2xl bg-slate-950 text-white px-6 h-14 text-base font-black shadow-lg active:scale-95 transition"
                    >
                        Add Record
                    </button>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-2xl bg-emerald-100 text-emerald-800 p-4 text-base font-black shrink-0">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('system_update'))
                <div class="mb-4 rounded-2xl bg-blue-100 text-blue-800 p-4 text-base font-black shrink-0">
                    {{ session('system_update') }}

                    @if (str_contains(session('system_update'), 'completed'))
                        <form
                            method="POST"
                            action="{{ route('admin.system-reboot') }}"
                            class="mt-3"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="rounded-2xl bg-red-600 text-white px-6 h-14 text-base font-black shadow-lg active:scale-95 transition"
                            >
                                Restart Device Now
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            @if ($errors->has('system_update'))
                <div class="mb-4 rounded-2xl bg-red-100 text-red-700 p-4 text-base font-black shrink-0">
                    {{ $errors->first('system_update') }}
                </div>
            @endif

            <div class="admin-scroll flex-1 min-h-0 overflow-y-auto pr-2">
                <table class="w-full text-left">
                    <thead class="sticky top-0 bg-white z-10">
                    <tr class="border-b text-xs text-slate-500 uppercase">
                        <th class="py-3">Action</th>
                        <th class="py-3">Type</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Issue</th>
                        <th class="py-3">Cost</th>
                        <th class="py-3">Performed</th>
                        <th class="py-3">Next</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($maintenances as $maintenance)
                        <tr class="border-b last:border-b-0">
                            <td class="py-4">
                                <a
                                    href="{{ route('admin.maintenance.report', $maintenance) }}"
                                    target="_blank"
                                    class="inline-flex rounded-2xl bg-slate-950 text-white px-4 py-3 text-sm font-black shadow active:scale-95 transition"
                                >
                                    Print
                                </a>
                            </td>

                            <td class="py-4 text-base font-black text-slate-900">
                                {{ $maintenance->maintenance_type }}
                            </td>

                            <td class="py-4">
                                <span class="rounded-full bg-slate-100 px-3 py-2 text-xs font-black text-slate-700">
                                    {{ $maintenance->status }}
                                </span>
                            </td>

                            <td class="py-4 text-sm font-bold text-slate-600 max-w-[260px] truncate">
                                {{ $maintenance->issue_reported ?: 'No issue reported' }}
                            </td>

                            <td class="py-4 text-base font-bold">
                                ₱{{ $maintenance->cost }}
                            </td>

                            <td class="py-4 text-sm font-bold text-slate-500">
                                {{ $maintenance->performed_at?->format('M d, Y') ?? '-' }}
                            </td>

                            <td class="py-4 text-sm font-bold text-slate-500">
                                {{ $maintenance->next_maintenance_at?->format('M d, Y') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="7"
                                class="py-12 text-center text-slate-400 font-black text-lg"
                            >
                                No maintenance records yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $maintenances->links() }}
                </div>
            </div>
        </main>
    </div>

    <div
        id="systemUpdateModal"
        class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center p-4"
    >
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl text-center">
            <h3 class="text-3xl font-black text-slate-950 mb-3">
                Run System Update?
            </h3>

            <p class="text-base text-slate-500 font-bold mb-6">
                This will pull the latest code, run migrations, rebuild assets, and may require a device restart.
            </p>

            <div class="grid grid-cols-2 gap-3">
                <button
                    type="button"
                    onclick="closeSystemUpdateModal()"
                    class="rounded-2xl bg-slate-200 text-slate-900 px-4 py-4 text-base font-black active:scale-95 transition"
                >
                    Cancel
                </button>

                <form
                    method="POST"
                    action="{{ route('admin.system-update') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-blue-600 text-white px-4 py-4 text-base font-black active:scale-95 transition"
                    >
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div
        id="maintenanceModal"
        class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center p-4"
    >
        <div class="w-full max-w-[980px] max-h-[640px] rounded-3xl bg-white shadow-2xl overflow-hidden flex flex-col">
            <div class="flex items-center justify-between p-6 pb-4 shrink-0">
                <div>
                    <h3 class="text-3xl font-black text-slate-950 leading-none mb-2">
                        Add Maintenance Record
                    </h3>

                    <p class="text-sm text-slate-500 font-bold">
                        Record staff visit, repair, cleaning, or inspection.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="closeMaintenanceModal()"
                    class="w-12 h-12 rounded-2xl bg-slate-100 text-2xl font-black text-slate-900 active:scale-95 transition"
                >
                    ✕
                </button>
            </div>

            @if ($errors->any() && ! $errors->has('system_update'))
                <div class="mx-6 mb-4 rounded-2xl bg-red-100 text-red-700 p-4 text-base font-black shrink-0">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="modal-scroll flex-1 min-h-0 overflow-y-auto px-6 pb-6 pr-4">
                <form
                    method="POST"
                    action="{{ route('admin.maintenance.store') }}"
                    class="grid grid-cols-2 gap-4"
                >
                    @csrf

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Maintenance Type
                        </label>

                        <select
                            name="maintenance_type"
                            required
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
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
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Status
                        </label>

                        <select
                            name="status"
                            required
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
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
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Performed At
                        </label>

                        <input
                            type="date"
                            name="performed_at"
                            value="{{ old('performed_at', now()->format('Y-m-d')) }}"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Next Maintenance
                        </label>

                        <input
                            type="date"
                            name="next_maintenance_at"
                            value="{{ old('next_maintenance_at') }}"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Cost
                        </label>

                        <input
                            type="number"
                            name="cost"
                            value="{{ old('cost', 0) }}"
                            min="0"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Printer Status
                        </label>

                        <input
                            type="text"
                            name="printer_status"
                            value="{{ old('printer_status') }}"
                            placeholder="Good / Needs cleaning / Error"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Coin Acceptor Status
                        </label>

                        <input
                            type="text"
                            name="coin_acceptor_status"
                            value="{{ old('coin_acceptor_status') }}"
                            placeholder="Working / Needs calibration"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Paper Stock
                        </label>

                        <input
                            type="text"
                            name="paper_stock"
                            value="{{ old('paper_stock') }}"
                            placeholder="Full / Half / Low"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Ink Status
                        </label>

                        <input
                            type="text"
                            name="ink_status"
                            value="{{ old('ink_status') }}"
                            placeholder="Good / Low / Replaced"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Network Status
                        </label>

                        <input
                            type="text"
                            name="network_status"
                            value="{{ old('network_status') }}"
                            placeholder="Online / Offline / Weak"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Issue Reported
                        </label>

                        <textarea
                            name="issue_reported"
                            rows="3"
                            class="w-full rounded-2xl bg-slate-100 px-4 py-3 text-lg font-bold resize-none"
                        >{{ old('issue_reported') }}</textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Action Taken
                        </label>

                        <textarea
                            name="action_taken"
                            rows="3"
                            class="w-full rounded-2xl bg-slate-100 px-4 py-3 text-lg font-bold resize-none"
                        >{{ old('action_taken') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Parts Replaced
                        </label>

                        <textarea
                            name="parts_replaced"
                            rows="3"
                            class="w-full rounded-2xl bg-slate-100 px-4 py-3 text-lg font-bold resize-none"
                        >{{ old('parts_replaced') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Notes
                        </label>

                        <textarea
                            name="notes"
                            rows="3"
                            class="w-full rounded-2xl bg-slate-100 px-4 py-3 text-lg font-bold resize-none"
                        >{{ old('notes') }}</textarea>
                    </div>

                    <button
                        type="button"
                        onclick="closeMaintenanceModal()"
                        class="rounded-2xl bg-slate-200 text-slate-900 h-14 text-lg font-black active:scale-95 transition"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="rounded-2xl bg-slate-950 text-white h-14 text-lg font-black shadow-lg active:scale-95 transition"
                    >
                        Save Record
                    </button>
                </form>
            </div>
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

        function openSystemUpdateModal() {
            const modal = document.getElementById(
                'systemUpdateModal'
            );

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeSystemUpdateModal() {
            const modal = document.getElementById(
                'systemUpdateModal'
            );

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        @if ($errors->any() && ! $errors->has('system_update'))
        openMaintenanceModal();
        @endif
    </script>
</x-kiosk-layout>
