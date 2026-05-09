<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use App\Models\PrintJob;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'totalCredits' => CreditTransaction::sum('amount'),
            'completedJobs' => PrintJob::where('status', 'completed')->count(),
            'queuedJobs' => PrintJob::where('status', 'queued')->count(),
            'failedJobs' => PrintJob::where('status', 'failed')->count(),
            'recentJobs' => PrintJob::latest()->take(10)->get(),
        ]);
    }
}
