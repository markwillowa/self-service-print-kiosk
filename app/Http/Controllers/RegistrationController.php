<?php

namespace App\Http\Controllers;

use App\Models\Admin;
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
