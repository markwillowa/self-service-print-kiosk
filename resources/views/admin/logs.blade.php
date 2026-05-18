<x-kiosk-layout title="Laravel Logs">
    <div class="h-full grid grid-cols-[180px_1fr] gap-3">
        @include('admin.partials.sidebar')

        <main class="bg-white rounded-2xl p-3 shadow-sm overflow-hidden flex flex-col">
            <div class="flex items-center justify-between mb-3 shrink-0 gap-3">
                <div>
                    <h2 class="text-3xl font-black text-slate-950">
                        Log Activities
                    </h2>

                    <p class="text-sm text-slate-500 font-bold">
                        Latest application logs
                    </p>
                </div>

                <div class="flex gap-2">
                    <button
                        onclick="window.location.reload()"
                        class="rounded-xl bg-slate-950 text-white px-4 py-2 text-sm font-black"
                    >
                        Refresh
                    </button>

                    <button
                        type="button"
                        onclick="openClearLogsModal()"
                        class="rounded-xl bg-red-500 text-white px-4 py-2 text-sm font-black"
                    >
                        Clear
                    </button>
                </div>
            </div>

            <div class="flex-1 min-h-0 overflow-y-auto rounded-2xl bg-slate-950 p-3">
                @forelse ($logs as $log)
                    <div class="font-mono text-[11px] text-emerald-400 break-words border-b border-slate-800 py-1">
                        {{ $log }}
                    </div>
                @empty
                    <div class="text-slate-400 font-black text-center py-10">
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
        <div class="w-full max-w-sm rounded-2xl bg-white p-5 shadow-2xl text-center">
            <div class="w-16 h-16 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                <span class="text-4xl font-black">
                    !
                </span>
            </div>

            <h3 class="text-2xl font-black text-slate-950 mb-2">
                Clear Logs?
            </h3>

            <p class="text-sm text-slate-500 font-bold mb-5">
                This will empty all Laravel log files.
                This action cannot be undone.
            </p>

            <div class="grid grid-cols-2 gap-2">
                <button
                    type="button"
                    onclick="closeClearLogsModal()"
                    class="rounded-xl bg-slate-200 text-slate-900 px-4 py-3 text-sm font-black"
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
                        class="w-full rounded-xl bg-red-500 text-white px-4 py-3 text-sm font-black"
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
