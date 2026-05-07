<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Student Details Management System — Laravel 12">
    <title>@yield('title', 'SDMS — Student Details Management System')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ════════════════════════════════════════════════
           DESIGN TOKENS — change these to retheme the app
           ════════════════════════════════════════════════ */
        :root {
            --brand-dark:     #0f2542;
            --brand-mid:      #1e3a5f;
            --brand-light:    #2d6a9f;
            --brand-accent:   #7ec8f7;
            --brand-gradient: linear-gradient(135deg, #0f2542 0%, #2d6a9f 100%);

            --surface:        #ffffff;
            --surface-2:      #f8fafc;
            --surface-3:      #f0f4f8;

            --text-primary:   #111827;
            --text-secondary: #374151;
            --text-muted:     #9ca3af;

            --border:         #e5e7eb;
            --border-light:   #f0f0f0;

            --success-bg:     #d1fae5;
            --success-text:   #065f46;
            --danger-bg:      #fee2e2;
            --danger-text:    #991b1b;
            --warning-bg:     #fef3c7;
            --warning-text:   #92400e;

            --radius-sm:      6px;
            --radius-md:      10px;
            --radius-lg:      14px;
            --radius-xl:      20px;

            --shadow-sm:  0 1px 4px rgba(0,0,0,0.06);
            --shadow-md:  0 2px 16px rgba(0,0,0,0.08);
            --shadow-lg:  0 8px 32px rgba(0,0,0,0.12);
        }

        /* ── Reset & base ──────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background-color: var(--surface-3);
            color: var(--text-secondary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 0.94rem;
            line-height: 1.6;
        }

        main { flex: 1; }

        /* ── Scrollbar ─────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--surface-3); }
        ::-webkit-scrollbar-thumb {
            background: #c1cdd8;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover { background: #9ab0c4; }

        /* ════════════════════════════════════════════════
           NAVBAR
           ════════════════════════════════════════════════ */
        .navbar {
            background: var(--brand-gradient);
            box-shadow: 0 2px 20px rgba(0,0,0,0.22);
            padding: 0;
            min-height: 62px;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .navbar .container {
            min-height: 62px;
        }

        /* Brand */
        .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.3px;
            color: #fff !important;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255,255,255,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            transition: background 0.2s;
        }
        .navbar-brand:hover .brand-icon {
            background: rgba(255,255,255,0.28);
        }
        .brand-text span { color: var(--brand-accent); }

        /* Nav links */
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.78) !important;
            font-weight: 500;
            font-size: 0.88rem;
            padding: 0.45rem 0.85rem !important;
            border-radius: var(--radius-md);
            transition: all 0.18s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .navbar-nav .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.12);
        }
        .navbar-nav .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.18);
        }

        /* Toggler */
        .navbar-toggler {
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: var(--radius-md);
            padding: 4px 8px;
        }
        .navbar-toggler:focus { box-shadow: none; }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255%2c255%2c255%2c0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* ════════════════════════════════════════════════
           PAGE HEADER BANNER
           ════════════════════════════════════════════════ */
        .page-header {
            background: var(--brand-gradient);
            color: white;
            padding: 1.75rem 0 1.5rem;
            margin-bottom: 1.75rem;
            position: relative;
            overflow: hidden;
        }
        /* Decorative circles in background */
        .page-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            pointer-events: none;
        }
        .page-header::after {
            content: '';
            position: absolute;
            bottom: -60px; left: 20%;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            pointer-events: none;
        }
        .page-header h1 {
            font-size: 1.65rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.3px;
        }
        .page-header p {
            margin: 0.3rem 0 0;
            opacity: 0.78;
            font-size: 0.9rem;
        }
        .page-header .btn-light {
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: var(--radius-md);
            padding: 0.45rem 1rem;
            border: none;
            transition: all 0.2s;
        }
        .page-header .btn-light:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }

        /* ════════════════════════════════════════════════
           CARDS
           ════════════════════════════════════════════════ */
        .card {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            background: var(--surface);
        }
        .card-header {
            border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-light);
        }
        .card-header.gradient {
            background: var(--brand-gradient);
            color: white;
            border-bottom: none;
        }

        /* ════════════════════════════════════════════════
           BUTTONS
           ════════════════════════════════════════════════ */
        .btn {
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.5rem 1.2rem;
            transition: all 0.18s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-primary {
            background: var(--brand-gradient);
            border: none;
            color: white;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0c1e38 0%, #245a8a 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(30,58,95,0.35);
            color: white;
        }
        .btn-primary:active { transform: translateY(0); }
        .btn-outline-secondary {
            border: 1.5px solid var(--border);
            color: var(--text-secondary);
        }
        .btn-outline-secondary:hover {
            background: var(--surface-2);
            border-color: #c0c8d0;
            color: var(--text-primary);
        }
        .btn-danger {
            background: linear-gradient(135deg,#b91c1c,#dc2626);
            border: none;
        }
        .btn-danger:hover {
            background: linear-gradient(135deg,#991b1b,#b91c1c);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(185,28,28,0.35);
        }

        /* ════════════════════════════════════════════════
           FORM CONTROLS
           ════════════════════════════════════════════════ */
        .form-control, .form-select {
            border-radius: var(--radius-md);
            border: 1.5px solid var(--border);
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            color: var(--text-primary);
            background: var(--surface);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-light);
            box-shadow: 0 0 0 3px rgba(45,106,159,0.13);
            outline: none;
        }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #ef4444;
        }
        .form-control.is-invalid:focus, .form-select.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,0.13);
        }
        .form-label {
            font-weight: 600;
            font-size: 0.83rem;
            color: var(--text-secondary);
            letter-spacing: 0.2px;
            margin-bottom: 0.4rem;
            text-transform: uppercase;
        }
        .input-group-text {
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            background: var(--surface-2);
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        .input-group > .input-group-text {
            border-right: none;
            border-radius: var(--radius-md) 0 0 var(--radius-md);
        }
        .input-group > .form-control,
        .input-group > .form-select {
            border-left: none;
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
        }
        .invalid-feedback {
            font-size: 0.8rem;
            color: #dc2626;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 5px;
        }

        /* ════════════════════════════════════════════════
           ALERTS / FLASH MESSAGES
           ════════════════════════════════════════════════ */
        .alert {
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.85rem 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background: var(--success-bg);
            color: var(--success-text);
            border-left: 4px solid #10b981;
        }
        .alert-danger {
            background: var(--danger-bg);
            color: var(--danger-text);
            border-left: 4px solid #ef4444;
        }
        .alert-warning {
            background: var(--warning-bg);
            color: var(--warning-text);
            border-left: 4px solid #f59e0b;
        }
        .alert .btn-close { margin-left: auto; }

        /* ════════════════════════════════════════════════
           FOOTER
           ════════════════════════════════════════════════ */
        footer {
            background: var(--brand-dark);
            color: rgba(255,255,255,0.55);
            padding: 0;
            margin-top: auto;
        }
        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
            padding: 1.1rem 0;
            font-size: 0.82rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .footer-brand {
            font-weight: 700;
            color: var(--brand-accent);
        }
        .footer-links {
            display: flex;
            gap: 1rem;
        }
        .footer-links a {
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            transition: color 0.2s;
            font-size: 0.8rem;
        }
        .footer-links a:hover { color: rgba(255,255,255,0.85); }

        /* ════════════════════════════════════════════════
           UTILITIES
           ════════════════════════════════════════════════ */
        .required-star { color: #ef4444; }

        /* Page fade-in animation */
        .page-fade {
            animation: pageFade 0.25s ease-out;
        }
        @keyframes pageFade {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Breadcrumb */
        .breadcrumb {
            font-size: 0.8rem;
            margin: 0;
            padding: 0;
            background: none;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.4);
        }
        .breadcrumb-item a {
            color: rgba(255,255,255,0.65);
            text-decoration: none;
        }
        .breadcrumb-item a:hover { color: #fff; }
        .breadcrumb-item.active { color: rgba(255,255,255,0.85); }

        /* Back to top button */
        #backToTop {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--brand-gradient);
            color: white;
            border: none;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            z-index: 999;
            transition: opacity 0.2s, transform 0.2s;
        }
        #backToTop:hover { transform: translateY(-2px); }
        #backToTop.visible { display: flex; }

        /* ════════════════════════════════════════════════
           RESPONSIVE
           ════════════════════════════════════════════════ */
        @media (max-width: 768px) {
            .page-header h1 { font-size: 1.35rem; }
            .page-header { padding: 1.25rem 0 1.1rem; }
            .navbar-collapse {
                background: rgba(15,37,66,0.97);
                margin: 0 -12px;
                padding: 0.75rem 1rem;
                border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            }
            .footer-inner { flex-direction: column; text-align: center; }
        }
    </style>

    @stack('styles')
</head>

<body>

{{-- ════════════════ NAVBAR ════════════════ --}}
<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand" href="{{ route('students.index') }}">
            <div class="brand-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <span class="brand-text">SD<span>MS</span></span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navMenu"
                aria-controls="navMenu"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1 py-2 py-lg-0">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}"
                       href="{{ route('students.index') }}">
                        <i class="bi bi-people"></i> All Students
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('students.create') ? 'active' : '' }}"
                       href="{{ route('students.create') }}">
                        <i class="bi bi-person-plus"></i> Add Student
                    </a>
                </li>

                {{-- Divider (desktop only) --}}
                <li class="nav-item d-none d-lg-block">
                    <span style="display:block;width:1px;height:20px;
                                 background:rgba(255,255,255,0.2);margin:0 6px;"></span>
                </li>

                {{-- Quick stat badge --}}
                <li class="nav-item">
                    <span class="nav-link" style="cursor:default;">
                        <i class="bi bi-database"></i>
                        <span style="background:rgba(255,255,255,0.15);
                                     padding:2px 9px;border-radius:20px;
                                     font-size:0.78rem;font-weight:700;">
                            {{ \App\Models\Student::count() }} students
                        </span>
                    </span>
                </li>

            </ul>
        </div>
    </div>
