<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
    >

    <title>Register Kiosk</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            touch-action: manipulation;

            -webkit-user-select: none;

            user-select: none;
        }

        html,
        body {
            overscroll-behavior: none;
        }

        html {
            touch-action: manipulation;
        }
    </style>
</head>

<body
    draggable="false"
    class="min-h-screen bg-slate-950 flex items-center justify-center overflow-hidden"
>
<main class="relative w-[1024px] h-[600px] overflow-hidden bg-gradient-to-br from-white via-slate-50 to-slate-200 shadow-2xl">
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-blue-200/30 blur-3xl"></div>

    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-emerald-200/30 blur-3xl"></div>

    <section class="relative z-10 h-full p-8">
        <div class="h-full bg-white/90 rounded-[2.5rem] p-8 shadow-2xl overflow-y-auto">
            <div class="text-center mb-8">
                <div class="text-7xl mb-3">
                    🏫
                </div>

                <h1 class="text-5xl font-black text-slate-950 mb-2">
                    Register Kiosk
                </h1>

                <p class="text-lg text-slate-600">
                    Setup this Piso Print unit for your school or organization.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-3xl bg-red-100 text-red-700 p-5 text-lg font-black">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('registration.store') }}"
                class="space-y-6"
            >
                @csrf

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            School Name
                        </label>

                        <input
                            type="text"
                            name="school_name"
                            value="{{ old('school_name') }}"
                            required
                            class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Unit Serial Number
                        </label>

                        <input
                            type="text"
                            name="unit_serial_number"
                            value="{{ old('unit_serial_number') }}"
                            required
                            placeholder="PP-SCHOOL-001"
                            class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Contact Person
                        </label>

                        <input
                            type="text"
                            name="contact_person"
                            value="{{ old('contact_person') }}"
                            required
                            class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Contact Number
                        </label>

                        <input
                            type="text"
                            name="contact_number"
                            value="{{ old('contact_number') }}"
                            required
                            class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 mb-2">
                        Address
                    </label>

                    <textarea
                        name="address"
                        rows="3"
                        required
                        class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold resize-none"
                    >{{ old('address') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            value="{{ old('city') }}"
                            class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2">
                            Province
                        </label>

                        <input
                            type="text"
                            name="province"
                            value="{{ old('province') }}"
                            class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold"
                        >
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-6">
                    <h2 class="text-2xl font-black mb-5 text-slate-900">
                        Admin Account
                    </h2>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Admin Name
                            </label>

                            <input
                                type="text"
                                name="admin_name"
                                value="{{ old('admin_name') }}"
                                required
                                class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5 mt-5">
                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                required
                                class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Admin PIN
                            </label>

                            <input
                                type="password"
                                name="pin_code"
                                required
                                maxlength="6"
                                class="w-full rounded-3xl bg-slate-100 px-5 py-4 text-lg font-bold"
                            >
                        </div>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-3xl bg-slate-950 text-white text-2xl font-black py-5 shadow-2xl active:scale-95 transition"
                >
                    Register Kiosk
                </button>
            </form>
        </div>
    </section>
</main>
</body>
</html>
