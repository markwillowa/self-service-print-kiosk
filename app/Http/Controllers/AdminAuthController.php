<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function unlock(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'pin_code' => ['required', 'string'],
        ]);

        $admins = Admin::query()->get();

        foreach ($admins as $admin) {
            if (
                Hash::check(
                    $validated['pin_code'],
                    $admin->pin_code
                )
            ) {
                session()->put(
                    'admin_authenticated',
                    true
                );

                session()->put(
                    'admin_id',
                    $admin->id
                );

                session()->put(
                    'admin_name',
                    $admin->name
                );

                session()->put(
                    'admin_username',
                    $admin->username
                );

                session()->put(
                    'admin_expires_at',
                    now()->addMinutes(10)
                );

                return redirect()->route(
                    'admin.dashboard'
                );
            }
        }

        return back()->withErrors([
            'pin_code' => 'Invalid admin PIN.',
        ]);
    }

    public function logout(): RedirectResponse
    {
        session()->forget([
            'admin_authenticated',
            'admin_id',
            'admin_name',
            'admin_username',
            'admin_expires_at',
        ]);

        return redirect()->route('kiosk.home');
    }
}
