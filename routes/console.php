<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:prune-batches')->daily();

Schedule::call(function () {
    \App\Models\PrintJob::query()
        ->where('expires_at', '<', now())
        ->update([
            'status' => 'cancelled',
        ]);
})->everyMinute();

Schedule::command('print:process-queue')
    ->everySecond()
    ->withoutOverlapping();

Schedule::command(
    'print:cancel-expired'
)->everyMinute();

Schedule::command(
    'print:cleanup'
)->everyFiveMinutes();
