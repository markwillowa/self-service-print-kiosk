<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CreditTransaction;
use App\Models\Organization;
use App\Models\PrintJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        $period = $request->query('period', 'day');

        $startDate = match ($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            default => now()->startOfDay(),
        };

        $endDate = now();

        return view('admin.dashboard', [
            'period' => $period,

            'totalCredits' => CreditTransaction::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount'),

            'completedJobs' => PrintJob::query()
                ->where('status', 'completed')
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->count(),

            'queuedJobs' => PrintJob::query()
                ->where('status', 'queued')
                ->count(),

            'failedJobs' => PrintJob::query()
                ->where('status', 'failed')
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->count(),

            'cancelledJobs' => PrintJob::query()
                ->where('status', 'cancelled')
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->count(),

            'recentJobs' => PrintJob::query()
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }

    public function printJobs(): View
    {
        return view('admin.print-jobs', [
            'jobs' => PrintJob::query()
                ->latest()
                ->paginate(20),
        ]);
    }

    public function coins(): View
    {
        return view('admin.coins', [
            'transactions' => CreditTransaction::query()
                ->latest()
                ->paginate(20),

            'totalCredits' => CreditTransaction::sum('amount'),
        ]);
    }

    public function settings(): View
    {
        return view('admin.settings');
    }

    public function logs(): View
    {
        $logDirectory = storage_path('logs');

        $logFiles = collect(File::files($logDirectory))
            ->filter(function ($file) {
                return Str::endsWith($file->getFilename(), '.log');
            })
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            });

        $latestLogFile = $logFiles->first();

        $logs = [];
        $logFileName = null;

        if ($latestLogFile) {
            $logFileName = $latestLogFile->getFilename();

            $content = File::get($latestLogFile->getPathname());

            $lines = explode("\n", $content);

            $logs = array_reverse(
                array_filter($lines)
            );

            $logs = array_slice($logs, 0, 300);
        }

        return view('admin.logs', [
            'logs' => $logs,
            'logFileName' => $logFileName,
        ]);
    }

    public function clearLogs(): RedirectResponse
    {
        $logDirectory = storage_path('logs');

        $logFiles = collect(File::files($logDirectory))
            ->filter(function ($file) {
                return str_ends_with(
                    $file->getFilename(),
                    '.log'
                );
            });

        foreach ($logFiles as $file) {
            File::put($file->getPathname(), '');
        }

        return back();
    }

    public function profile(): View
    {
        return view('admin.profile', [
            'company' => Company::query()->latest()->first(),
            'organization' => Organization::query()->latest()->first(),
        ]);
    }
}
