<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SDMS — Student Details Management System')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ── Global ─────────────────────────────────────── */
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Navbar ──────────────────────────────────────── */
        .navbar {
            background: linear-gradient(135deg, #1e3a5f, #2d6a9f);
            box-shadow: 0 2px 12px rgba(0,0,0,0.18);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }
        .navbar-brand span {
            color: #7ec8f7;
        }
        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            transition: color 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            color: #ffffff !important;
        }

        /* ── Page header ─────────────────────────────────── */
        .page-header {
            background: linear-gradient(135deg, #1e3a5f, #2d6a9f);
            color: white;
            padding: 2rem 0 1.5rem;
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
        }
        .page-header p {
            margin: 0.25rem 0 0;
            opacity: 0.8;
            font-size: 0.95rem;
        }

        /* ── Cards ───────────────────────────────────────── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
        }
        .card-header {
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            font-size: 1rem;
            padding: 1rem 1.5rem;
        }

        /* ── Buttons ─────────────────────────────────────── */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1e3a5f, #2d6a9f);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #162d4a, #245a8a);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30,58,95,0.35);
        }

        /* ── Form controls ───────────────────────────────── */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid #dee2e6;
            padding: 0.6rem 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2d6a9f;
            box-shadow: 0 0 0 3px rgba(45,106,159,0.15);
        }
        .form-label {
            font-weight: 600;
            font-size: 0.88rem;
            color: #374151;
            margin-bottom: 0.4rem;
        }

        /* ── Alerts ──────────────────────────────────────── */
        .alert {
            border: none;
            border-radius: 10px;
            font-weight: 500;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ── Footer ──────────────────────────────────────── */
        footer {
            background: #1e3a5f;
            color: rgba(255,255,255,0.7);
            text-align: center;
            padding: 1.2rem;
            font-size: 0.85rem;
            margin-top: auto;
        }
        footer span {
            color: #7ec8f7;
        }

        /* ── Misc ────────────────────────────────────────── */
        .required-star { color: #ef4444; }
        main { flex: 1; }
    </style>

    @stack('styles')
</head>
<body>

    {{-- ── NAVBAR ── --}}
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('students.index') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>SD<span>MS</span>
            </a>
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}"
                           href="{{ route('students.index') }}">
                            <i class="bi bi-people me-1"></i>All Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('students.create') ? 'active' : '' }}"
                           href="{{ route('students.create') }}">
                            <i class="bi bi-person-plus me-1"></i>Add Student
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- ── PAGE HEADER (child views inject this) ── --}}
    @hasSection('page-header')
    <div class="page-header">
        <div class="container">
            @yield('page-header')
        </div>
    </div>
    @endif

    {{-- ── FLASH MESSAGES ── --}}
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- ── MAIN CONTENT ── --}}
    <main>
        <div class="container pb-5">
            @yield('content')
        </div>
    </main>

    {{-- ── FOOTER ── --}}
    <footer>
        &copy; {{ date('Y') }} <span>SDMS</span> — Student Details Management System. Built with Laravel 12.
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>