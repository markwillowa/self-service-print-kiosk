<?php

namespace App\Services;

use App\Models\PrintJob;

class PrinterService
{
    public function print(PrintJob $printJob): bool
    {
        sleep(2);

        $printJob->update([
            'status' => 'completed',
        ]);

        return true;
    }
}
