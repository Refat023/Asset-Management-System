<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display login logs for all users (Admin only)
     */
    public function index(Request $request)
    {
        // Check if user is admin
        $user = auth()->user();
        if (!$user || !$user->hasRole('admin')) {
            abort(403, 'Unauthorized access');
        }

        $search = $request->input('search');
        $userId = $request->input('user_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = UserLog::with('user')->where('action', 'login');

        // Filter by user
        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Search by user email or name
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('email', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }

        // Filter by date range
        if ($dateFrom) {
            $query->whereDate('login_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('login_at', '<=', $dateTo);
        }

        $logs = $query->latest('login_at')->paginate(50);
        $users = User::orderBy('name')->get();

        return view('admin.user_logs.index', [
            'logs' => $logs,
            'users' => $users,
            'search' => $search,
            'userId' => $userId,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    /**
     * Display login logs for current user
     */
    public function myLogs(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = UserLog::where('user_id', auth()->id())
                        ->where('action', 'login');

        // Filter by date range
        if ($dateFrom) {
            $query->whereDate('login_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('login_at', '<=', $dateTo);
        }

        $logs = $query->latest('login_at')->paginate(50);

        // Get login statistics
        $totalLogins = UserLog::where('user_id', auth()->id())
                              ->where('action', 'login')
                              ->count();

        $thisMonth = UserLog::where('user_id', auth()->id())
                            ->where('action', 'login')
                            ->whereMonth('login_at', now()->month)
                            ->count();

        $lastLogin = UserLog::where('user_id', auth()->id())
                            ->where('action', 'login')
                            ->latest('login_at')
                            ->first();

        return view('admin.user_logs.my_logs', [
            'logs' => $logs,
            'totalLogins' => $totalLogins,
            'thisMonth' => $thisMonth,
            'lastLogin' => $lastLogin,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    /**
     * Display user-specific login logs (Admin only)
     */
    public function userLogs(Request $request, $userId)
    {
        // Check if user is admin
        $user = auth()->user();
        if (!$user || !$user->hasRole('admin')) {
            abort(403, 'Unauthorized access');
        }

        $user = User::findOrFail($userId);
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = UserLog::where('user_id', $userId)
                        ->where('action', 'login');

        // Filter by date range
        if ($dateFrom) {
            $query->whereDate('login_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('login_at', '<=', $dateTo);
        }

        $logs = $query->latest('login_at')->paginate(50);

        // Get login statistics
        $totalLogins = UserLog::where('user_id', $userId)
                              ->where('action', 'login')
                              ->count();

        $thisMonth = UserLog::where('user_id', $userId)
                            ->where('action', 'login')
                            ->whereMonth('login_at', now()->month)
                            ->count();

        $lastLogin = UserLog::where('user_id', $userId)
                            ->where('action', 'login')
                            ->latest('login_at')
                            ->first();

        return view('admin.user_logs.user_logs', [
            'user' => $user,
            'logs' => $logs,
            'totalLogins' => $totalLogins,
            'thisMonth' => $thisMonth,
            'lastLogin' => $lastLogin,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    /**
     * Export login logs as CSV
     */
    public function export(Request $request)
    {
        // Check if user is admin
        $user = auth()->user();
        if (!$user || !$user->hasRole('admin')) {
            abort(403, 'Unauthorized access');
        }

        $userId = $request->input('user_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = UserLog::with('user')->where('action', 'login');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($dateFrom) {
            $query->whereDate('login_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('login_at', '<=', $dateTo);
        }

        $logs = $query->latest('login_at')->get();

        $csv = "User Email,User Name,Login Date,IP Address,Browser,OS,Device\n";

        foreach ($logs as $log) {
            $csv .= "\"{$log->user->email}\",\"{$log->user->name}\",\"{$log->login_at}\",\"{$log->ip_address}\",\"{$log->browser}\",\"{$log->os}\",\"{$log->device}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="login_logs_' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Display user login dashboard with statistics
     */
    public function dashboard()
    {
        $user = auth()->user();
        $userId = $user->id;

        // Get total logins
        $totalLogins = UserLog::where('user_id', $userId)
                             ->where('action', 'login')
                             ->count();

        // Get logins this month
        $thisMonthLogins = UserLog::where('user_id', $userId)
                                  ->where('action', 'login')
                                  ->whereMonth('login_at', now()->month)
                                  ->whereYear('login_at', now()->year)
                                  ->count();

        // Get last login
        $lastLogin = UserLog::where('user_id', $userId)
                           ->where('action', 'login')
                           ->latest('login_at')
                           ->first();

        // Get recent logins (last 10)
        $recentLogins = UserLog::where('user_id', $userId)
                              ->where('action', 'login')
                              ->latest('login_at')
                              ->take(10)
                              ->get();

        return view('dashboard.login_dashboard', [
            'totalLogins' => $totalLogins,
            'thisMonthLogins' => $thisMonthLogins,
            'lastLogin' => $lastLogin,
            'recentLogins' => $recentLogins,
        ]);
    }
}
