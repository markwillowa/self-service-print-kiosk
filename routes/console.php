<?php

use App\Models\PrintJob;
use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:prune-batches')->daily();

Schedule::command('print:process-queue')
    ->everySecond()
    ->withoutOverlapping();

Schedule::command('print:cancel-expired')
    ->everyMinute()
    ->withoutOverlapping();

Schedule::command('print:cleanup')
    ->everyFiveMinutes()
    ->withoutOverlapping();
