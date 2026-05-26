<aside class="bg-slate-950 text-white rounded-2xl p-3 flex flex-col">
    <h1 class="text-2xl font-black mb-4">
        Admin
    </h1>

    <nav class="space-y-2 flex-1">
        <a
            href="{{ route('admin.dashboard') }}"
            class="block rounded-xl px-3 py-3 text-sm font-black {{ request()->routeIs('admin.dashboard') ? 'bg-white text-slate-950' : 'bg-slate-800 text-white' }}"
        >
            Dashboard
        </a>

        <a
            href="{{ route('admin.print-jobs') }}"
            class="block rounded-xl px-3 py-3 text-sm font-black {{ request()->routeIs('admin.print-jobs') ? 'bg-white text-slate-950' : 'bg-slate-800 text-white' }}"
        >
            Print Jobs
        </a>

        <a
            href="{{ route('admin.coins') }}"
            class="block rounded-xl px-3 py-3 text-sm font-black {{ request()->routeIs('admin.coins') ? 'bg-white text-slate-950' : 'bg-slate-800 text-white' }}"
        >
            Coins
        </a>

        <a
            href="{{ route('admin.logs') }}"
            class="block rounded-xl px-3 py-3 text-sm font-black {{ request()->routeIs('admin.logs') ? 'bg-white text-slate-950' : 'bg-slate-800 text-white' }}"
        >
            Logs
        </a>

        <a
            href="{{ route('admin.maintenance') }}"
            class="block rounded-xl px-3 py-3 text-sm font-black {{ request()->routeIs('admin.maintenance') ? 'bg-white text-slate-950' : 'bg-slate-800 text-white' }}"
        >
            Maintenance
        </a>

        <a
            href="{{ route('admin.profile') }}"
            class="block rounded-xl px-3 py-3 text-sm font-black {{ request()->routeIs('admin.profile') ? 'bg-white text-slate-950' : 'bg-slate-800 text-white' }}"
        >
            Profile
        </a>

        @if (session('admin_username') === 'admin')
            <a
                href="{{ route('admin.users') }}"
                class="block rounded-xl px-3 py-3 text-sm font-black {{ request()->routeIs('admin.users*') ? 'bg-white text-slate-950' : 'bg-slate-800 text-white' }}"
            >
                Users
            </a>
        @endif

        <a
            href="{{ route('admin.settings') }}"
            class="block rounded-xl px-3 py-3 text-sm font-black {{ request()->routeIs('admin.settings') ? 'bg-white text-slate-950' : 'bg-slate-800 text-white' }}"
        >
            Settings
        </a>
    </nav>

    <form
        method="POST"
        action="{{ route('admin.logout') }}"
    >
        @csrf

        <button
            type="submit"
            class="w-full rounded-xl bg-red-500 text-white px-3 py-3 text-sm font-black"
        >
            Logout
        </button>
    </form>
</aside>
