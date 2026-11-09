<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Barangay MIS') — Barangay Management System</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-icon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #1a2e44;
            --sidebar-hover: #243b55;
            --sidebar-active: #0d6efd;
            --topbar-height: 56px;
        }

        body {
            background: #f4f6f9;
        }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform .3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-brand img {
            width: 32px;
            height: 32px;
            object-fit: contain;
            filter: drop-shadow(0 0 4px rgba(255, 255, 255, 0.1));
        }

        .sidebar-brand h6 {
            color: #fff;
            margin: 0;
            font-size: .75rem;
            opacity: .7;
            line-height: 1.2;
        }

        .sidebar-brand strong {
            color: #fff;
            font-size: 1.1rem;
            line-height: 1.2;
            display: block;
        }

        .sidebar-nav {
            padding: .5rem 0;
        }

        .nav-section {
            padding: .5rem 1rem .25rem;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255, 255, 255, .4);
        }

        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, .75);
            padding: .55rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .875rem;
            border-radius: 0;
            transition: background .15s, color .15s;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-nav .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
        }

        .sidebar-nav .nav-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        /* Main content */
        #main {
            margin-left: var(--sidebar-width);
        }

@guest  
        #main {
            margin-left: 0;
        }

        #topbar {
            display: none !important;
        }
@endguest 
        #topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e3e6ea;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .page-content {
            padding: 1.5rem;
        }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e3e6ea;
            padding: 1.25rem;
            position: relative;
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        /* Table */
        .table-card {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e3e6ea;
            overflow: hidden;
        }

        .table-card .table {
            margin: 0;
        }

        .table-card .table thead th {
            background: #f8f9fb;
            border-bottom: 1px solid #e3e6ea;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6c757d;
            padding: .75rem 1rem;
        }

        .table-card .table tbody td {
            padding: .75rem 1rem;
            vertical-align: middle;
        }

        .table-card .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .badge-active {
            background: #d1f2e4;
            color: #0a6640;
        }

        .badge-inactive {
            background: #fde8e8;
            color: #9b2929;
        }

        .badge-male {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-female {
            background: #fce7f3;
            color: #9d174d;
        }

        .badge-other {
            background: #f3e8ff;
            color: #6b21a8;
        }

        /* Avatar */
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: .85rem;
            color: #495057;
        }

        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.show {
                transform: translateX(0);
            }

            #main {
                margin-left: 0;
            }
        }

        /* Prevent Modal FOUC */
        .modal:not(.show) {
            display: none !important;
        }

        .sub-active {
            background: none !important;
            font-weight: bold !important;
            color: #fff !important;
        }
    </style>
    @stack('styles')
</head>

