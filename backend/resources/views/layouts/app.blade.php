<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRM Lead Tracker') - Lead & Customer Management</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --crm-bg: #ffffff;
            --crm-card-bg: #ffffff;
            --crm-border: #e2e8f0;
            --crm-text: #0f172a;
            --crm-muted: #64748b;
            --crm-accent: #4f46e5;
            --crm-accent-hover: #4338ca;
            --crm-success: #15803d;
            --crm-warning: #b45309;
            --crm-danger: #dc2626;
            --crm-info: #2563eb;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: var(--crm-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid var(--crm-border);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.35rem;
            color: #0f172a;
        }

        .nav-link {
            color: var(--crm-muted);
            font-weight: 600;
            transition: all 0.2s ease;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }

        .nav-link:hover, .nav-link.active {
            color: #4f46e5;
            background-color: #eef2ff;
        }

        .crm-card {
            background-color: #ffffff;
            border: 1px solid var(--crm-border);
            border-radius: 0.85rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
        }

        .table-custom {
            --bs-table-bg: #ffffff;
            --bs-table-color: var(--crm-text);
            --bs-table-border-color: var(--crm-border);
        }

        .table-custom th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--crm-border);
            padding: 0.85rem 1.25rem;
        }

        .table-custom td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--crm-border);
            color: #0f172a;
        }

        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            border-radius: 0.6rem;
            padding: 0.65rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: var(--crm-accent);
            color: #0f172a;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .btn-accent {
            background-color: var(--crm-accent);
            color: #ffffff;
            font-weight: 600;
            border-radius: 0.6rem;
            padding: 0.65rem 1.25rem;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-accent:hover {
            background-color: var(--crm-accent-hover);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .badge-role {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.35em 0.75em;
            border-radius: 2rem;
        }

        .badge-role-admin {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .badge-role-sales {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .badge-status {
            padding: 0.4em 0.85em;
            border-radius: 2rem;
            font-weight: 700;
            font-size: 0.8rem;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
        }

        .status-new { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .status-in_progress { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .status-won { background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .status-lost { background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        .stat-card {
            background-color: #ffffff;
            border: 1px solid var(--crm-border);
            border-radius: 0.85rem;
            padding: 1.25rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
        }

        .footer {
            margin-top: auto;
            border-top: 1px solid var(--crm-border);
            background-color: #ffffff;
            color: var(--crm-muted);
            font-size: 0.85rem;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-custom">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('leads.index') }}">
                <i class="bi bi-diagram-3-fill fs-4 text-primary"></i>
                <span class="brand-logo">CRM Lead Tracker</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                @auth
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('leads.*') ? 'active' : '' }}" href="{{ route('leads.index') }}">
                            <i class="bi bi-funnel-fill me-1"></i> Leads Module
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                            <i class="bi bi-people-fill me-1"></i> Customers Module
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <!-- Quick Demo Role Switcher -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill px-3 fw-semibold" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-shuffle me-1"></i> Demo Switch Role
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><h6 class="dropdown-header">Switch User Session</h6></li>
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('switch-role', 'admin') }}">
                                    <span><i class="bi bi-shield-lock-fill text-danger me-2"></i> Admin</span>
                                    <small class="text-muted ms-2">admin@crm.com</small>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('switch-role', 'sales_user') }}">
                                    <span><i class="bi bi-person-badge-fill text-primary me-2"></i> Sales User</span>
                                    <small class="text-muted ms-2">sales@crm.com</small>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Current User Info -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-role {{ Auth::user()->isAdmin() ? 'badge-role-admin' : 'badge-role-sales' }}">
                            <i class="bi {{ Auth::user()->isAdmin() ? 'bi-shield-check' : 'bi-person-badge' }} me-1"></i>
                            {{ Auth::user()->role === 'admin' ? 'Admin' : 'Sales User' }}
                        </span>
                        <span class="text-dark fw-bold d-none d-md-inline" style="font-size: 0.9rem;">
                            {{ Auth::user()->name }}
                        </span>
                    </div>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Logout">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="container-fluid px-4 py-4">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 bg-success bg-opacity-10 text-success rounded-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 bg-danger bg-opacity-10 text-danger rounded-3 mb-4 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-3 text-center">
        <div class="container">
            <span>PHP Team Leader Machine Test Assessment — Built with Laravel {{ app()->version() }} & Sanctum</span>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
