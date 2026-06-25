<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CreditTransaction;
use App\Models\Maintenance;
use App\Models\Organization;
use App\Models\PrintJob;
use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Process\Process;

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
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function printJobs()
    {
        return view('admin.print-jobs', [
            'jobs' => PrintJob::query()
                ->latest()
                ->paginate(10),
        ]);
    }

    public function coins(): View
    {
        return view('admin.coins', [
            'transactions' => CreditTransaction::query()
                ->latest()
                ->paginate(10),

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

    public function updatePricing(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'black_price_per_page' => ['required', 'integer', 'min:1', 'max:999'],
            'color_price_per_page' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $company = Company::query()
            ->latest()
            ->firstOrFail();

        if ($company->kiosk_name !== 'Self-Service Print') {
            return back()->withErrors([
                'pricing' => 'Pricing can only be edited for Self-Service Print.',
            ]);
        }

        $company->update([
            'black_price_per_page' => $validated['black_price_per_page'],
            'color_price_per_page' => $validated['color_price_per_page'],
            'allow_custom_pricing' => true,
        ]);

        return back()->with('success', 'Pricing updated successfully.');
    }

    public function systemUpdate(): RedirectResponse
    {
        $projectPath = '/var/www/self-service-print-kiosk';

        $gitPull = new Process([
            'git',
            'pull',
        ], $projectPath);

        $gitPull->setTimeout(300);
        $gitPull->run();

        if (! $gitPull->isSuccessful()) {
            return back()->withErrors([
                'system_update' =>
                    'Git pull failed: ' . $gitPull->getErrorOutput(),
            ]);
        }

        $output = trim(
            $gitPull->getOutput() .
            "\n" .
            $gitPull->getErrorOutput()
        );

        $upToDate =
            str_contains($output, 'Already up to date') ||
            str_contains($output, 'Already up-to-date');

        if ($upToDate) {
            return back()->with(
                'system_update',
                'System is already up to date.'
            );
        }

        $composerInstall = new Process([
            'composer',
            'install',
            '--no-dev',
            '--optimize-autoloader',
        ], $projectPath);

        $composerInstall->setTimeout(900);
        $composerInstall->run();

        if (! $composerInstall->isSuccessful()) {
            return back()->withErrors([
                'system_update' =>
                    'Update downloaded, but Composer install failed: ' .
                    $composerInstall->getErrorOutput(),
            ]);
        }

        $migrate = new Process([
            'php',
            'artisan',
            'migrate',
            '--force',
        ], $projectPath);

        $migrate->setTimeout(300);
        $migrate->run();

        if (! $migrate->isSuccessful()) {
            return back()->withErrors([
                'system_update' =>
                    'Update downloaded, but database migration failed: ' .
                    $migrate->getErrorOutput(),
            ]);
        }

        $optimizeClear = new Process([
            'php',
            'artisan',
            'optimize:clear',
        ], $projectPath);

        $optimizeClear->setTimeout(120);
        $optimizeClear->run();

        if (! $optimizeClear->isSuccessful()) {
            return back()->withErrors([
                'system_update' =>
                    'Update downloaded, but optimize clear failed: ' .
                    $optimizeClear->getErrorOutput(),
            ]);
        }

        $npmBuild = new Process([
            'npm',
            'run',
            'build',
        ], $projectPath);

        $npmBuild->setTimeout(900);
        $npmBuild->run();

        if (! $npmBuild->isSuccessful()) {
            return back()->withErrors([
                'system_update' =>
                    'Update downloaded, but NPM build failed: ' .
                    $npmBuild->getErrorOutput(),
            ]);
        }

        $optimize = new Process([
            'php',
            'artisan',
            'optimize',
        ], $projectPath);

        $optimize->setTimeout(120);
        $optimize->run();

        if (! $optimize->isSuccessful()) {
            return back()->withErrors([
                'system_update' =>
                    'Update downloaded, but optimize failed: ' .
                    $optimize->getErrorOutput(),
            ]);
        }

        return back()->with(
            'system_update',
            'System update completed. Please restart the device to apply changes.'
        );
    }

    public function systemReboot(): RedirectResponse
    {
        $process = new Process([
            'sudo',
            '-n',
            '/usr/sbin/reboot',
        ]);

        $process->run();

        if (! $process->isSuccessful()) {
            return back()->withErrors([
                'system_update' =>
                    'Reboot failed: ' . $process->getErrorOutput(),
            ]);
        }

        return back();
    }

    public function vouchers(): View
    {
        $vouchers = Voucher::query()
            ->latest()
            ->paginate(10);

        return view('admin.vouchers', [
            'vouchers' => $vouchers,
        ]);
    }

    public function storeVoucher(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'regex:/^[0-9]+$/',
                'max:12',
                'unique:vouchers,code',
            ],
            'amount' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],
        ]);

        Voucher::create([
            'code' => trim($validated['code']),
            'amount' => $validated['amount'],
        ]);

        return back()->with([
            'success' => 'Voucher created successfully.',
        ]);
    }
}
