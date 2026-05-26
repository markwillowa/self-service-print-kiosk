<x-kiosk-layout title="Users">
    <div class="h-full grid grid-cols-[180px_1fr] gap-3">
        @include('admin.partials.sidebar')

        <main class="bg-white rounded-2xl p-4 shadow-sm overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-3xl font-black text-slate-950 leading-none">
                        Users
                    </h2>

                    <p class="text-sm text-slate-500 font-bold">
                        Manage kiosk administrators
                    </p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-xl bg-emerald-100 text-emerald-700 px-4 py-3 text-sm font-black">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-red-100 text-red-700 px-4 py-3 text-sm font-black">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('admin.users.store') }}"
                class="grid grid-cols-2 gap-3 mb-4"
            >
                @csrf

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        required
                        class="w-full rounded-xl bg-slate-100 px-3 py-3 text-sm font-bold"
                    >
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        required
                        class="w-full rounded-xl bg-slate-100 px-3 py-3 text-sm font-bold"
                    >
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-xl bg-slate-100 px-3 py-3 text-sm font-bold"
                    >
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        PIN Code
                    </label>

                    <input
                        type="password"
                        name="pin_code"
                        required
                        maxlength="6"
                        class="w-full rounded-xl bg-slate-100 px-3 py-3 text-sm font-bold"
                    >
                </div>

                <button
                    type="submit"
                    class="col-span-2 rounded-xl bg-slate-950 text-white py-3 text-sm font-black active:scale-95 transition"
                >
                    Create User
                </button>
            </form>

            <div class="rounded-2xl overflow-hidden border border-slate-200">
                <table class="w-full text-left">
                    <thead class="bg-slate-100">
                    <tr>
                        <th class="px-4 py-3 text-xs font-black uppercase text-slate-500">
                            Name
                        </th>

                        <th class="px-4 py-3 text-xs font-black uppercase text-slate-500">
                            Username
                        </th>

                        <th class="px-4 py-3 text-xs font-black uppercase text-slate-500">
                            Role
                        </th>

                        <th class="px-4 py-3 text-xs font-black uppercase text-slate-500">
                            Created
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($users as $user)
                        <tr class="border-t border-slate-200">
                            <td class="px-4 py-3 text-sm font-black text-slate-950">
                                {{ $user->name }}
                            </td>

                            <td class="px-4 py-3 text-sm font-bold text-slate-700">
                                {{ $user->username }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($user->is_super_admin)
                                    <span class="rounded-full bg-emerald-100 text-emerald-700 px-2 py-1 text-xs font-black">
                                        Super Admin
                                    </span>
                                @else
                                    <span class="rounded-full bg-slate-100 text-slate-700 px-2 py-1 text-xs font-black">
                                        Admin
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-xs font-bold text-slate-500">
                                {{ $user->created_at->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="4"
                                class="px-4 py-10 text-center text-sm font-black text-slate-400"
                            >
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-kiosk-layout>
