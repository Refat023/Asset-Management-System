@extends('master')
@section('content')
<style>
    .log-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .log-table {
        margin-bottom: 0;
    }

    .log-table thead th {
        background-color: #495057;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        padding: 1rem 0.75rem;
        font-size: 0.8rem;
    }

    .log-table tbody tr {
        border-bottom: 1px solid #f1f3f4;
    }

    .log-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .log-table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .filter-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .filter-section .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        color: #495057;
    }

    .badge-device {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-desktop {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .badge-phone {
        background-color: #d4edda;
        color: #155724;
    }

    .badge-tablet {
        background-color: #fff3cd;
        color: #856404;
    }

    .stat-card {
        background: white;
        border-left: 4px solid #007bff;
        padding: 1.5rem;
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
        margin-bottom: 1rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #007bff;
        margin: 0.5rem 0;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .user-header {
        background: linear-gradient(135deg, #343a40 0%, #495057 100%);
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    .user-info h2 {
        margin: 0 0 0.5rem 0;
        font-weight: 700;
    }

    .user-info p {
        margin: 0;
        opacity: 0.9;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
</style>

<div class="container-fluid">
    <!-- User Header -->
    <div class="user-header">
        <div class="user-avatar">
            <i class="fa fa-user"></i>
        </div>
        <div class="user-info">
            <h2>{{ $user->name }}</h2>
            <p><i class="fa fa-envelope"></i> {{ $user->email }}</p>
        </div>
        <div class="ms-auto">
            <a href="{{ route('user.logs.index') }}" class="btn btn-light btn-sm">
                <i class="fa fa-arrow-left"></i> Back to All Logs
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Logins</div>
            <div class="stat-number">{{ $totalLogins }}</div>
        </div>
        <div class="stat-card" style="border-left-color: #28a745;">
            <div class="stat-label">This Month</div>
            <div class="stat-number" style="color: #28a745;">{{ $thisMonth }}</div>
        </div>
        @if($lastLogin)
            <div class="stat-card" style="border-left-color: #17a2b8;">
                <div class="stat-label">Last Login</div>
                <div class="stat-number" style="color: #17a2b8; font-size: 1rem;">
                    {{ $lastLogin->login_at->format('M d, Y') }}
                </div>
                <small class="text-muted">{{ $lastLogin->login_at->diffForHumans() }}</small>
            </div>
        @endif
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('user.logs.user', $user->id) }}" class="form-inline">
            <div class="row w-100">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-search"></i> Filter
                        </button>
                        <a href="{{ route('user.logs.user', $user->id) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa fa-refresh"></i> Clear
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Login History Table -->
    <div class="log-card">
        <div class="card-header" style="background-color: #f8f9fa; padding: 1rem; border-bottom: 1px solid #dee2e6;">
            <h6 class="mb-0">Login Activity</h6>
        </div>
        <div class="table-responsive">
            <table class="table log-table">
                <thead>
                    <tr>
                        <th>Login Date & Time</th>
                        <th>IP Address</th>
                        <th>Browser</th>
                        <th>OS</th>
                        <th>Device</th>
                        <th>Session Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                {{ $log->login_at->format('M d, Y H:i:s') }}
                                <br>
                                <small class="text-muted">{{ $log->login_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <code>{{ $log->ip_address ?? 'N/A' }}</code>
                            </td>
                            <td>{{ $log->browser ?? 'Unknown' }}</td>
                            <td>{{ $log->os ?? 'Unknown' }}</td>
                            <td>
                                <span class="badge-device badge-{{ strtolower($log->device ?? 'unknown') }}">
                                    {{ $log->device ?? 'Unknown' }}
                                </span>
                            </td>
                            <td>
                                @if($log->logout_at)
                                    <span class="badge bg-success">
                                        {{ $log->logout_at->diffInMinutes($log->login_at) }} mins
                                    </span>
                                @else
                                    <span class="badge bg-warning">Active</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fa fa-inbox"></i> No login records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <div>
                <small class="text-muted">
                    Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} logins
                </small>
            </div>
            <div>
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
