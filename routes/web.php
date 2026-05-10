<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KioskController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [KioskController::class, 'home'])->name('kiosk.home');
Route::get('/upload', [KioskController::class, 'upload'])->name('kiosk.upload');
Route::post('/upload', [KioskController::class, 'store'])->name('kiosk.store');

Route::get('/payment/{printJob}', [KioskController::class, 'payment'])
    ->name('kiosk.payment');

Route::post(
    '/payment/{printJob}/credit/{amount}',
    [KioskController::class, 'addCredit']
)->name('kiosk.add-credit');

Route::post('/payment/{printJob}/print', [KioskController::class, 'print'])
    ->name('kiosk.print');

Route::get('/printing/{printJob}', [KioskController::class, 'printing'])
    ->name('kiosk.printing');

Route::get('/status/{printJob}', [KioskController::class, 'status'])
    ->name('kiosk.status');

Route::get('/admin', [AdminController::class, 'dashboard'])
    ->name('admin.dashboard');

Route::get('/preview/{printJob}', [KioskController::class, 'preview'])
    ->name('kiosk.preview');

Route::post('/preview/{printJob}/confirm', [KioskController::class, 'confirm'])
    ->name('kiosk.confirm');

Route::get('/preview-file/{printJob}', function (\App\Models\PrintJob $printJob) {
    if (! request()->hasValidSignature()) {
        abort(403);
    }

    $path = $printJob->filtered_pdf_path
        ?: $printJob->converted_pdf_path;

    return response()->file(
        Storage::disk('local')->path($path)
    );
})->name('kiosk.preview-file');

Route::post('/preview/{printJob}/pages', [KioskController::class, 'updatePages'])
    ->name('kiosk.update-pages');

Route::post(
    '/preview/{printJob}/print-mode',
    [KioskController::class, 'updatePrintMode']
)->name('kiosk.update-print-mode');
