<?php

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
