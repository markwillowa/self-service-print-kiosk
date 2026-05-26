<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CreditTransaction;
use App\Models\Maintenance;
use App\Models\Organization;
use App\Models\PrintJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

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

    public function maintenance(): View
    {
        return view('admin.maintenance', [
            'maintenances' => Maintenance::query()
                ->with(['company', 'organization', 'admin'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function storeMaintenance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'maintenance_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'issue_reported' => ['nullable', 'string'],
            'action_taken' => ['nullable', 'string'],
            'parts_replaced' => ['nullable', 'string'],
            'cost' => ['nullable', 'integer', 'min:0'],
            'printer_status' => ['nullable', 'string', 'max:255'],
            'coin_acceptor_status' => ['nullable', 'string', 'max:255'],
            'paper_stock' => ['nullable', 'string', 'max:255'],
            'ink_status' => ['nullable', 'string', 'max:255'],
            'network_status' => ['nullable', 'string', 'max:255'],
            'performed_at' => ['nullable', 'date'],
            'next_maintenance_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        Maintenance::create([
            ...$validated,
            'company_id' => Company::query()->latest()->value('id'),
            'organization_id' => Organization::query()->latest()->value('id'),
            'admin_id' => session('admin_id'),
            'cost' => $validated['cost'] ?? 0,
        ]);

        return redirect()
            ->route('admin.maintenance')
            ->with('success', 'Maintenance record saved.');
    }

    public function maintenanceReport(
        Maintenance $maintenance
    ): View {
        return view('admin.maintenance-report', [
            'maintenance' => $maintenance->load([
                'company',
                'organization',
                'admin',
            ]),
        ]);
    }

    public function users(): View
    {
        return view('admin.users', [
            'users' => Admin::query()
                ->latest()
                ->get(),
        ]);
    }

    public function storeUser(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:admins,username',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
            ],

            'pin_code' => [
                'required',
                'digits_between:4,6',
            ],
        ]);

        $organizationId = Admin::query()
            ->value('organization_id');

        Admin::create([
            'organization_id' => $organizationId,

            'name' => $validated['name'],

            'username' => $validated['username'],

            'password' => Hash::make(
                $validated['password']
            ),

            'pin_code' => Hash::make(
                $validated['pin_code']
            ),

            'is_super_admin' => false,
        ]);

        return back()->with('success', 'User created.');
    }
}
