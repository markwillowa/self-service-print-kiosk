<?php

namespace App\Services;

use App\Models\PrintJob;
use Illuminate\Support\Facades\Cache;

class KioskSessionLock
{
    private const CACHE_KEY = 'active_kiosk_print_job';

    public function lock(PrintJob $printJob): void
    {
        Cache::put(
            self::CACHE_KEY,
            $printJob->uuid,
            now()->addMinutes(10)
        );
    }

    public function unlock(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function activeJobUuid(): ?string
    {
        return Cache::get(self::CACHE_KEY);
    }

    public function isLocked(): bool
    {
        return Cache::has(self::CACHE_KEY);
    }
}
