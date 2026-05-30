<x-kiosk-layout title="Laravel Logs">
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
            <div class="flex items-center justify-between mb-5 shrink-0 gap-4">
                <div>
                    <h2 class="text-4xl font-black text-slate-950 leading-none mb-2">
                        Log Activities
                    </h2>

                    <p class="text-base text-slate-500 font-bold">
                        Latest application logs
                    </p>
                </div>

                <div class="flex gap-3 shrink-0">
                    <button
                        onclick="window.location.reload()"
                        class="rounded-2xl bg-slate-950 text-white px-6 h-14 text-base font-black shadow-lg active:scale-95 transition"
                    >
                        Refresh
                    </button>

                    <button
                        type="button"
                        onclick="openClearLogsModal()"
                        class="rounded-2xl bg-red-500 text-white px-6 h-14 text-base font-black shadow-lg active:scale-95 transition"
                    >
                        Clear
                    </button>
                </div>
            </div>

            <div class="admin-scroll flex-1 min-h-0 overflow-y-auto rounded-3xl bg-slate-950 p-4 pr-2">
                @forelse ($logs as $log)
                    <div class="font-mono text-sm text-emerald-400 break-words border-b border-slate-800 py-2 pr-3">
                        {{ $log }}
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center text-slate-400 font-black text-xl">
                        No logs found.
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    <div
        id="clearLogsModal"
        class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center p-4"
    >
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl text-center">
            <div class="w-20 h-20 rounded-3xl bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-5">
                <span class="text-5xl font-black">
                    !
                </span>
            </div>

            <h3 class="text-3xl font-black text-slate-950 mb-3">
                Clear Logs?
            </h3>

            <p class="text-base text-slate-500 font-bold mb-6">
                This will empty all Laravel log files.
                This action cannot be undone.
            </p>

            <div class="grid grid-cols-2 gap-3">
                <button
                    type="button"
                    onclick="closeClearLogsModal()"
                    class="rounded-2xl bg-slate-200 text-slate-900 px-4 py-4 text-base font-black active:scale-95 transition"
                >
                    Cancel
                </button>

                <form
                    method="POST"
                    action="{{ route('admin.logs.clear') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-red-500 text-white px-4 py-4 text-base font-black active:scale-95 transition"
                    >
                        Clear
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openClearLogsModal() {
            const modal = document.getElementById(
                'clearLogsModal'
            );

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeClearLogsModal() {
            const modal = document.getElementById(
                'clearLogsModal'
            );

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</x-kiosk-layout>
