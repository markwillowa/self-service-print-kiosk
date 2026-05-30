<x-kiosk-layout title="Register Kiosk">
    <style>
        .registration-scroll::-webkit-scrollbar {
            width: 10px;
        }

        .registration-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .registration-scroll::-webkit-scrollbar-thumb {
            background: rgba(100, 116, 139, 0.35);
            border-radius: 999px;
        }

        .registration-scroll {
            scrollbar-width: thin;
        }
    </style>

    <div class="h-full flex flex-col min-h-0 gap-4 py-2 max-h-full">
        <div class="flex items-center gap-4 shrink-0">
            <div class="w-20 h-20 rounded-3xl bg-slate-950 text-white flex items-center justify-center shrink-0 shadow-xl">
                <x-heroicon-o-building-office-2 class="w-11 h-11" />
            </div>

            <div class="min-w-0">
                <h1 class="text-4xl font-black text-slate-950 leading-none mb-2">
                    Register Kiosk
                </h1>

                <p class="text-base text-slate-600 font-bold">
                    Setup this printing kiosk unit.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="shrink-0 rounded-2xl bg-red-100 text-red-700 p-4 text-base font-black">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            id="registrationForm"
            method="POST"
            action="{{ route('registration.store') }}"
            enctype="multipart/form-data"
            class="flex-1 min-h-0 flex flex-col overflow-hidden"
        >
            @csrf

            <div class="registration-scroll bg-white/90 rounded-3xl p-5 shadow-xl border border-white flex-1 min-h-0 overflow-y-auto">
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div
                        id="stepIndicator1"
                        class="rounded-2xl bg-slate-950 text-white px-4 py-3"
                    >
                        <div class="text-xs font-black uppercase opacity-70">
                            Step 1
                        </div>

                        <div class="text-lg font-black">
                            Company Profile
                        </div>
                    </div>

                    <div
                        id="stepIndicator2"
                        class="rounded-2xl bg-slate-100 text-slate-600 px-4 py-3"
                    >
                        <div class="text-xs font-black uppercase opacity-70">
                            Step 2
                        </div>

                        <div class="text-lg font-black">
                            Organization
                        </div>
                    </div>

                    <div
                        id="stepIndicator3"
                        class="rounded-2xl bg-slate-100 text-slate-600 px-4 py-3"
                    >
                        <div class="text-xs font-black uppercase opacity-70">
                            Step 3
                        </div>

                        <div class="text-lg font-black">
                            Admin Account
                        </div>
                    </div>
                </div>

                <section
                    id="step1"
                    class="registration-step"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-3xl font-black text-slate-950 leading-none mb-1">
                                Company Profile
                            </h2>

                            <p class="text-sm text-slate-500 font-bold">
                                Enter the company and kiosk pricing information.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Company Logo
                                <span class="text-slate-400 font-bold">
                                    Optional
                                </span>
                            </label>

                            <input
                                type="file"
                                name="company_avatar"
                                accept="image/png,image/jpeg"
                                class="w-full rounded-2xl bg-slate-100 px-4 py-4 text-base font-bold"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Kiosk Name
                            </label>

                            <select
                                id="kioskNameSelect"
                                name="kiosk_name"
                                required
                                data-step="1"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                            >
                                <option
                                    value="Piso Print"
                                    @selected(old('kiosk_name', 'Piso Print') === 'Piso Print')
                                >
                                    Piso Print
                                </option>

                                <option
                                    value="Self-Service Print"
                                    @selected(old('kiosk_name') === 'Self-Service Print')
                                >
                                    Self-Service Print
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Company Name
                            </label>

                            <input
                                type="text"
                                name="company_name"
                                value="{{ old('company_name') }}"
                                required
                                data-step="1"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Owner
                            </label>

                            <input
                                type="text"
                                name="company_owner"
                                value="{{ old('company_owner') }}"
                                required
                                data-step="1"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Company Contact Number
                            </label>

                            <input
                                type="text"
                                name="company_contact_number"
                                value="{{ old('company_contact_number') }}"
                                required
                                data-step="1"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Company Email
                                <span class="text-slate-400 font-bold">
                                    Optional
                                </span>
                            </label>

                            <input
                                type="email"
                                name="company_email"
                                value="{{ old('company_email') }}"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                            >
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Company Address
                            </label>

                            <textarea
                                name="company_address"
                                rows="3"
                                required
                                data-step="1"
                                class="w-full rounded-2xl bg-slate-100 px-4 py-3 text-lg font-black resize-none"
                            >{{ old('company_address') }}</textarea>
                        </div>
                    </div>

                    <div
                        id="customPricingSection"
                        class="hidden mt-5 rounded-3xl bg-amber-50 border border-amber-200 p-4"
                    >
                        <div class="flex items-center gap-3 mb-4">
                            <x-heroicon-o-banknotes class="w-7 h-7 text-amber-800" />

                            <div>
                                <h3 class="text-xl font-black text-amber-900 leading-none mb-1">
                                    Custom Pricing
                                </h3>

                                <p class="text-sm font-bold text-amber-800">
                                    Only available for Self-Service Print.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-black text-slate-700 mb-2">
                                    Black Price Per Page
                                </label>

                                <input
                                    type="number"
                                    name="black_price_per_page"
                                    value="{{ old('black_price_per_page', 1) }}"
                                    min="1"
                                    class="w-full rounded-2xl bg-white px-4 h-14 text-lg font-black"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-black text-slate-700 mb-2">
                                    Colored Price Per Page
                                </label>

                                <input
                                    type="number"
                                    name="color_price_per_page"
                                    value="{{ old('color_price_per_page', 3) }}"
                                    min="1"
                                    class="w-full rounded-2xl bg-white px-4 h-14 text-lg font-black"
                                >
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    id="step2"
                    class="registration-step hidden"
                >
                    <div class="mb-4">
                        <h2 class="text-3xl font-black text-slate-950 leading-none mb-1">
                            Organization
                        </h2>

                        <p class="text-sm text-slate-500 font-bold">
                            Enter the school or deployment location details.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                School Name
                            </label>

                            <input
                                type="text"
                                name="school_name"
                                value="{{ old('school_name') }}"
                                required
                                data-step="2"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
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
                                data-step="2"
                                placeholder="PP-SCHOOL-001"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
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
                                data-step="2"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Contact Number
                            </label>

                            <input
                                type="text"
                                name="contact_number"
                                value="{{ old('contact_number') }}"
                                required
                                data-step="2"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Email
                                <span class="text-slate-400 font-bold">
                                    Optional
                                </span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                City
                                <span class="text-slate-400 font-bold">
                                    Optional
                                </span>
                            </label>

                            <input
                                type="text"
                                name="city"
                                value="{{ old('city') }}"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Province
                                <span class="text-slate-400 font-bold">
                                    Optional
                                </span>
                            </label>

                            <input
                                type="text"
                                name="province"
                                value="{{ old('province') }}"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                            >
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Address
                            </label>

                            <textarea
                                name="address"
                                rows="3"
                                required
                                data-step="2"
                                class="w-full rounded-2xl bg-slate-100 px-4 py-3 text-lg font-black resize-none"
                            >{{ old('address') }}</textarea>
                        </div>
                    </div>
                </section>

                <section
                    id="step3"
                    class="registration-step hidden"
                >
                    <div class="mb-4">
                        <h2 class="text-3xl font-black text-slate-950 leading-none mb-1">
                            Admin Account
                        </h2>

                        <p class="text-sm text-slate-500 font-bold">
                            Create the first administrator account for this kiosk.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Admin Name
                            </label>

                            <input
                                type="text"
                                name="admin_name"
                                value="{{ old('admin_name') }}"
                                required
                                data-step="3"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
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
                                data-step="3"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
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
                                data-step="3"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
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
                                data-step="3"
                                maxlength="6"
                                class="w-full rounded-2xl bg-slate-100 px-4 h-14 text-lg font-black"
                            >
                        </div>
                    </div>
                </section>
            </div>

            <div class="shrink-0 flex items-center justify-between pt-4 gap-3">
                <button
                    type="button"
                    id="backButton"
                    onclick="previousStep()"
                    class="hidden rounded-2xl bg-slate-200 text-slate-900 px-8 h-14 text-lg font-black active:scale-95 transition"
                >
                    Back
                </button>

                <div class="flex-1"></div>

                <button
                    type="button"
                    id="nextButton"
                    onclick="nextStep()"
                    class="rounded-2xl bg-slate-950 text-white px-10 h-14 text-lg font-black shadow-xl active:scale-95 transition"
                >
                    Next
                </button>

                <button
                    type="submit"
                    id="submitButton"
                    class="hidden rounded-2xl bg-emerald-600 text-white px-10 h-14 text-lg font-black shadow-xl active:scale-95 transition"
                >
                    Register Kiosk
                </button>
            </div>
        </form>
    </div>

    <script>
        let currentStep = 1;

        const totalSteps = 3;

        const kioskNameSelect = document.getElementById(
            'kioskNameSelect'
        );

        const customPricingSection = document.getElementById(
            'customPricingSection'
        );

        function toggleCustomPricingSection() {
            if (
                kioskNameSelect.value === 'Self-Service Print'
            ) {
                customPricingSection.classList.remove('hidden');
            } else {
                customPricingSection.classList.add('hidden');
            }
        }

        function requiredFieldsForStep(step) {
            return Array.from(
                document.querySelectorAll(
                    '[data-step="' + step + '"][required]'
                )
            );
        }

        function validateCurrentStep() {
            const fields = requiredFieldsForStep(currentStep);

            for (const field of fields) {
                if (! field.checkValidity()) {
                    field.reportValidity();

                    return false;
                }
            }

            return true;
        }

        function showStep(step) {
            document
                .querySelectorAll('.registration-step')
                .forEach((section) => {
                    section.classList.add('hidden');
                });

            document
                .getElementById('step' + step)
                .classList.remove('hidden');

            for (let index = 1; index <= totalSteps; index++) {
                const indicator = document.getElementById(
                    'stepIndicator' + index
                );

                if (index === step) {
                    indicator.className =
                        'rounded-2xl bg-slate-950 text-white px-4 py-3';
                } else if (index < step) {
                    indicator.className =
                        'rounded-2xl bg-emerald-100 text-emerald-800 px-4 py-3';
                } else {
                    indicator.className =
                        'rounded-2xl bg-slate-100 text-slate-600 px-4 py-3';
                }
            }

            document
                .getElementById('backButton')
                .classList.toggle('hidden', step === 1);

            document
                .getElementById('nextButton')
                .classList.toggle('hidden', step === totalSteps);

            document
                .getElementById('submitButton')
                .classList.toggle('hidden', step !== totalSteps);
        }

        function nextStep() {
            if (! validateCurrentStep()) {
                return;
            }

            if (currentStep < totalSteps) {
                currentStep++;

                showStep(currentStep);
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                currentStep--;

                showStep(currentStep);
            }
        }

        kioskNameSelect.addEventListener(
            'change',
            toggleCustomPricingSection
        );

        toggleCustomPricingSection();

        showStep(currentStep);
    </script>
</x-kiosk-layout>