</nav>

{{-- ════════════════ PAGE HEADER ════════════════ --}}
@hasSection('page-header')
<div class="page-header">
    <div class="container position-relative" style="z-index:1;">
        @yield('page-header')
    </div>
</div>
@endif

{{-- ════════════════ FLASH MESSAGES ════════════════ --}}
@if(session('success') || session('error') || session('warning'))
<div class="container mt-3 page-fade">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <i class="bi bi-x-circle-fill fs-5"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <span>{{ session('warning') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endif

{{-- ════════════════ MAIN CONTENT ════════════════ --}}
<main>
    <div class="container pb-5 page-fade">
        @yield('content')
    </div>
</main>

{{-- ════════════════ FOOTER ════════════════ --}}
<footer>
    <div class="container">
        <div class="footer-inner">
            <div>
                &copy; {{ date('Y') }}
                <span class="footer-brand">SDMS</span>
                &mdash; Student Details Management System
            </div>
            <div class="footer-links">
                <a href="{{ route('students.index') }}">
                    <i class="bi bi-people me-1"></i>Students
                </a>
                <a href="{{ route('students.create') }}">
                    <i class="bi bi-person-plus me-1"></i>Add Student
                </a>
                <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;">
                    <i class="bi bi-arrow-up me-1"></i>Top
                </a>
            </div>
            <div style="font-size:0.78rem;">
                Built with
                <i class="bi bi-heart-fill" style="color:#f87171;font-size:0.7rem;"></i>
                Laravel 12 &amp; Bootstrap 5
            </div>
        </div>
    </div>
</footer>

{{-- Back to top button --}}
<button id="backToTop" aria-label="Back to top">
    <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ── Back to top ─────────────────────────────────── */
(function () {
    const btn = document.getElementById('backToTop');
    window.addEventListener('scroll', function () {
        btn.classList.toggle('visible', window.scrollY > 300);
    });
    btn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();

/* ── Auto-dismiss flash alerts after 5s ─────────── */
(function () {
    setTimeout(function () {
        document.querySelectorAll('.alert.alert-success').forEach(function (el) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            bsAlert.close();
        });
    }, 5000);
})();
</script>

@stack('scripts')
</body>
</html>