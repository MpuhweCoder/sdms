@extends('layouts.app')

@section('title', 'Dashboard — SDMS')

@section('page-header')
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
        <h1><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
        <p>Welcome to the Student Details Management System.</p>
    </div>
@endsection

@push('styles')
<style>
    /* ── Stat cards ───────────────────────────────── */
    .dash-stat {
        border-radius: 16px;
        padding: 1.4rem 1.5rem;
        border: none;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: var(--shadow-md);
        cursor: default;
    }
    .dash-stat:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    .dash-stat .stat-bg-icon {
        position: absolute;
        right: -10px; bottom: -10px;
        font-size: 5.5rem;
        opacity: 0.1;
        line-height: 1;
        pointer-events: none;
    }
    .dash-stat .stat-number {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -1px;
    }
    .dash-stat .stat-label {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        opacity: 0.75;
        margin-top: 2px;
    }
    .dash-stat .stat-sub {
        font-size: 0.8rem;
        margin-top: 0.6rem;
        opacity: 0.7;
    }

    /* ── Quick action cards ──────────────────────── */
    .action-card {
        border-radius: 14px;
        border: 2px solid var(--border);
        background: var(--surface);
        padding: 1.5rem;
        text-decoration: none;
        color: var(--text-secondary);
        display: block;
        transition: all 0.2s;
        box-shadow: var(--shadow-sm);
    }
    .action-card:hover {
        border-color: var(--brand-light);
        color: var(--brand-mid);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        text-decoration: none;
    }
    .action-card .action-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 1rem;
        transition: transform 0.2s;
    }
    .action-card:hover .action-icon { transform: scale(1.1); }
    .action-card h6 {
        font-weight: 700;
        font-size: 0.95rem;
        margin: 0 0 0.25rem;
        color: var(--text-primary);
    }
    .action-card p {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.4;
    }

    /* ── Recent students table ───────────────────── */
    .recent-table th {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        padding: 0.6rem 1rem;
        border-bottom: 2px solid var(--border-light);
        background: var(--surface-2);
        white-space: nowrap;
    }
    .recent-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.88rem;
    }
    .recent-table tbody tr:last-child td { border-bottom: none; }
    .recent-table tbody tr:hover { background: #f8faff; }

    /* ── Course breakdown bars ───────────────────── */
    .course-bar-wrap {
        margin-bottom: 0.85rem;
    }
    .course-bar-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        margin-bottom: 4px;
        font-weight: 500;
    }
    .course-bar {
        height: 8px;
        border-radius: 10px;
        background: var(--border-light);
        overflow: hidden;
    }
    .course-bar-fill {
        height: 100%;
        border-radius: 10px;
        background: var(--brand-gradient);
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ── Empty dashboard hint ────────────────────── */
    .empty-dash {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }
    .empty-dash i {
        font-size: 3.5rem;
        opacity: 0.25;
        display: block;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')

@php
    $total        = \App\Models\Student::count();
    $newest       = \App\Models\Student::latest()->first();
    $avgAge       = \App\Models\Student::avg('age');
    $recentList   = \App\Models\Student::latest()->take(5)->get();

    // Course breakdown for the bar chart
    $courseCounts = \App\Models\Student::selectRaw('course, COUNT(*) as total')
                        ->groupBy('course')
                        ->orderByDesc('total')
                        ->take(6)
                        ->get();

    $avatarColors = [
        ['bg'=>'#dbeafe','text'=>'#1d4ed8'],
        ['bg'=>'#d1fae5','text'=>'#065f46'],
        ['bg'=>'#fef3c7','text'=>'#92400e'],
        ['bg'=>'#ede9fe','text'=>'#5b21b6'],
        ['bg'=>'#fee2e2','text'=>'#991b1b'],
    ];
@endphp

{{-- ── ROW 1: STAT CARDS ────────────────────────────── --}}
<div class="row g-3 mt-1 mb-4">

    {{-- Total Students --}}
    <div class="col-sm-6 col-xl-3">
        <div class="dash-stat" style="background:linear-gradient(135deg,#1e3a5f,#2d6a9f);color:white;">
            <div class="stat-label">Total Students</div>
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-sub">
                <i class="bi bi-people me-1"></i>Registered in system
            </div>
            <i class="bi bi-people stat-bg-icon"></i>
        </div>
    </div>

    {{-- Average Age --}}
    <div class="col-sm-6 col-xl-3">
        <div class="dash-stat" style="background:linear-gradient(135deg,#065f46,#059669);color:white;">
            <div class="stat-label">Average Age</div>
            <div class="stat-number">{{ $total > 0 ? number_format($avgAge, 1) : '—' }}</div>
            <div class="stat-sub">
                <i class="bi bi-calendar3 me-1"></i>Across all students
            </div>
            <i class="bi bi-calendar3 stat-bg-icon"></i>
        </div>
    </div>

    {{-- Courses --}}
    <div class="col-sm-6 col-xl-3">
        <div class="dash-stat" style="background:linear-gradient(135deg,#5b21b6,#7c3aed);color:white;">
            <div class="stat-label">Courses</div>
            <div class="stat-number">{{ $courseCounts->count() }}</div>
            <div class="stat-sub">
                <i class="bi bi-book me-1"></i>Distinct programs
            </div>
            <i class="bi bi-book stat-bg-icon"></i>
        </div>
    </div>

    {{-- Newest Student --}}
    <div class="col-sm-6 col-xl-3">
        <div class="dash-stat" style="background:linear-gradient(135deg,#92400e,#b45309);color:white;">
            <div class="stat-label">Latest Added</div>
            <div class="stat-number" style="font-size:1.25rem;letter-spacing:0;">
                {{ $newest ? \Illuminate\Support\Str::limit($newest->name, 14) : '—' }}
            </div>
            <div class="stat-sub">
                <i class="bi bi-clock me-1"></i>
                {{ $newest ? $newest->created_at->diffForHumans() : 'No students yet' }}
            </div>
            <i class="bi bi-person-plus stat-bg-icon"></i>
        </div>
    </div>

</div>

{{-- ── ROW 2: QUICK ACTIONS ────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <h6 style="font-weight:700;font-size:0.8rem;text-transform:uppercase;
                   letter-spacing:.6px;color:var(--text-muted);margin-bottom:.75rem;">
            Quick Actions
        </h6>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('students.create') }}" class="action-card">
            <div class="action-icon" style="background:#e0eaf5;">
                <i class="bi bi-person-plus-fill" style="color:#1e3a5f;"></i>
            </div>
            <h6>Add Student</h6>
            <p>Register a new student into the system</p>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('students.index') }}" class="action-card">
            <div class="action-icon" style="background:#d1fae5;">
                <i class="bi bi-people-fill" style="color:#065f46;"></i>
            </div>
            <h6>View All Students</h6>
            <p>Browse, search and manage records</p>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('students.index', ['sort'=>'name','direction'=>'asc']) }}"
           class="action-card">
            <div class="action-icon" style="background:#ede9fe;">
                <i class="bi bi-sort-alpha-down" style="color:#5b21b6;"></i>
            </div>
            <h6>Sort A → Z</h6>
            <p>View all students sorted by name</p>
        </a>
    </div>

    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('students.index', ['sort'=>'created_at','direction'=>'desc']) }}"
           class="action-card">
            <div class="action-icon" style="background:#fef3c7;">
                <i class="bi bi-clock-history" style="color:#92400e;"></i>
            </div>
            <h6>Recent First</h6>
            <p>See the most recently added students</p>
        </a>
    </div>
