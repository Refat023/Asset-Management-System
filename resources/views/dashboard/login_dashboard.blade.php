@extends('master')
@section('content')
<style>
    .dashboard-container {
        padding: 2rem 0;
    }

    .page-title {
        margin-bottom: 2rem;
    }

    .page-title h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .page-title p {
        color: #7f8c8d;
        font-size: 0.95rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.total {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card.month {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-card.last {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.8;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
    }

    .recent-activity {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .activity-header {
        background: #f8f9fa;
        padding: 1.5rem;
        border-bottom: 2px solid #e9ecef;
    }

    .activity-header h3 {
        margin: 0;
        color: #2c3e50;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .activity-table {
        width: 100%;
        margin-bottom: 0;
    }

    .activity-table thead th {
        background-color: #495057;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        padding: 1rem 0.75rem;
        font-size: 0.8rem;
    }

    .activity-table tbody tr {
        border-bottom: 1px solid #f1f3f4;
        transition: background-color 0.2s ease;
    }

    .activity-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .activity-table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .badge-device {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-desktop {
        background-color: #cfe2ff;
        color: #084298;
    }

    .badge-phone {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .badge-tablet {
        background-color: #fff3cd;
        color: #664d03;
    }

    .badge-active {
        background-color: #d1e7dd;
        color: #0f5132;
        padding: 0.3rem 0.6rem;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .no-activity {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .no-activity i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .duration-badge {
        background-color: #e7f3ff;
        color: #0066cc;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-number {
            font-size: 1.8rem;
        }

        .activity-table tbody td {
            font-size: 0.75rem;
            padding: 0.75rem 0.5rem;
        }

        .badge-device {
            padding: 0.3rem 0.6rem;
            font-size: 0.65rem;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="page-title">
        <h2><i class="fas fa-chart-line"></i> Login Activity Dashboard</h2>
        <p>Monitor your login history and account access</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <!-- Total Logins Card -->
        <div class="stat-card total">
            <div class="stat-icon">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="stat-number">{{ $totalLogins }}</div>
            <div class="stat-label">Total Logins</div>
        </div>

        <!-- This Month Card -->
        <div class="stat-card month">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-number">{{ $thisMonthLogins }}</div>
            <div class="stat-label">Logins This Month</div>
        </div>

        <!-- Last Login Card -->
        <div class="stat-card last">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">
                @if($lastLogin)
                    {{ $lastLogin->login_at->diffForHumans() }}
                @else
                    <span style="font-size: 1rem;">N/A</span>
                @endif
            </div>
            <div class="stat-label">Last Login</div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-activity">
        <div class="activity-header">
            <h3><i class="fas fa-history"></i> Recent Login Activity</h3>
        </div>

        @if($recentLogins->isEmpty())
            <div class="no-activity">
                <i class="fas fa-inbox"></i>
                <p>No login history found</p>
            </div>
        @else
            <table class="table activity-table">
                <thead>
                    <tr>
                        <th>Login Date & Time</th>
                        <th>Device</th>
                        <th>Browser</th>
                        <th>Operating System</th>
                        <th>IP Address</th>
                        <th>Session Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLogins as $log)
                        <tr>
                            <td>
                                <strong>{{ $log->login_at->format('M d, Y') }}</strong><br>
                                <small class="text-muted">{{ $log->login_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <span class="badge-device badge-{{ strtolower($log->device) }}">
                                    <i class="fas fa-{{ $log->device === 'Desktop' ? 'desktop' : ($log->device === 'Phone' ? 'mobile' : 'tablet') }}"></i>
                                    {{ $log->device }}
                                </span>
                            </td>
                            <td>{{ $log->browser ?? 'Unknown' }}</td>
                            <td>{{ $log->os ?? 'Unknown' }}</td>
                            <td>
                                <code style="background: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 3px;">
                                    {{ $log->ip_address }}
                                </code>
                            </td>
                            <td>
                                @if($log->logout_at)
                                    <span class="duration-badge">
                                        {{ $log->logout_at->diffInMinutes($log->login_at) }} min
                                    </span>
                                @else
                                    <span class="badge-active">
                                        <i class="fas fa-circle"></i> Active
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Additional Info -->
    <div style="margin-top: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #667eea;">
        <p style="margin: 0; color: #495057;">
            <i class="fas fa-info-circle"></i> This dashboard shows your login activity for security monitoring purposes. 
            If you notice any unusual activity, please contact your administrator immediately.
        </p>
    </div>
</div>
@endsection
