<!DOCTYPE html>
<html lang="en">



<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>Asset Management</title>

    <!-- Bootstrap -->
    <link href="{{ asset('backend/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('backend/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('backend/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ asset('backend/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="{{ asset('backend/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}"
        rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{ asset('backend/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet" />
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('backend/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('backend/build/css/custom.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/build/css/responsive.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Load Bootstrap JS and its dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <style>
        .navbar-profile-photo {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>


</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view ">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('uploads/AMS_White.png') }}" alt="Logo" style="width: 100%; height: auto;">
                        </a>
                    </div>

                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <div class="profile clearfix ">

                        <div class="profile_info">
                            <h6 class="text-white">{{ Auth::user()->name }}</h6>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu ">
                        <div class="menu_section">
                            <h3>General</h3>
                            <ul class="nav side-menu">
                                <li><a href="{{ route('home') }}"><i class="fa fa-home"></i>Dashboard</a></li>

                                <li><a><i class="fa fa-tasks"></i>Assets<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('store') }}"><i class="fa fa-laptop"></i>List All</a>
                                        </li>
                                        <li><a href="{{ route('issue_list') }}"><i class="fa fa-circle "></i>Issued
                                                List</a></li>
                                        <li><a href="{{ route('instock_list') }}"><i class="fa fa-circle "></i>Instock
                                                List</a></li>
                                        <li><a href="{{ route('store_delete_list') }}"><i
                                                    class="fa fa-close"></i>Delete List</a></li>
                                        <li><a href="{{ route('store_archive_list') }}"><i
                                                    class="fa fa-archive"></i>Archive List</a></li>

                                        <li><a href="{{ route('maintenance_view') }}"><i
                                                    class="fa fa-gears"></i>Maintenance
                                                List</a></li>
                                        <li><a href="{{ route('wastproduct_list') }}"><i class="fa fa-gears"></i>Waste
                                                Product</a></li>
                                        <li><a href="{{ route('stock_summary') }}"><i class="fa fa-bar-chart-o"></i>Stock Summary</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-exchange"></i>Transfer Management<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('transfer') }}"><i class="fa fa-plus"></i>New Transfer
                                                Request</a></li>
                                        <li><a href="{{ route('transfer_requests') }}"><i class="fa fa-list"></i>All
                                                Transfer Requests</a></li>
                                        <li>
                                            <a href="{{ route('pending_transfer_requests') }}">
                                                <i class="fa fa-clock-o"></i>Pending Approvals
                                                @php
                                                    $pendingCount = \App\Models\TransferRequest::pending()->count();
                                                @endphp
                                                @if ($pendingCount > 0)
                                                    <span class="badge badge-danger"
                                                        style="background-color: #dc3545; color: white; padding: 2px 6px; border-radius: 10px; font-size: 10px;">{{ $pendingCount }}</span>
                                                @endif
                                            </a>
                                        </li>
                                        <li><a href="{{ route('borrowed_items') }}"><i
                                                    class="fa fa-handshake-o"></i>Borrowed Items</a></li>
                                        <li><a href="{{ route('transfer_list') }}"><i
                                                    class="fa fa-history"></i>Transfer History</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('backup.index') }}"><i class="fa fa-database"></i>Database
                                        Backup</a>
                                </li>
                                <li><a><i class="fa fa-tasks"></i>Pasword Management<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('computer_pass') }}">Computer</a></li>
                                        <li><a href="{{ route('mail_pass') }}">Mail</a></li>
                                        <li><a href="{{ route('camera_pass') }}">Camera</a></li>
                                        <li><a href="{{ route('internet_pass') }}">internet</a></li>
                                        <li><a href="{{ route('ding_pass') }}">Ding</a></li>
                                        <li><a href="{{ route('others_pass') }}">Othres</a></li>
                                    </ul>
                                </li>

                                <li><a><i class="fa fa-tasks"></i>Employee<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('employee_list') }}"><i class="fa fa-group"></i>Active
                                                Employee</a></li>
                                        <li><a href="{{ route('inactive_list') }}"><i
                                                    class="fa fa-user-times"></i>Inactive Employee</a></li>
                                        <li><a href="{{ route('delete_list') }}"><i class="fa fa-trash"></i>Delete
                                                Employee</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('users') }}"><i class="fa fa-user"></i>User List</a></li>
                                <li><a><i class="fa fa-table"></i> Import Data <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('employee_import') }}">Employee Data</a></li>
                                        <li><a href="{{ route('store_import') }}">Assets data</a></li>
                                    </ul>
                                </li>

                                <li><a><i class="fa fa-institution (alias)"></i> Consumable<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('productdetails') }}">Product Details</a></li>
                                        <li><a href="{{ route('consumableIssue') }}">Issue Details</a></li>
                                        <li><a href="{{ route('Inventory') }}">Inventory</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-bar-chart-o"></i> References <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('department') }}">Departmet</a></li>
                                        <li><a href="{{ route('designation') }}">Designation</a></li>
                                        <li><a href="{{ route('supplier') }}">Supplier</a></li>
                                        <li><a href="{{ route('brand') }}">Brand</a></li>
                                        <li><a href="{{ route('status') }}">Status</a></li>
                                        <li><a href="{{ route('size') }}">Size Mesurment</a></li>
                                        <li><a href="{{ route('color') }}">Color</a></li>
                                        <li><a href="{{ route('company.list') }}">Company</a></li>
                                        <li><a href="{{ route('product_type') }}">Asset Type</a></li>
                                    </ul>
                                </li>

                                <li><a><i class="fa fa-clone"></i>Permision<span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        @can('role-list')
                                            <li><a href="{{ route('roles.index') }}">Manage Role</a></li>
                                        @endcan
                                        <li><a href="fixed_footer.html">Manage Employee</a></li>
                                    </ul>
                                </li>

                                <li><a><i class="fa fa-gear"></i>Settings<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('profile') }}">Profile</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a href="{{ route('logout') }}" data-toggle="tooltip" data-placement="top"
                            onclick="event.preventDefault();
              document.getElementById('logout-form').submit();"
                            class="dropdown-item ai-icon">
                            <span class="glyphicon glyphicon-off text-white" aria-hidden="true"></span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>



            <!-- top navigation -->
            <div class="top_nav ">
                <div class="nav_menu">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <nav class="nav navbar-nav">
                        <ul class="navbar-right">
                            <li class="nav-item dropdown" style="padding-left: 15px;">
                                <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true"
                                    id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">

                                    @if (Auth::user()->picture && file_exists(public_path('uploads/profile_photos/' . Auth::user()->picture)))
                                        <img src="{{ asset('uploads/profile_photos/' . Auth::user()->picture) }}"
                                            alt="Profile Photo" class="navbar-profile-photo">
                                    @else
                                        <img src="{{ asset('uploads/profile_photos/default.png') }}"
                                            alt="Default Profile Photo" class="navbar-profile-photo">
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-usermenu pull-right"
                                    aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="javascript:;">
                                        {{ Auth::user()->name }}
                                    </a>

                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();"
                                        class="dropdown-item ai-icon">

                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
                                            width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12">
                                            </line>
                                        </svg>

                                        <span class="ml-2">Logout</span>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </nav>


                </div>
                @yield('content')
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops! Something went wrong.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- /top navigation -->

            <!-- page content -->


            <!-- footer content -->
            <!-- <footer>
          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer> -->
            <!-- /footer content -->
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('backend/vendors/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('backend/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('backend/vendors/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('backend/vendors/nprogress/nprogress.js') }}"></script>
    <!-- Chart.js -->
    <script src="{{ asset('backend/vendors/Chart.js/dist/Chart.min.js') }}"></script>
    <!-- gauge.js -->
    <script src="{{ asset('backend/vendors/gauge.js/dist/gauge.min.js') }}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{ asset('backend/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('backend/vendors/iCheck/icheck.min.js') }}"></script>
    <!-- Skycons -->
    <script src="{{ asset('backend/vendors/skycons/skycons.js') }}"></script>
    <!-- Flot -->
    <script src="{{ asset('backend/vendors/Flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('backend/vendors/Flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('backend/vendors/Flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('backend/vendors/Flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('backend/vendors/Flot/jquery.flot.resize.js') }}"></script>
    <!-- Flot plugins -->
    <script src="{{ asset('backend/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
    <script src="{{ asset('backend/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/flot.curvedlines/curvedLines.js') }}"></script>
    <!-- DateJS -->
    <script src="{{ asset('backend/vendors/DateJS/build/date.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('backend/vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
    <script src="{{ asset('backend/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('backend/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{ asset('backend/vendors/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="{{ asset('backend/build/js/custom.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Session Timeout Handler -->
    @auth
        <script>
            $(document).ready(function() {
                initSessionTimeout();
            });

            function initSessionTimeout() {
                const timeout = {{ config('session.timeout', 30) }}; // minutes
                const warningTime = Math.max(5, Math.floor(timeout * 0.1)); // Warn 10% before timeout (min 5 min)
                const checkInterval = 60000; // Check every minute

                let lastActivity = Date.now();
                let warningShown = false;
                let warningModal = null;

                // Track user activity
                $(document).on('mousedown keydown scroll touchstart', function() {
                    lastActivity = Date.now();
                    warningShown = false;
                    if (warningModal) {
                        warningModal.close();
                        warningModal = null;
                    }
                });

                // Check session status periodically
                setInterval(function() {
                    const now = Date.now();
                    const minutesInactive = Math.floor((now - lastActivity) / 60000);
                    const timeoutMinutes = timeout;
                    const timeUntilTimeout = timeoutMinutes - minutesInactive;

                    // Show warning if approaching timeout
                    if (!warningShown && timeUntilTimeout <= warningTime && timeUntilTimeout > 0) {
                        showSessionWarning(timeUntilTimeout);
                        warningShown = true;
                    }

                    // Check if session is expired (with small buffer for network delays)
                    if (timeUntilTimeout <= -1) {
                        handleSessionExpired();
                    }
                }, checkInterval);

                // Handle AJAX errors (session expired)
                $(document).ajaxError(function(event, xhr, settings, thrownError) {
                    if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                        handleSessionExpired(xhr.responseJSON.message);
                    }
                });
            }

            function showSessionWarning(minutesLeft) {
                warningModal = Swal.fire({
                    title: 'Session Timeout Warning',
                    html: `Your session will expire in <strong>${minutesLeft}</strong> minute(s) due to inactivity.<br><br>Move your mouse or press any key to stay logged in.`,
                    icon: 'warning',
                    timer: (minutesLeft * 60000) - 10000, // Close 10 seconds before actual timeout
                    timerProgressBar: true,
                    showCancelButton: true,
                    confirmButtonText: 'Stay Logged In',
                    cancelButtonText: 'Logout Now',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.cancel) {
                        // User chose to logout
                        window.location.href = '{{ route('logout') }}';
                    } else if (result.isConfirmed) {
                        // User chose to stay logged in
                        updateActivity();
                    }
                });
            }

            function handleSessionExpired(message = 'Your session has expired due to inactivity.') {
                Swal.fire({
                    title: 'Session Expired',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'Login Again',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then(() => {
                    window.location.href = '{{ route('login') }}';
                });
            }

            function updateActivity() {
                // Send a heartbeat to the server to refresh session
                $.ajax({
                    url: '{{ route('home') }}',
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function() {
                        // Session refreshed successfully
                        lastActivity = Date.now();
                        warningShown = false;
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            handleSessionExpired();
                        }
                    }
                });
            }
        </script>
    @endauth

    @stack('script')

</body>

</html>
