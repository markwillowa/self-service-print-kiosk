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
            '/admin/system-update',
            [AdminController::class, 'systemUpdate']
        )->name('admin.system-update');

        Route::post(
            '/admin/system-reboot',
            [AdminController::class, 'systemReboot']
        )->name('admin.system-reboot');

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

            Route::post(
                '/admin/profile/pricing',
                [AdminController::class, 'updatePricing']
            )->name('admin.profile.pricing.update');
        });

        Route::get(
            '/admin/vouchers',
            [AdminController::class, 'vouchers']
        )->name('admin.vouchers');

        Route::post(
            '/admin/vouchers',
            [AdminController::class, 'storeVoucher']
        )->name('admin.vouchers.store');
    });

    Route::get(
        '/',
        [KioskController::class, 'language']
    )->name('kiosk.language');

    Route::post(
        '/language',
        [KioskController::class, 'setLanguage']
    )->name('kiosk.set-language');

    Route::get(
        '/home',
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

    Route::get(
        '/paper-check/{printJob}',
        [KioskController::class, 'paperCheck']
    )->name('kiosk.paper-check');

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

            $path =
                $printJob->preview_pdf_path
                    ?: $printJob->filtered_pdf_path
                    ?: $printJob->converted_pdf_path;

            return response()->file(
                Storage::disk('local')->path($path),
                [
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                ]
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

Route::post('/reboot', function () {
    $process = new Process([
        'sudo',
        '-n',
        '/usr/sbin/reboot',
    ]);

    $process->run();

    if (! $process->isSuccessful()) {
        logger()->error('Reboot failed', [
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput(),
        ]);

        return response(
            'Reboot failed: ' . $process->getErrorOutput(),
            500
        );
    }

    return response('Rebooting...');
})->middleware([
    'kiosk.registered',
    'kiosk.local',
])->name('kiosk.reboot');

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

    $lpstatCommand = [
        'lpstat',
        '-p',
    ];

    if ($printerName) {
        $lpstatCommand[] = $printerName;
    }

    $lpstatProcess = new Process($lpstatCommand);

    $lpstatProcess->setTimeout(3);
    $lpstatProcess->run();

    $lpstatOutput =
        strtolower($lpstatProcess->getOutput()) .
        "\n" .
        strtolower($lpstatProcess->getErrorOutput());

    $printerExistsInCups = $lpstatProcess->isSuccessful();

    $cupsReady =
        str_contains($lpstatOutput, 'is idle') ||
        str_contains($lpstatOutput, 'now printing') ||
        str_contains($lpstatOutput, 'enabled') ||
        str_contains($lpstatOutput, 'accepting requests');

    $isDisabled =
        (str_contains($lpstatOutput, 'disabled') && !str_contains($lpstatOutput, 'enabled')) ||
        str_contains($lpstatOutput, 'not available') ||
        str_contains($lpstatOutput, 'not accepting requests');

    $lsusbProcess = new Process([
        'lsusb',
    ]);

    $lsusbProcess->setTimeout(3);
    $lsusbProcess->run();

    $lsusbOutput = strtolower(
        $lsusbProcess->getOutput() .
        "\n" .
        $lsusbProcess->getErrorOutput()
    );

    $usbDetected =
        str_contains($lsusbOutput, 'epson') ||
        str_contains($lsusbOutput, 'seiko') ||
        str_contains($lsusbOutput, 'printer') ||
        str_contains($lsusbOutput, 'canon') ||
        str_contains($lsusbOutput, 'hp') ||
        str_contains($lsusbOutput, 'brother');

    $online =
        ($printerExistsInCups && $cupsReady && ! $isDisabled) ||
        $usbDetected;

    // Fallback: If we have NO printer name configured and lpstat -p failed,
    // but usb is detected, we should be okay.
    // Or if lpstat output contains "printer", it exists.
    if (!$online && str_contains($lpstatOutput, 'printer')) {
        $online = true;
    }

    if (! $online) {
        logger()->warning('Printer detection failed', [
            'printer_name' => $printerName,
            'cups_ok' => $printerExistsInCups,
            'cups_ready' => $cupsReady,
            'cups_disabled' => $isDisabled,
            'lpstat_output' => $lpstatOutput,
            'lsusb_output' => $lsusbOutput,
        ]);
    }

    return response()->json([
        'online' => $online,
        'message' => $online
            ? 'Printer is online.'
            : 'Printer is offline or not detected.',
        'cups_ok' => $printerExistsInCups,
        'cups_ready' => $cupsReady,
        'cups_disabled' => $isDisabled,
        'usb_detected' => $usbDetected,
        'lpstat_output' => trim($lpstatOutput),
        'lsusb_output' => trim($lsusbOutput),
    ]);
})->middleware([
    'kiosk.registered',
    'kiosk.local',
])->name('kiosk.printer-status');

Route::post(
    '/voucher/redeem',
    [KioskController::class, 'redeemVoucher']
)->name('kiosk.voucher.redeem');

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
