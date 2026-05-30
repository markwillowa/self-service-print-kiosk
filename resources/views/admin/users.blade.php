<x-kiosk-layout title="Users">
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
                        Users
                    </h2>

                    <p class="text-base text-slate-500 font-bold">
                        Manage kiosk administrators
                    </p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-2xl bg-emerald-100 text-emerald-700 px-5 py-4 text-base font-black shrink-0">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-2xl bg-red-100 text-red-700 px-5 py-4 text-base font-black shrink-0">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="admin-scroll flex-1 min-h-0 overflow-y-auto pr-2">
                <form
                    method="POST"
                    action="{{ route('admin.users.store') }}"
                    class="grid grid-cols-2 gap-4 mb-5"
                >
                    @csrf

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            required
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            required
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            PIN Code
                        </label>

                        <input
                            type="password"
                            name="pin_code"
                            required
                            maxlength="6"
                            class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-bold"
                        >
                    </div>

                    <button
                        type="submit"
                        class="col-span-2 rounded-2xl bg-slate-950 text-white h-14 text-lg font-black shadow-lg active:scale-95 transition"
                    >
                        Create User
                    </button>
                </form>

                <div class="rounded-3xl overflow-hidden border border-slate-200">
                    <table class="w-full text-left">
                        <thead class="bg-slate-100 sticky top-0 z-10">
                        <tr>
                            <th class="px-5 py-4 text-sm font-black uppercase text-slate-500">
                                Name
                            </th>

                            <th class="px-5 py-4 text-sm font-black uppercase text-slate-500">
                                Username
                            </th>

                            <th class="px-5 py-4 text-sm font-black uppercase text-slate-500">
                                Role
                            </th>

                            <th class="px-5 py-4 text-sm font-black uppercase text-slate-500">
                                Created
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse ($users as $user)
                            <tr class="border-t border-slate-200">
                                <td class="px-5 py-4 text-base font-black text-slate-950">
                                    {{ $user->name }}
                                </td>

                                <td class="px-5 py-4 text-base font-bold text-slate-700">
                                    {{ $user->username }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($user->is_super_admin)
                                        <span class="rounded-full bg-emerald-100 text-emerald-700 px-3 py-2 text-xs font-black">
                                            Super Admin
                                        </span>
                                    @else
                                        <span class="rounded-full bg-slate-100 text-slate-700 px-3 py-2 text-xs font-black">
                                            Admin
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-sm font-bold text-slate-500">
                                    {{ $user->created_at->format('M d, Y h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="4"
                                    class="px-5 py-12 text-center text-lg font-black text-slate-400"
                                >
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</x-kiosk-layout>
