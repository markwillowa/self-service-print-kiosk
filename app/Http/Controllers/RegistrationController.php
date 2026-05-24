<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function index(): View
    {
        return view('registration.index');
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'company_avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            'kiosk_name' => [
                'required',
                'string',
                'in:Piso Print,Self-Service Print',
            ],

            'company_name' => ['required', 'string', 'max:255'],

            'company_owner' => ['required', 'string', 'max:255'],

            'company_address' => ['required', 'string'],

            'company_email' => ['nullable', 'email'],

            'company_contact_number' => ['required', 'string', 'max:50'],

            'black_price_per_page' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'color_price_per_page' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'school_name' => ['required', 'string', 'max:255'],

            'contact_person' => ['required', 'string', 'max:255'],

            'contact_number' => ['required', 'string', 'max:50'],

            'email' => ['nullable', 'email'],

            'address' => ['required', 'string'],

            'city' => ['nullable', 'string'],

            'province' => ['nullable', 'string'],

            'unit_serial_number' => [
                'required',
                'string',
                'max:255',
                'unique:organizations,unit_serial_number',
            ],

            'admin_name' => ['required', 'string', 'max:255'],

            'username' => ['required', 'string', 'max:255', 'unique:admins,username'],

            'password' => ['required', 'string', 'min:6'],

            'pin_code' => ['required', 'digits_between:4,6'],
        ]);

        $avatarPath = null;

        if ($request->hasFile('company_avatar')) {
            $avatarPath = $request
                ->file('company_avatar')
                ->store('companies/avatars', 'public');
        }

        $kioskName = $validated['kiosk_name'];

        $isSelfService =
            $kioskName === 'Self-Service Print';

        $blackPrice =
            $isSelfService
                ? (int) ($validated['black_price_per_page'] ?? 1)
                : 1;

        $colorPrice =
            $isSelfService
                ? (int) ($validated['color_price_per_page'] ?? 3)
                : 3;

        Company::create([
            'avatar' => $avatarPath,
            'kiosk_name' => $validated['kiosk_name'],
            'black_price_per_page' => $blackPrice,
            'color_price_per_page' => $colorPrice,
            'allow_custom_pricing' => $isSelfService,
            'name' => $validated['company_name'],
            'owner' => $validated['company_owner'],
            'address' => $validated['company_address'],
            'email' => $validated['company_email'] ?? null,
            'contact_number' => $validated['company_contact_number'],
        ]);

        $organization = Organization::create([
            'school_name' => $validated['school_name'],

            'contact_person' => $validated['contact_person'],

            'contact_number' => $validated['contact_number'],

            'email' => $validated['email'] ?? null,

            'address' => $validated['address'],

            'city' => $validated['city'] ?? null,

            'province' => $validated['province'] ?? null,

            'unit_serial_number' => strtoupper(
                $validated['unit_serial_number']
            ),

            'is_registered' => true,

            'registered_at' => now(),
        ]);

        Admin::create([
            'organization_id' => $organization->id,

            'name' => $validated['admin_name'],

            'username' => $validated['username'],

            'password' => Hash::make($validated['password']),

            'pin_code' => Hash::make($validated['pin_code']),

            'is_super_admin' => true,
        ]);

        return redirect()->route('kiosk.home');
    }
}
