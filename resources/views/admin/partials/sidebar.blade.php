<aside class="bg-slate-950 text-white rounded-3xl p-4 flex flex-col shadow-xl">
    <div class="mb-5">
        <h1 class="text-3xl font-black leading-none">
            Admin
        </h1>

        <p class="text-xs font-bold text-slate-400 mt-2">
            Kiosk Management
        </p>
    </div>

    <nav class="space-y-2 flex-1 overflow-y-auto pr-1">
        @foreach ([
            ['route' => 'admin.dashboard', 'label' => 'Dashboard'],
            ['route' => 'admin.print-jobs', 'label' => 'Print Jobs'],
            ['route' => 'admin.coins', 'label' => 'Coins'],
            ['route' => 'admin.logs', 'label' => 'Logs'],
            ['route' => 'admin.maintenance', 'label' => 'Maintenance'],
            ['route' => 'admin.profile', 'label' => 'Profile'],
            ['route' => 'admin.settings', 'label' => 'Settings'],
        ] as $item)
            <a
                href="{{ route($item['route']) }}"
                class="block rounded-2xl px-4 py-4 text-base font-black active:scale-95 transition {{ request()->routeIs($item['route']) ? 'bg-white text-slate-950 shadow-lg' : 'bg-slate-800 text-white' }}"
            >
                {{ $item['label'] }}
            </a>
        @endforeach

        @if (session('admin_username') === 'admin')
            <a
                href="{{ route('admin.users') }}"
                class="block rounded-2xl px-4 py-4 text-base font-black active:scale-95 transition {{ request()->routeIs('admin.users*') ? 'bg-white text-slate-950 shadow-lg' : 'bg-slate-800 text-white' }}"
            >
                Users
            </a>
        @endif
    </nav>

    <form
        method="POST"
        action="{{ route('admin.logout') }}"
        class="pt-4"
    >
        @csrf

        <button
            type="submit"
            class="w-full rounded-2xl bg-red-500 text-white px-4 py-4 text-base font-black shadow-lg active:scale-95 transition"
        >
            Logout
        </button>
    </form>
</aside>
