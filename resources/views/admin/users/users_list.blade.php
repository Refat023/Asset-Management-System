@extends('master')

@section('content')

<style>
    /* Professional User List Styling */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .page-header h1 {
        margin: 0;
        font-size: 2.2rem;
        font-weight: 600;
    }

    .page-header .subtitle {
        margin: 0;
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--card-color);
    }

    .stat-card.total { --card-color: #667eea; }
    .stat-card.active { --card-color: #28a745; }
    .stat-card.admins { --card-color: #dc3545; }
    .stat-card.recent { --card-color: #ffc107; }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--card-color);
        margin: 0;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .stat-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 2rem;
        color: var(--card-color);
        opacity: 0.3;
    }

    .control-panel {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .control-panel .row > div {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .control-panel .row > div:last-child {
        margin-bottom: 0;
    }

    .search-input {
        border-radius: 25px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-search {
        border-radius: 25px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }

    .btn-create {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 25px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .users-table-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .users-table {
        margin-bottom: 0;
    }

    .users-table thead th {
        background: linear-gradient(135deg, #495057, #6c757d);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        padding: 1.2rem 1rem;
        font-size: 0.85rem;
    }

    .users-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f1f3f4;
    }

    .users-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .users-table tbody td {
        padding: 1.2rem 1rem;
        vertical-align: middle;
        border: none;
        font-size: 0.9rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-name {
        font-weight: 600;
        color: #495057;
        margin: 0;
    }

    .user-email {
        color: #6c757d;
        font-size: 0.85rem;
        margin: 0;
    }

    .role-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        margin-right: 0.25rem;
    }

    .role-admin { background-color: #dc3545; color: white; }
    .role-user { background-color: #28a745; color: white; }
    .role-manager { background-color: #17a2b8; color: white; }
    .role-editor { background-color: #ffc107; color: #212529; }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .action-btn {
        padding: 0.5rem;
        border: none;
        border-radius: 8px;
        transition: all 0.3s ease;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        text-decoration: none;
    }

    .btn-view {
        background-color: #17a2b8;
        color: white;
    }

    .btn-view:hover { color: white; }

    .btn-edit {
        background-color: #28a745;
        color: white;
    }

    .btn-edit:hover { color: white; }

    .btn-profile {
        background-color: #6f42c1;
        color: white;
    }

    .btn-profile:hover { color: white; }

    .btn-password {
        background-color: #fd7e14;
        color: white;
    }

    .btn-password:hover { color: white; }

    .btn-delete {
        background-color: #dc3545;
        color: white;
    }

    .btn-delete:hover { color: white; }

    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
    }

    .status-online { background-color: #28a745; }
    .status-offline { background-color: #6c757d; }

    .export-dropdown {
        border-radius: 25px;
    }

    .pagination-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        padding: 1rem;
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            text-align: center;
        }

        .control-panel .row > div {
            margin-bottom: 1rem;
        }

        .stats-cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .stats-cards {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1><i class="fa fa-users"></i> User Management</h1>
                <p class="subtitle">Manage system users, roles, and permissions</p>
            </div>
            <div class="col-md-4 text-end">
                @if(auth()->check() && auth()->user()->hasRole('admin'))
                    <a href="{{ route('create') }}" class="btn btn-create">
                        <i class="fa fa-plus"></i> Create New User
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-cards">
        <div class="stat-card total">
            <div class="stat-icon">
                <i class="fa fa-users"></i>
            </div>
            <div class="stat-label">Total Users</div>
            <h2 class="stat-number">{{ $users->count() }}</h2>
            <small class="text-muted">All registered users</small>
        </div>
        <div class="stat-card active">
            <div class="stat-icon">
                <i class="fa fa-user-check"></i>
            </div>
            <div class="stat-label">Active Users</div>
            <h2 class="stat-number">{{ $users->where('email_verified_at', '!=', null)->count() }}</h2>
            <small class="text-muted">Verified accounts</small>
        </div>
        <div class="stat-card admins">
            <div class="stat-icon">
                <i class="fa fa-user-shield"></i>
            </div>
            <div class="stat-label">Administrators</div>
            <h2 class="stat-number">{{ $users->filter(function($user) { return $user->hasRole('admin'); })->count() }}</h2>
            <small class="text-muted">Admin users</small>
        </div>
        <div class="stat-card recent">
            <div class="stat-icon">
                <i class="fa fa-user-plus"></i>
            </div>
            <div class="stat-label">Recent</div>
            <h2 class="stat-number">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</h2>
            <small class="text-muted">Last 30 days</small>
        </div>
    </div>

    <!-- Control Panel -->
    <div class="control-panel">
        <div class="row align-items-center">
            <div class="col-md-4">
                <form action="" method="GET" class="d-flex">
                    <input type="search" class="form-control search-input" name="search"
                           placeholder="Search users..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-search ms-2">
                        <i class="fa fa-search"></i> Search
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <form action="{{url('/users/export')}}" method="GET" class="d-flex">
                    <select name="type" class="form-control export-dropdown">
                        <option value="">Export Format</option>
                        <option value="xlsx">Excel (XLSX)</option>
                        <option value="csv">CSV</option>
                        <option value="xls">Excel (XLS)</option>
                    </select>
                    <button type="submit" class="btn btn-success ms-2">
                        <i class="fa fa-download"></i> Export
                    </button>
                </form>
            </div>
            <div class="col-md-4 text-end">
                <span class="text-muted">Showing {{ $users->count() }} users</span>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="users-table-container">
        <table class="table users-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Roles</th>
                    <th>Total Login</th>
                    <th>Login Status</th>
                    <th>Last Login</th>
                    <th>Joined</th>
                    <th>Last Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <div class="user-info">
                            @if($user->profile_photo)
                                <img src="{{ asset('uploads/profile_photos/' . $user->profile_photo) }}"
                                     alt="Avatar" class="user-avatar">
                            @else
                                <img src="{{ asset('images/user.png') }}" alt="Avatar" class="user-avatar">
                            @endif
                            <div>
                                <p class="user-name">{{ $user->name }}</p>
                                <p class="user-email">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($user->email_verified_at)
                            <span class="status-indicator status-online"></span>
                            <span class="text-success">Active</span>
                        @else
                            <span class="status-indicator status-offline"></span>
                            <span class="text-muted">Pending</span>
                        @endif
                    </td>
                    <td>
                        @foreach ($user->getRoleNames() as $role)
                            <span class="role-badge role-{{ strtolower($role) }}">{{ $role }}</span>
                        @endforeach
                        @if($user->getRoleNames()->isEmpty())
                            <span class="text-muted">No role assigned</span>
                        @endif
                    </td>
                    <td>
                        {{ $loginStats[$user->id]->total_logins ?? 0 }}
                    </td>
                    <td>
                        @php
                            $userLoginStats = $loginStats->get($user->id);
                            $status = ($userLoginStats && $userLoginStats->has_active_session) ? 'Online' : 'Offline';
                        @endphp
                        <span class="badge badge-{{ $status === 'Online' ? 'success' : 'secondary' }}">
                            {{ $status }}
                        </span>
                    </td>
                    <td>
                        @if($userLoginStats && $userLoginStats->last_login_at)
                            {{ \Carbon\Carbon::parse($userLoginStats->last_login_at)->format('M d, Y h:i A') }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <span class="text-muted">{{ $user->updated_at->diffForHumans() }}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="#" class="action-btn btn-view" title="View User"
                               onclick="viewUser({{ $user->id }})">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="#" class="action-btn btn-edit" title="Edit User"
                               onclick="editUser({{ $user->id }})">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button class="action-btn btn-profile" title="Edit Profile"
                                    onclick="editProfile({{ $user->id }})">
                                <i class="fa fa-user-edit"></i>
                            </button>
                            <button class="action-btn btn-password" title="Change Password"
                                    onclick="changePassword({{ $user->id }}, '{{ $user->name }}')">
                                <i class="fa fa-key"></i>
                            </button>
                            <button class="action-btn btn-delete" title="Delete User"
                                    onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- User View Modal -->
<div class="modal fade" id="userViewModal" tabindex="-1" aria-labelledby="userViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="modal-title" id="userViewModalLabel">
                    <i class="fa fa-user"></i> User Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userViewContent">
                <!-- User details will be loaded here -->
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                    <p>Loading user details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Edit Modal -->
<div class="modal fade" id="userEditModal" tabindex="-1" aria-labelledby="userEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                <h5 class="modal-title" id="userEditModalLabel">
                    <i class="fa fa-edit"></i> Edit User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userEditContent">
                <!-- Edit form will be loaded here -->
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                    <p>Loading edit form...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                <h5 class="modal-title" id="deleteConfirmModalLabel">
                    <i class="fa fa-trash"></i> Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>Are you sure you want to delete this user?</h5>
                    <p class="text-muted">User: <strong id="deleteUserName"></strong></p>
                    <p class="text-danger"><small>This action cannot be undone!</small></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fa fa-trash"></i> Delete User
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Profile Edit Modal -->
<div class="modal fade" id="profileEditModal" tabindex="-1" aria-labelledby="profileEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); color: white;">
                <h5 class="modal-title" id="profileEditModalLabel">
                    <i class="fa fa-user-edit"></i> Edit Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="profileEditContent">
                <!-- Profile edit form will be loaded here -->
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                    <p>Loading profile edit form...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #fd7e14 0%, #f0ad4e 100%); color: white;">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="fa fa-key"></i> Change Password
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="changePasswordContent">
                <!-- Password change form will be loaded here -->
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                    <p>Loading password change form...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// User Management Functions
let currentUserId = null;

// Check if Bootstrap is available
function isBootstrapAvailable() {
    return typeof bootstrap !== 'undefined';
}

// Show Modal with fallback
function showModal(modalElement) {
    if (isBootstrapAvailable()) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } else {
        // Fallback for older Bootstrap or jQuery
        $(modalElement).modal('show');
    }
}

// Hide Modal with fallback
function hideModal(modalElement) {
    if (isBootstrapAvailable()) {
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
            modalInstance.hide();
        }
    } else {
        // Fallback for older Bootstrap or jQuery
        $(modalElement).modal('hide');
    }
}

// View User Function
function viewUser(userId) {
    currentUserId = userId;

    const modalElement = document.getElementById('userViewModal');
    const contentElement = document.getElementById('userViewContent');

    // Reset content
    contentElement.innerHTML = `
        <div class="text-center">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p>Loading user details...</p>
        </div>
    `;

    // Show modal
    showModal(modalElement);

    // Load user details via AJAX
    fetch(`/users/${userId}/view`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            contentElement.innerHTML = data.html;
        } else {
            contentElement.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i>
                    ${data.message || 'Error loading user details.'}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        contentElement.innerHTML = `
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i>
                Error: Could not load user details. ${error.message}
            </div>
        `;
    });
}

// Edit User Function
function editUser(userId) {
    currentUserId = userId;

    const modalElement = document.getElementById('userEditModal');
    const contentElement = document.getElementById('userEditContent');

    // Reset content
    contentElement.innerHTML = `
        <div class="text-center">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p>Loading edit form...</p>
        </div>
    `;

    // Show modal
    showModal(modalElement);

    // Load edit form via AJAX
    fetch(`/users/${userId}/edit`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            contentElement.innerHTML = data.html;
            bindUserEditForm();
        } else {
            contentElement.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i>
                    ${data.message || 'Error loading edit form.'}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        contentElement.innerHTML = `
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i>
                Error: Could not load edit form. ${error.message}
            </div>
        `;
    });
}

// Profile Edit Function
function editProfile(userId) {
    currentUserId = userId;

    const modalElement = document.getElementById('profileEditModal');
    const contentElement = document.getElementById('profileEditContent');

    // Reset content
    contentElement.innerHTML = `
        <div class="text-center">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p>Loading profile edit form...</p>
        </div>
    `;

    // Show modal
    showModal(modalElement);

    // Load profile edit form via AJAX
    fetch(`/users/${userId}/profile/edit`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            contentElement.innerHTML = data.html;
        } else {
            contentElement.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i>
                    ${data.message || 'Error loading profile edit form.'}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        contentElement.innerHTML = `
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i>
                Error: Could not load profile edit form. ${error.message}
            </div>
        `;
    });
}

// Change Password Function
function changePassword(userId, userName) {
    currentUserId = userId;

    const modalElement = document.getElementById('changePasswordModal');
    const contentElement = document.getElementById('changePasswordContent');

    // Reset content
    contentElement.innerHTML = `
        <div class="text-center">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p>Loading password change form...</p>
        </div>
    `;

    // Show modal
    showModal(modalElement);

    // Load password change form via AJAX
    fetch(`/users/${userId}/password/edit`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            contentElement.innerHTML = data.html;
        } else {
            contentElement.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i>
                    ${data.message || 'Error loading password change form.'}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        contentElement.innerHTML = `
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i>
                Error: Could not load password change form. ${error.message}
            </div>
        `;
    });
}

// Delete User Function
function deleteUser(userId, userName) {
    currentUserId = userId;
    document.getElementById('deleteUserName').textContent = userName;

    // Show confirmation modal
    const modalElement = document.getElementById('deleteConfirmModal');
    showModal(modalElement);
}

// Confirm Delete
document.addEventListener('DOMContentLoaded', function() {
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (currentUserId) {
                // Show loading state
                this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Deleting...';
                this.disabled = true;

                // Submit delete request
                fetch(`/users/${currentUserId}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Close modal
                        hideModal(document.getElementById('deleteConfirmModal'));

                        // Show success message
                        showAlert('success', 'User deleted successfully!');

                        // Reload page
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showAlert('danger', data.message || 'Error deleting user.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', `Error: Could not delete user. ${error.message}`);
                })
                .finally(() => {
                    // Reset button
                    this.innerHTML = '<i class="fa fa-trash"></i> Delete User';
                    this.disabled = false;
                });
            }
        });
    }
});

// Show Alert Function
function showAlert(type, message) {
    // Remove any existing alerts
    const existingAlerts = document.querySelectorAll('.position-fixed.alert');
    existingAlerts.forEach(alert => alert.remove());

    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
            <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', alertHtml);

    // Auto remove after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.position-fixed.alert');
        alerts.forEach(alert => {
            if (alert) alert.remove();
        });
    }, 5000);
}

function bindUserEditForm() {
    const form = document.getElementById('userEditForm');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        clearValidation();

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;

        if (!validateForm()) {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            return;
        }

        const formData = new FormData(this);
        submitUserUpdate(formData, currentUserId);
    });
}

function validateForm() {
    let isValid = true;

    const name = document.getElementById('editName');
    if (!name.value.trim()) {
        showFieldError(name, 'Name is required');
        isValid = false;
    }

    const email = document.getElementById('editEmail');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim()) {
        showFieldError(email, 'Email is required');
        isValid = false;
    } else if (!emailRegex.test(email.value)) {
        showFieldError(email, 'Please enter a valid email address');
        isValid = false;
    }

    const password = document.getElementById('editPassword');
    const passwordConfirm = document.getElementById('editPasswordConfirm');

    if (password.value) {
        if (password.value.length < 8) {
            showFieldError(password, 'Password must be at least 8 characters');
            isValid = false;
        }

        if (password.value !== passwordConfirm.value) {
            showFieldError(passwordConfirm, 'Passwords do not match');
            isValid = false;
        }
    }

    const roleCheckboxes = document.querySelectorAll('input[name="role[]"]');
    const roleChecked = Array.from(roleCheckboxes).some(cb => cb.checked);

    if (!roleChecked) {
        const roleContainer = document.querySelector('.role-options');
        roleContainer.style.borderColor = '#dc3545';
        if (!roleContainer.parentNode.querySelector('.text-danger')) {
            roleContainer.insertAdjacentHTML('afterend', '<div class="text-danger small mt-1">Please select at least one role</div>');
        }
        isValid = false;
    }

    return isValid;
}

function showFieldError(field, message) {
    field.classList.add('is-invalid');
    const feedback = field.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = message;
        feedback.style.display = 'block';
    }
}

function clearValidation() {
    document.querySelectorAll('.form-control').forEach(field => {
        field.classList.remove('is-invalid', 'is-valid');
    });

    document.querySelectorAll('.invalid-feedback').forEach(feedback => {
        feedback.style.display = 'none';
    });

    const roleContainer = document.querySelector('.role-options');
    if (roleContainer) {
        roleContainer.style.borderColor = '#e9ecef';
        const roleError = roleContainer.parentNode.querySelector('.text-danger');
        if (roleError) {
            roleError.remove();
        }
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('currentAvatar').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removePhoto() {
    document.getElementById('currentAvatar').src = '{{ asset("images/user.png") }}';
    document.getElementById('profilePhoto').value = '';
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const btn = field.nextElementSibling.querySelector('i');

    if (field.type === 'password') {
        field.type = 'text';
        btn.classList.remove('fa-eye');
        btn.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        btn.classList.remove('fa-eye-slash');
        btn.classList.add('fa-eye');
    }
}

// Update User Form Submit (for when edit form is loaded)
function submitUserUpdate(formData, userId) {
    const submitBtn = document.querySelector('#userEditForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Updating...';
    submitBtn.disabled = true;

    fetch(`/users/${userId}/update`, {
        method: 'POST', // Laravel expects POST with _method=PUT
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Close modal
            hideModal(document.getElementById('userEditModal'));

            // Show success message
            showAlert('success', 'User updated successfully!');

            // Reload page
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Show validation errors
            if (data.errors) {
                let errorMessage = 'Validation errors:<br>';
                Object.values(data.errors).forEach(error => {
                    errorMessage += `• ${error[0]}<br>`;
                });
                const existingAlerts = document.querySelectorAll('#userEditContent .alert-danger');
                existingAlerts.forEach(alert => alert.remove());

                document.getElementById('userEditContent').insertAdjacentHTML('afterbegin', `
                    <div class="alert alert-danger">${errorMessage}</div>
                `);
            } else {
                showAlert('danger', data.message || 'Error updating user.');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', `Error: Could not update user. ${error.message}`);
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Submit Profile Update
function submitProfileUpdate(formData, userId) {
    const submitBtn = document.querySelector('#profileEditForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Updating...';
    submitBtn.disabled = true;

    fetch(`/users/${userId}/profile/update`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Close modal
            hideModal(document.getElementById('profileEditModal'));

            // Show success message
            showAlert('success', 'Profile updated successfully!');

            // Reload page
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Show validation errors
            if (data.errors) {
                let errorMessage = 'Validation errors:<br>';
                Object.values(data.errors).forEach(error => {
                    errorMessage += `• ${error[0]}<br>`;
                });
                const existingAlerts = document.querySelectorAll('#profileEditContent .alert-danger');
                existingAlerts.forEach(alert => alert.remove());

                document.getElementById('profileEditContent').insertAdjacentHTML('afterbegin', `
                    <div class="alert alert-danger">${errorMessage}</div>
                `);
            } else {
                showAlert('danger', data.message || 'Error updating profile.');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', `Error: Could not update profile. ${error.message}`);
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Submit Password Change
function submitPasswordChange(formData, userId) {
    const submitBtn = document.querySelector('#changePasswordForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Changing...';
    submitBtn.disabled = true;

    fetch(`/users/${userId}/password/update`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Close modal
            hideModal(document.getElementById('changePasswordModal'));

            // Show success message
            showAlert('success', 'Password changed successfully!');

            // Reload page
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Show validation errors
            if (data.errors) {
                let errorMessage = 'Validation errors:<br>';
                Object.values(data.errors).forEach(error => {
                    errorMessage += `• ${error[0]}<br>`;
                });
                const existingAlerts = document.querySelectorAll('#changePasswordContent .alert-danger');
                existingAlerts.forEach(alert => alert.remove());

                document.getElementById('changePasswordContent').insertAdjacentHTML('afterbegin', `
                    <div class="alert alert-danger">${errorMessage}</div>
                `);
            } else {
                showAlert('danger', data.message || 'Error changing password.');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', `Error: Could not change password. ${error.message}`);
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('User management page loaded');

    // Add CSRF token to all AJAX requests if jQuery is available
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken && typeof $ !== 'undefined') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            }
        });
    }
});
</script>

@endsection