<body>

    {{-- Sidebar --}}
    @auth
        <nav id="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('assets/img/logo-icon.png') }}" alt="MIS Logo">
                <div>
                    <strong>Barangay MIS</strong>
                    <h6>Management Information System</h6>
                </div>
            </div>
            <ul class="sidebar-nav list-unstyled">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                @can('view_residents')
                    <li>
                        <div class="nav-section">Residents</div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center 
                            {{ request()->routeIs('residents.*') || request()->routeIs('family-members.*') ? 'active' : '' }}"
                            data-bs-toggle="collapse"
                            href="#residentMenu"
                            role="button"
                            aria-expanded="{{ request()->routeIs('residents.*') || request()->routeIs('family-members.*') ? 'true' : 'false' }}"
                        >
                            <span><i class="bi bi-people"></i> Residents</span>
                            <i class="bi bi-chevron-down"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('residents.*') || request()->routeIs('family-members.*') ? 'show' : '' }}" id="residentMenu">
                            <ul class="nav flex-column ms-3">
                                <li>
                                    <a href="{{ route('residents.index') }}"
                                        class="nav-link {{ request()->routeIs('residents.*') ? 'sub-active active' : '' }}">
                                        <i class="bi bi-people"></i> All Residents
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('family-members.index') }}"
                                        class="nav-link {{ request()->routeIs('family-members.*') ? 'sub-active active' : '' }}">
                                        <i class="bi bi-diagram-3"></i> Family Members
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('view_households')
                    <li>
                        <a href="{{ route('households.index') }}"
                            class="nav-link {{ request()->routeIs('households.*') ? 'active' : '' }}">
                            <i class="bi bi-house"></i> Households
                        </a>
                    </li>
                @endcan
                @can('view_puroks')
                    <li>
                        <a href="{{ route('puroks.index') }}"
                            class="nav-link {{ request()->routeIs('puroks.*') ? 'active' : '' }}">
                            <i class="bi bi-map"></i> Puroks
                        </a>
                    </li>
                @endcan

                @can('view_residents')
                    <li>
                        <div class="nav-section">Health & Assistance</div>
                    </li>
                    <li>
                        <a href="{{ route('health-records.index') }}"
                            class="nav-link {{ request()->routeIs('health-records.*') ? 'active' : '' }}">
                            <i class="bi bi-heart-pulse"></i> Health Records
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vaccinations.index') }}"
                            class="nav-link {{ request()->routeIs('vaccinations.*') ? 'active' : '' }}">
                            <i class="bi bi-shield-plus"></i> Vaccinations
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('medical-assistances.index') }}" class="nav-link {{ request()->routeIs('medical-assistances.*') ? 'active' : '' }}">
                            <i class="bi bi-clipboard-plus"></i> Assistance
                        </a>
                    </li>
                @endcan

                @if(auth()->user()->hasPermission('view_certificates') || auth()->user()->hasPermission('view_requests'))
                    <li>
                        <div class="nav-section">Documents</div>
                    </li>
                    @can('view_certificates')
                        <li>
                            <a href="#" class="nav-link">
                                <i class="bi bi-file-earmark-text"></i> Certificates
                            </a>
                        </li>
                    @endcan
                    @can('view_requests')
                        <li>
                            <a href="#" class="nav-link">
                                <i class="bi bi-inbox"></i> Requests
                            </a>
                        </li>
                    @endcan
                @endif

                @if(auth()->user()->hasPermission('view_blotter') || auth()->user()->hasPermission('view_inventory') || auth()->user()->hasPermission('view_announcements'))
                    <li>
                        <div class="nav-section">Operations</div>
                    </li>
                    @can('view_blotter')
                        <li>
                            <a href="#" class="nav-link">
                                <i class="bi bi-exclamation-triangle"></i> Blotter
                            </a>
                        </li>
                    @endcan
                    @can('view_inventory')
                        <li>
                            <a href="#" class="nav-link">
                                <i class="bi bi-box-seam"></i> Inventory
                            </a>
                        </li>
                    @endcan
                    @can('view_announcements')
                        <li>
                            <a href="#" class="nav-link">
                                <i class="bi bi-megaphone"></i> Announcements
                            </a>
                        </li>
                    @endcan
                @endif

                @can('view_finance')
                    <li>
                        <div class="nav-section">Finance</div>
                    </li>
                    <li>
                        <a href="#" class="nav-link">
                            <i class="bi bi-cash-stack"></i> Payments
                        </a>
                    </li>
                @endcan

                @if(auth()->user()->isSuperAdmin())
                    <li>
                        <div class="nav-section">System</div>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="bi bi-person-gear"></i> User Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('roles.index') }}"
                            class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <i class="bi bi-shield-lock"></i> Role Management
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link">
                            <i class="bi bi-clock-history"></i> Audit Logs
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    @endauth

    {{-- Main --}}
    <div id="main">
        {{-- Topbar --}}
        <div id="topbar" class="d-flex align-items-center px-3 gap-3">
            <button class="btn btn-sm btn-light d-lg-none" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small fw-semibold">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                            class="text-decoration-none text-muted">Home</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
            @auth
                <div class="ms-auto d-flex align-items-center gap-2">
                    <span class="small text-muted d-none d-sm-inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-secondary" type="submit">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        {{-- SweetAlert Flash messages --}}
        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'top-end'
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#0d6efd'
                });
            </script>
        @endif

        @if($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `<ul class="text-start mb-0 small">
                                                                                                                                                                    @foreach($errors->all() as $error)
                                                                                                                                                                        <li>{{ $error }}</li>
                                                                                                                                                                    @endforeach
                                                                                                                                                                  </ul>`,
                    confirmButtonColor: '#0d6efd'
                });
            </script>
        @endif

        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Global SweetAlert2 Interceptors
        document.addEventListener('submit', function (e) {
            const form = e.target;
            const onsubmitAttr = form.getAttribute('onsubmit');
            const isSwalConfirm = form.classList.contains('swal-confirm');

            if (isSwalConfirm || (onsubmitAttr && onsubmitAttr.includes('confirm('))) {
                e.preventDefault();

                let message = 'Are you sure?';
                if (isSwalConfirm) {
                    message = form.dataset.message || message;
                } else if (onsubmitAttr) {
                    const match = onsubmitAttr.match(/confirm\(['"](.+)['"]\)/);
                    if (match) message = match[1];
                }

                Swal.fire({
                    title: 'Confirm Action',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.removeAttribute('onsubmit');
                        form.classList.remove('swal-confirm');
                        form.submit();
                    }
                });
            }
        });

        // Override window.alert
        window.alert = function (message) {
            Swal.fire({
                text: message,
                confirmButtonColor: '#0d6efd'
            });
        };
    </script>
    @stack('scripts')
</body>

</html>