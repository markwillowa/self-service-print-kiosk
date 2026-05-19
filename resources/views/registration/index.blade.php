<x-kiosk-layout title="Register Kiosk">
    <div class="h-full bg-white/90 rounded-2xl p-3 shadow-xl overflow-y-auto">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-14 h-14 rounded-2xl bg-slate-950 text-white flex items-center justify-center shrink-0">
                <x-heroicon-o-building-office-2 class="w-8 h-8" />
            </div>

            <div>
                <h1 class="text-3xl font-black text-slate-950 leading-none">
                    Register Kiosk
                </h1>

                <p class="text-sm text-slate-600 font-bold">
                    Setup this Piso Print unit.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-3 rounded-2xl bg-red-100 text-red-700 p-3 text-sm font-black">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('registration.store') }}"
            enctype="multipart/form-data"
            class="space-y-3"
        >
            @csrf

            <div class="border-b border-slate-200 pb-3">
                <h2 class="text-lg font-black mb-2 text-slate-900">
                    Company Profile
                </h2>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Company Logo
                        </label>

                        <input
                            type="file"
                            name="company_avatar"
                            accept="image/png,image/jpeg"
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Company Name
                        </label>

                        <input
                            type="text"
                            name="company_name"
                            value="{{ old('company_name') }}"
                            required
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Owner
                        </label>

                        <input
                            type="text"
                            name="company_owner"
                            value="{{ old('company_owner') }}"
                            required
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Company Contact Number
                        </label>

                        <input
                            type="text"
                            name="company_contact_number"
                            value="{{ old('company_contact_number') }}"
                            required
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Company Email
                        </label>

                        <input
                            type="email"
                            name="company_email"
                            value="{{ old('company_email') }}"
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>
                </div>

                <div class="mt-3">
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Company Address
                    </label>

                    <textarea
                        name="company_address"
                        rows="2"
                        required
                        class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold resize-none"
                    >{{ old('company_address') }}</textarea>
                </div>
            </div>

            <div class="border-b border-slate-200 pb-3">
                <h2 class="text-lg font-black mb-2 text-slate-900">
                    Kiosk / School Information
                </h2>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            School Name
                        </label>

                        <input
                            type="text"
                            name="school_name"
                            value="{{ old('school_name') }}"
                            required
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Unit Serial Number
                        </label>

                        <input
                            type="text"
                            name="unit_serial_number"
                            value="{{ old('unit_serial_number') }}"
                            required
                            placeholder="PP-SCHOOL-001"
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Contact Person
                        </label>

                        <input
                            type="text"
                            name="contact_person"
                            value="{{ old('contact_person') }}"
                            required
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Contact Number
                        </label>

                        <input
                            type="text"
                            name="contact_number"
                            value="{{ old('contact_number') }}"
                            required
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>
                </div>

                <div class="mt-3">
                    <label class="block text-xs font-black text-slate-700 mb-1">
                        Address
                    </label>

                    <textarea
                        name="address"
                        rows="2"
                        required
                        class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold resize-none"
                    >{{ old('address') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            value="{{ old('city') }}"
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Province
                        </label>

                        <input
                            type="text"
                            name="province"
                            value="{{ old('province') }}"
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>
                </div>
            </div>

            <div class="border-b border-slate-200 pb-3">
                <h2 class="text-lg font-black mb-2 text-slate-900">
                    Admin Account
                </h2>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Admin Name
                        </label>

                        <input
                            type="text"
                            name="admin_name"
                            value="{{ old('admin_name') }}"
                            required
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-700 mb-1">
                            Admin PIN
                        </label>

                        <input
                            type="password"
                            name="pin_code"
                            required
                            maxlength="6"
                            class="w-full rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold"
                        >
                    </div>
                </div>
            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-slate-950 text-white text-lg font-black py-3 shadow-xl active:scale-95 transition"
            >
                Register Kiosk
            </button>
        </form>
    </div>
</x-kiosk-layout>
