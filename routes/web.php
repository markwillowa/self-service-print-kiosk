<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\RegistrationController;
use App\Models\PrintJob;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

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

        Route::get(
            '/admin/print-jobs',
            [AdminController::class, 'printJobs']
        )->name('admin.print-jobs');

        Route::get(
            '/admin/coins',
            [AdminController::class, 'coins']
        )->name('admin.coins');

        Route::get(
            '/admin/settings',
            [AdminController::class, 'settings']
        )->name('admin.settings');

        Route::get(
            '/admin/logs',
            [AdminController::class, 'logs']
        )->name('admin.logs');

        Route::post(
            '/admin/logs/clear',
            [AdminController::class, 'clearLogs']
        )->name('admin.logs.clear');

        Route::get(
            '/admin/profile',
            [AdminController::class, 'profile']
        )->name('admin.profile');

        Route::get(
            '/admin/maintenance',
            [AdminController::class, 'maintenance']
        )->name('admin.maintenance');

        Route::post(
            '/admin/maintenance',
            [AdminController::class, 'storeMaintenance']
        )->name('admin.maintenance.store');

        Route::get(
            '/admin/maintenance/{maintenance}/report',
            [AdminController::class, 'maintenanceReport']
        )->name('admin.maintenance.report');

        Route::middleware('super.admin')->group(function () {
            Route::get(
                '/admin/users',
                [AdminController::class, 'users']
            )->name('admin.users');

            Route::post(
                '/admin/users',
                [AdminController::class, 'storeUser']
            )->name('admin.users.store');
        });
    });

    Route::get(
        '/',
        [KioskController::class, 'home']
    )->name('kiosk.home');

    Route::get(
        '/upload',
        [KioskController::class, 'upload']
    )->name('kiosk.upload');

    Route::post(
        '/upload',
        [KioskController::class, 'store']
    )->name('kiosk.store');

    Route::post(
        '/preview/{printJob}/back',
        [KioskController::class, 'backFromPreview']
    )->name('kiosk.preview.back');

    Route::post(
        '/upload/{printJob}/select',
        [KioskController::class, 'selectUploadedFile']
    )->name('kiosk.select-upload');

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

    Route::post('/open-keyboard', function () {
        shell_exec(
            'WAYLAND_DISPLAY=wayland-0 XDG_RUNTIME_DIR=/run/user/1000 matchbox-keyboard >/dev/null 2>&1 &'
        );

        return response()->json([
            'success' => true,
        ]);
    })->middleware([
        'kiosk.registered',
        'kiosk.local',
    ])->name('kiosk.open-keyboard');

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

            $path =
                $printJob->preview_pdf_path
                    ?: $printJob->filtered_pdf_path
                    ?: $printJob->converted_pdf_path;

            return response()->file(
                Storage::disk('local')->path($path)
            );
        }
    )->name('kiosk.preview-file');

    Route::post(
        '/preview/{printJob}/settings',
        [KioskController::class, 'updateSettings']
    )->name('kiosk.update-settings');

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

Route::post('/shutdown', function () {
    $process = new Process([
        'sudo',
        '-n',
        '/usr/sbin/shutdown',
        'now',
    ]);

    $process->run();

    if (! $process->isSuccessful()) {
        logger()->error('Shutdown failed', [
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput(),
        ]);

        return response(
            'Shutdown failed: ' . $process->getErrorOutput(),
            500
        );
    }

    return response('Shutting down...');
})->middleware([
    'kiosk.registered',
    'kiosk.local',
])->name('kiosk.shutdown');

Route::get('/printer-status', function () {
    $printerName = config('services.printer.name');

    $process = new Process([
        'lpstat',
        '-p',
        $printerName,
    ]);

    $process->run();

    return response()->json([
        'online' => $process->isSuccessful(),
        'message' => $process->isSuccessful()
            ? 'Printer is online.'
            : 'Printer is offline or not available.',
        'output' => $process->getOutput(),
        'error' => $process->getErrorOutput(),
    ]);
})->middleware([
    'kiosk.registered',
    'kiosk.local',
])->name('kiosk.printer-status');

/*
|--------------------------------------------------------------------------
| Protected Phone Routes
|--------------------------------------------------------------------------
*/
Route::get(
    '/mobile-upload',
    [KioskController::class, 'mobileUpload']
)->name('kiosk.mobile-upload');

Route::post(
    '/mobile-upload',
    [KioskController::class, 'mobileStore']
)->name('kiosk.mobile-store');

Route::post('/coin', function (Request $request) {
    $validated = $request->validate([
        'amount' => ['required', 'integer', 'min:1'],
    ]);

    $creditService = app(\App\Services\KioskCreditService::class);

    $activeJobUuid = app(\App\Services\KioskSessionLock::class)
        ->activeJobUuid();

    $printJob = null;

    if ($activeJobUuid) {
        $printJob = PrintJob::query()
            ->where('uuid', $activeJobUuid)
            ->where('status', 'pending_payment')
            ->first();
    }

    if (! $printJob) {
        $creditService->add($validated['amount']);

        return response()->json([
            'success' => true,
            'message' => 'Credit saved.',
            'credit_balance' => $creditService->balance(),
        ]);
    }

    $remaining = max(
        $printJob->total_amount - $printJob->paid_amount,
        0
    );

    $coinAmount = $validated['amount'];

    $usedAmount = min($coinAmount, $remaining);

    $excessAmount = max($coinAmount - $usedAmount, 0);

    if ($usedAmount > 0) {
        $printJob->increment('paid_amount', $usedAmount);
    }

    if ($excessAmount > 0) {
        $creditService->add($excessAmount);
    }

    $printJob->refresh();

    if ($printJob->paid_amount >= $printJob->total_amount) {
        $printJob->update([
            'status' => 'paid',
        ]);

        $printJob->refresh();
    }

    return response()->json([
        'success' => true,
        'paid_amount' => $printJob->paid_amount,
        'total_amount' => $printJob->total_amount,
        'status' => $printJob->status,
        'credit_balance' => $creditService->balance(),
    ]);
});

Route::get('/kiosk-credit', function () {
    return response()->json([
        'credit_balance' => app(\App\Services\KioskCreditService::class)
            ->balance(),
    ]);
})->name('kiosk.credit');