</div>

{{-- ── ROW 3: RECENT STUDENTS + COURSE BREAKDOWN ───────── --}}
<div class="row g-4">

    {{-- Recent Students --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white d-flex align-items-center
                        justify-content-between">
                <span style="font-weight:700;color:var(--brand-mid);">
                    <i class="bi bi-clock-history me-2"></i>Recently Added
                </span>
                <a href="{{ route('students.index') }}"
                   style="font-size:0.8rem;color:var(--brand-light);
                          text-decoration:none;font-weight:600;">
                    View all <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentList->isEmpty())
                    <div class="empty-dash">
                        <i class="bi bi-person-x"></i>
                        <p>No students added yet.<br>
                            <a href="{{ route('students.create') }}">Add the first one!</a>
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table recent-table mb-0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Added</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentList as $student)
                                @php $color = $avatarColors[$student->id % 5]; @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:34px;height:34px;
                                                        border-radius:50%;flex-shrink:0;
                                                        background:{{ $color['bg'] }};
                                                        color:{{ $color['text'] }};
                                                        display:flex;align-items:center;
                                                        justify-content:center;
                                                        font-weight:700;font-size:0.85rem;">
                                                {{ strtoupper(substr($student->name,0,1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight:600;color:var(--text-primary);
                                                            font-size:0.88rem;">
                                                    {{ $student->name }}
                                                </div>
                                                <div style="font-size:0.75rem;color:var(--text-muted);">
                                                    {{ $student->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="font-size:0.78rem;font-weight:600;
                                                     padding:.2rem .65rem;border-radius:20px;
                                                     background:#e0eaf5;color:#1e3a5f;">
                                            {{ \Illuminate\Support\Str::limit($student->course, 22) }}
                                        </span>
                                    </td>
                                    <td style="color:var(--text-muted);
                                               font-size:0.78rem;white-space:nowrap;">
                                        {{ $student->created_at->diffForHumans() }}
                                    </td>
                                    <td>
                                        <a href="{{ route('students.show', $student) }}"
                                           style="color:var(--brand-light);
                                                  font-size:0.85rem;">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Course Breakdown --}}
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-white">
                <span style="font-weight:700;color:var(--brand-mid);">
                    <i class="bi bi-bar-chart-fill me-2"></i>Students by Course
                </span>
            </div>
            <div class="card-body">
                @if($courseCounts->isEmpty())
                    <div class="empty-dash">
                        <i class="bi bi-bar-chart"></i>
                        <p>No data yet.</p>
                    </div>
                @else
                    @foreach($courseCounts as $row)
                    <div class="course-bar-wrap">
                        <div class="course-bar-label">
                            <span style="color:var(--text-secondary);">
                                {{ \Illuminate\Support\Str::limit($row->course, 28) }}
                            </span>
                            <span style="font-weight:700;color:var(--brand-mid);">
                                {{ $row->total }}
                            </span>
                        </div>
                        <div class="course-bar">
                            <div class="course-bar-fill"
                                 style="width:{{ $total > 0 ? round(($row->total/$total)*100) : 0 }}%;">
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="mt-3 pt-2 border-top text-center"
                         style="font-size:0.78rem;color:var(--text-muted);">
                        {{ $total }} total students across
                        {{ $courseCounts->count() }} courses
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
/* Animate course bars on load */
document.addEventListener('DOMContentLoaded', function () {
    const fills = document.querySelectorAll('.course-bar-fill');
    fills.forEach(function (el) {
        const target = el.style.width;
        el.style.width = '0%';
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                el.style.width = target;
            });
        });
    });
});
</script>
@endpush