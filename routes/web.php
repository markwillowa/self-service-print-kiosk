<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\RegistrationController;
use App\Models\PrintJob;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Registration Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/register-kiosk',
    [RegistrationController::class, 'index']
)->name('registration.index');

Route::post(
    '/register-kiosk',
    [RegistrationController::class, 'store']
)->name('registration.store');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::post(
    '/admin/unlock',
    [AdminAuthController::class, 'unlock']
)->name('admin.unlock');

Route::post(
    '/admin/logout',
    [AdminAuthController::class, 'logout']
)->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Protected Kiosk Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'kiosk.registered',
    'kiosk.local',
])->group(function () {
    Route::middleware('admin.auth')->group(function () {
        Route::get(
            '/admin',
            [AdminController::class, 'dashboard']
        )->name('admin.dashboard');
    });

    Route::get(
        '/',
        [KioskController::class, 'home']
    )->name('kiosk.home');

    Route::get(
        '/payment/{printJob}',
        [KioskController::class, 'payment']
    )->name('kiosk.payment');

    Route::post(
        '/payment/{printJob}/credit/{amount}',
        [KioskController::class, 'addCredit']
    )->name('kiosk.add-credit');

    Route::post(
        '/payment/{printJob}/print',
        [KioskController::class, 'print']
    )->name('kiosk.print');

    Route::get(
        '/printing/{printJob}',
        [KioskController::class, 'printing']
    )->name('kiosk.printing');

    Route::get(
        '/status/{printJob}',
        [KioskController::class, 'status']
    )->name('kiosk.status');

    Route::get(
        '/preview/{printJob}',
        [KioskController::class, 'preview']
    )->name('kiosk.preview');

    Route::post(
        '/preview/{printJob}/confirm',
        [KioskController::class, 'confirm']
    )->name('kiosk.confirm');

    Route::get(
        '/preview-file/{printJob}',
        function (PrintJob $printJob) {
            if (! request()->hasValidSignature()) {
                abort(403);
            }

            $path = $printJob->filtered_pdf_path
                ?: $printJob->converted_pdf_path;

            return response()->file(
                Storage::disk('local')->path($path)
            );
        }
    )->name('kiosk.preview-file');

    Route::post(
        '/preview/{printJob}/pages',
        [KioskController::class, 'updatePages']
    )->name('kiosk.update-pages');

    Route::post(
        '/preview/{printJob}/print-mode',
        [KioskController::class, 'updatePrintMode']
    )->name('kiosk.update-print-mode');

    Route::post(
        '/kiosk/{printJob}/cancel',
        [KioskController::class, 'cancel']
    )->name('kiosk.cancel');

    Route::get(
        '/connect',
        [KioskController::class, 'connect']
    )->name('kiosk.connect');

    Route::get(
        '/transfer',
        [KioskController::class, 'transfer']
    )->name('kiosk.transfer');
});

/*
|--------------------------------------------------------------------------
| Protected Phone Routes
|--------------------------------------------------------------------------
*/
Route::get(
    '/upload',
    [KioskController::class, 'upload']
)->name('kiosk.upload');

Route::post(
    '/upload',
    [KioskController::class, 'store']
)->name('kiosk.store');
