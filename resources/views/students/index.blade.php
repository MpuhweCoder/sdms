@extends('layouts.app')

@section('title', 'All Students — SDMS')
@section('page-header')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Students</li>
                </ol>
            </nav>
            <h1><i class="bi bi-people-fill me-2"></i>Student Records</h1>
            <p>
                @if($search)
                    Results for <strong style="color:#7ec8f7;">"{{ $search }}"</strong>
                @else
                    Manage all registered students in one place.
                @endif
            </p>
        </div>
        <a href="{{ route('students.create') }}" class="btn btn-light fw-semibold">
            <i class="bi bi-person-plus-fill me-1"></i>Add New Student
        </a>
    </div>
@endsection

@push('styles')
<style>
    /* ── Stat cards ────────────────────────────────────── */
    .stat-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* ── Search bar ────────────────────────────────────── */
    .search-wrapper {
        position: relative;
    }
    .search-wrapper .bi-search {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 0.95rem;
        pointer-events: none;
    }
    .search-input {
        padding-left: 2.4rem !important;
        border-radius: 10px !important;
        border: 1.5px solid #e5e7eb !important;
        height: 42px;
        font-size: 0.93rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-input:focus {
        border-color: #2d6a9f !important;
        box-shadow: 0 0 0 3px rgba(45,106,159,0.12) !important;
    }
    .search-clear {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        font-size: 1rem;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 4px;
        line-height: 1;
        display: none;
    }
    .search-clear:hover { color: #374151; }

    /* ── Table ─────────────────────────────────────────── */
    .students-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .students-table thead th {
        background: #1e3a5f;
        color: rgba(255,255,255,0.85);
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        padding: 0.9rem 1.1rem;
        border: none;
        white-space: nowrap;
        user-select: none;
    }
    .students-table thead th:first-child { border-radius: 10px 0 0 0; }
    .students-table thead th:last-child  { border-radius: 0 10px 0 0; }

    /* Sortable column header */
    .sort-link {
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: color 0.15s;
    }
    .sort-link:hover { color: #ffffff; }
    .sort-link.active { color: #7ec8f7; }
    .sort-icon {
        font-size: 0.7rem;
        opacity: 0.5;
    }
    .sort-link.active .sort-icon { opacity: 1; }

    .students-table tbody tr {
        transition: background 0.12s;
    }
    .students-table tbody tr:hover {
        background-color: #eef4fb;
    }
    .students-table tbody td {
        padding: 0.85rem 1.1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.93rem;
        color: #374151;
    }
    .students-table tbody tr:last-child td { border-bottom: none; }

    /* ── Avatar ────────────────────────────────────────── */
    .avatar {
        width: 38px; height: 38px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.95rem;
        flex-shrink: 0;
    }

    /* ── Badges ────────────────────────────────────────── */
    .course-badge {
        display: inline-block;
        padding: 0.28rem 0.75rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        background: #e0eaf5;
        color: #1e3a5f;
        white-space: nowrap;
    }
    .age-pill {
        display: inline-block;
        padding: 0.22rem 0.65rem;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
        background: #f3f4f6;
        color: #374151;
    }

    /* ── Action buttons ────────────────────────────────── */
    .btn-action {
        width: 34px; height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center; justify-content: center;
        font-size: 0.9rem;
        border: none;
        transition: all 0.18s;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-edit   { background:#dbeafe; color:#1d4ed8; }
    .btn-edit:hover   { background:#1d4ed8; color:white; transform:translateY(-1px); }
    .btn-delete { background:#fee2e2; color:#dc2626; }
    .btn-delete:hover { background:#dc2626; color:white; transform:translateY(-1px); }
    .btn-view   { background:#d1fae5; color:#065f46; }
    .btn-view:hover   { background:#065f46; color:white; transform:translateY(-1px); }

    /* ── Search highlight ──────────────────────────────── */
    mark.search-hl {
        background: #fef08a;
        color: #713f12;
        border-radius: 3px;
        padding: 0 2px;
    }

    /* ── Empty state ───────────────────────────────────── */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: #9ca3af;
    }
    .empty-state i {
        font-size: 4rem;
        display: block;
        margin-bottom: 1rem;
        opacity: 0.35;
    }
    .empty-state h5 {
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    /* ── Row number ────────────────────────────────────── */
    .row-num {
        font-size: 0.78rem;
        font-weight: 700;
        color: #9ca3af;
        text-align: center;
    }

    /* ── No results search tip ─────────────────────────── */
    .search-tip {
        font-size: 0.82rem;
        color: #9ca3af;
        margin-top: 0.5rem;
    }
</style>
@endpush

@section('content')

{{-- ── STAT CARDS ─────────────────────────────────────── --}}
<div class="row g-3 mb-4 mt-1">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e0eaf5;">
                    <i class="bi bi-people-fill" style="color:#1e3a5f;"></i>
                </div>
                <div>
                    <div class="text-muted"
                         style="font-size:0.75rem;font-weight:600;
                                text-transform:uppercase;letter-spacing:.5px;">
                        Total Students
                    </div>
                    <div style="font-size:1.8rem;font-weight:700;
                                line-height:1.1;color:#1e3a5f;">
                        {{ $totalStudents }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#d1fae5;">
                    <i class="bi bi-funnel-fill" style="color:#065f46;"></i>
                </div>
                <div>
                    <div class="text-muted"
                         style="font-size:0.75rem;font-weight:600;
                                text-transform:uppercase;letter-spacing:.5px;">
                        {{ $search ? 'Filtered' : 'Showing' }}
                    </div>
                    <div style="font-size:1.8rem;font-weight:700;
                                line-height:1.1;color:#065f46;">
                        {{ $students->total() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fef3c7;">
                    <i class="bi bi-file-earmark-text" style="color:#92400e;"></i>
                </div>
                <div>
                    <div class="text-muted"
                         style="font-size:0.75rem;font-weight:600;
                                text-transform:uppercase;letter-spacing:.5px;">
                        Page
                    </div>
                    <div style="font-size:1.8rem;font-weight:700;
                                line-height:1.1;color:#92400e;">
                        {{ $students->currentPage() }}
                        <span style="font-size:1rem;color:#d97706;">
                            / {{ $students->lastPage() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#ede9fe;">
                    <i class="bi bi-sort-down" style="color:#5b21b6;"></i>
                </div>
                <div>
                    <div class="text-muted"
                         style="font-size:0.75rem;font-weight:600;
                                text-transform:uppercase;letter-spacing:.5px;">
                        Sorted By
                    </div>
                    <div style="font-size:1rem;font-weight:700;
                                line-height:1.4;color:#5b21b6;">
                        {{ ucfirst(str_replace('_', ' ', $sort)) }}
                        <span style="font-size:0.75rem;font-weight:500;
                                     text-transform:uppercase;">
                            {{ $direction }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── SEARCH + TABLE CARD ─────────────────────────────── --}}
<div class="card">

    {{-- ── SEARCH BAR ──────────────────────────────────── --}}
    <div class="card-header bg-white border-bottom p-3">
        <form method="GET"
              action="{{ route('students.index') }}"
              id="searchForm">

            {{-- Preserve sort/direction when searching --}}
            @if($sort !== 'created_at')
                <input type="hidden" name="sort" value="{{ $sort }}">
            @endif
            @if($direction !== 'desc')
                <input type="hidden" name="direction" value="{{ $direction }}">
            @endif

            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="search-wrapper">
                        <i class="bi bi-search"></i>
                        <input
                            type="text"
                            name="search"
                            id="searchInput"
                            class="form-control search-input"
                            placeholder="Search by name, email or course…"
                            value="{{ $search }}"
                            autocomplete="off"
                            spellcheck="false"
                        >
                        {{-- X button appears when there is text --}}
                        <button type="button"
                                class="search-clear"
                                id="searchClear"
                                title="Clear search">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary" style="height:42px;">
                        <i class="bi bi-search me-1"></i>Search
                    </button>
                </div>
                @if($search)
                <div class="col-auto">
                    <a href="{{ route('students.index', array_filter(['sort'=>$sort,'direction'=>$direction])) }}"
                       class="btn btn-outline-secondary"
                       style="height:42px;">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </a>
                </div>
                @endif
            </div>

        </form>

        {{-- Active search result info --}}
        @if($search)
        <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
            <span style="font-size:0.83rem;color:#374151;">
                <i class="bi bi-funnel me-1 text-primary"></i>
                Found <strong>{{ $students->total() }}</strong>
                {{ Str::plural('result', $students->total()) }}
                for <strong>"{{ $search }}"</strong>
            </span>
            @if($students->total() === 0)
                <span class="search-tip">
                    — try a shorter keyword or check spelling
                </span>
            @endif
        </div>
        @endif

    </div>

    {{-- ── TABLE BODY ───────────────────────────────────── --}}
    <div class="card-body p-0">

        @if($students->isEmpty())

            <div class="empty-state">
                @if($search)
                    <i class="bi bi-search"></i>
                    <h5>No students match "{{ $search }}"</h5>
                    <p class="mb-4">Try a different keyword or
                        <a href="{{ route('students.index') }}">clear the search</a>.
                    </p>
                @else
                    <i class="bi bi-person-x"></i>
                    <h5>No students registered yet</h5>
                    <p class="mb-4">Get started by adding your first student.</p>
                    <a href="{{ route('students.create') }}"
                       class="btn btn-primary px-4">
                        <i class="bi bi-person-plus me-1"></i>Add First Student
                    </a>
                @endif
            </div>

        @else

        <div class="table-responsive">
            <table class="students-table">
                <thead>
                    <tr>
                        <th style="width:48px;">#</th>

                        {{--
                            Each sortable column header is a link.
                            Clicking it sorts by that column.
                            Clicking the active column reverses direction.
                            The helper @sortIcon and @sortUrl are
                            computed inline with @php blocks below.
                        --}}

                        {{-- Name --}}
                        <th>
                            @php
                                $nameDir = ($sort==='name' && $direction==='asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('students.index', ['sort'=>'name','direction'=>$nameDir,'search'=>$search]) }}"
                               class="sort-link {{ $sort==='name' ? 'active' : '' }}">
                                Student
                                <i class="bi {{ $sort==='name' ? ($direction==='asc' ? 'bi-arrow-up' : 'bi-arrow-down') : 'bi-arrow-down-up' }} sort-icon"></i>
                            </a>
                        </th>

                        {{-- Age --}}
                        <th>
                            @php
                                $ageDir = ($sort==='age' && $direction==='asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('students.index', ['sort'=>'age','direction'=>$ageDir,'search'=>$search]) }}"
                               class="sort-link {{ $sort==='age' ? 'active' : '' }}">
                                Age
                                <i class="bi {{ $sort==='age' ? ($direction==='asc' ? 'bi-arrow-up' : 'bi-arrow-down') : 'bi-arrow-down-up' }} sort-icon"></i>
                            </a>
                        </th>

                        {{-- Email --}}
                        <th>
                            @php
                                $emailDir = ($sort==='email' && $direction==='asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('students.index', ['sort'=>'email','direction'=>$emailDir,'search'=>$search]) }}"
                               class="sort-link {{ $sort==='email' ? 'active' : '' }}">
                                Email
                                <i class="bi {{ $sort==='email' ? ($direction==='asc' ? 'bi-arrow-up' : 'bi-arrow-down') : 'bi-arrow-down-up' }} sort-icon"></i>
                            </a>
                        </th>

                        {{-- Course --}}
                        <th>
                            @php
                                $courseDir = ($sort==='course' && $direction==='asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('students.index', ['sort'=>'course','direction'=>$courseDir,'search'=>$search]) }}"
                               class="sort-link {{ $sort==='course' ? 'active' : '' }}">
                                Course
                                <i class="bi {{ $sort==='course' ? ($direction==='asc' ? 'bi-arrow-up' : 'bi-arrow-down') : 'bi-arrow-down-up' }} sort-icon"></i>
                            </a>
                        </th>

                        {{-- Registered --}}
                        <th>
                            @php
                                $dateDir = ($sort==='created_at' && $direction==='asc') ? 'desc' : 'asc';
                            @endphp
                            <a href="{{ route('students.index', ['sort'=>'created_at','direction'=>$dateDir,'search'=>$search]) }}"
                               class="sort-link {{ $sort==='created_at' ? 'active' : '' }}">
                                Registered
                                <i class="bi {{ $sort==='created_at' ? ($direction==='asc' ? 'bi-arrow-up' : 'bi-arrow-down') : 'bi-arrow-down-up' }} sort-icon"></i>
                            </a>
                        </th>

                        <th style="width:120px;text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    <tr>
                        {{-- Continuous row number across pages --}}
                        <td>
                            <span class="row-num">
                                {{ ($students->currentPage()-1) * $students->perPage() + $index + 1 }}
                            </span>
                        </td>

                        {{-- Name + Avatar --}}
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $colors = [
                                        ['bg'=>'#dbeafe','text'=>'#1d4ed8'],
                                        ['bg'=>'#d1fae5','text'=>'#065f46'],
                                        ['bg'=>'#fef3c7','text'=>'#92400e'],
                                        ['bg'=>'#ede9fe','text'=>'#5b21b6'],
                                        ['bg'=>'#fee2e2','text'=>'#991b1b'],
                                    ];
                                    $color = $colors[$student->id % 5];
                                @endphp
                                <div class="avatar"
                                     style="background:{{ $color['bg'] }};
                                            color:{{ $color['text'] }};">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;color:#111827;">
                                        {{-- Highlight search term in name --}}
                                        {!! $search
                                            ? preg_replace(
                                                '/(' . preg_quote(e($search), '/') . ')/i',
                                                '<mark class="search-hl">$1</mark>',
                                                e($student->name)
                                              )
                                            : e($student->name)
                                        !!}
                                    </div>
                                    <div style="font-size:0.75rem;color:#9ca3af;">
                                        ID #{{ $student->id }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Age --}}
                        <td>
                            <span class="age-pill">{{ $student->age }} yrs</span>
                        </td>

                        {{-- Email (highlighted) --}}
                        <td>
                            <a href="mailto:{{ $student->email }}"
                               class="text-decoration-none"
                               style="color:#2d6a9f;font-size:0.88rem;">
                                {!! $search
                                    ? preg_replace(
                                        '/(' . preg_quote(e($search), '/') . ')/i',
                                        '<mark class="search-hl">$1</mark>',
                                        e($student->email)
                                      )
                                    : e($student->email)
                                !!}
                            </a>
                        </td>

                        {{-- Course (highlighted) --}}
                        <td>
                            <span class="course-badge">
                                {!! $search
                                    ? preg_replace(
                                        '/(' . preg_quote(e($search), '/') . ')/i',
                                        '<mark class="search-hl">$1</mark>',
                                        e($student->course)
                                      )
                                    : e($student->course)
                                !!}
                            </span>
                        </td>

                        {{-- Registered date --}}
                        <td style="color:#9ca3af;font-size:0.82rem;white-space:nowrap;">
                            {{ $student->created_at->format('d M Y') }}
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('students.show', $student) }}"
                                   class="btn-action btn-view" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}"
                                   class="btn-action btn-edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button"
                                        class="btn-action btn-delete"
                                        title="Delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-student-id="{{ $student->id }}"
                                        data-student-name="{{ $student->name }}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ── TABLE FOOTER ────────────────────────────── --}}
        <div class="d-flex align-items-center justify-content-between
                    flex-wrap gap-2 px-4 py-3 border-top"
             style="background:#fafafa;">
            <small class="text-muted">
                Showing
                <strong>{{ $students->firstItem() }}</strong>–<strong>{{ $students->lastItem() }}</strong>
                of <strong>{{ $students->total() }}</strong>
                {{ $search ? 'results' : 'students' }}
            </small>
            {{ $students->links() }}
        </div>

        @endif

    </div>{{-- /card-body --}}
</div>{{-- /card --}}

<<<<<<< HEAD
{{-- ── DELETE CONFIRMATION MODAL ──────────────────────── --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:14px;overflow:hidden;">

            {{-- Modal Header --}}
=======

{{-- ── DELETE CONFIRMATION MODAL ──────────────────────── --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:14px;overflow:hidden;">

            <div class="modal-header border-0 pb-0" style="background:#fff8f8;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:44px;height:44px;border-radius:50%;
                                background:#fee2e2;display:flex;
                                align-items:center;justify-content:center;">
                        <i class="bi bi-exclamation-triangle-fill"
                           style="color:#dc2626;font-size:1.2rem;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight:700;color:#111827;">
                        Confirm Deletion
                    </h5>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 pt-3 pb-2" style="background:#fff8f8;">
                <p class="mb-0" style="color:#4b5563;">
                    Are you sure you want to permanently delete
                    <strong id="modalStudentName" style="color:#dc2626;"></strong>?
                </p>

                <p class="mb-0 mt-2" style="font-size:0.83rem;color:#9ca3af;">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    This action is irreversible.
                </p>
            </div>

            <div class="modal-footer border-0 pt-2 gap-2" style="background:#fff8f8;">

                <button type="button"
                        class="btn btn-outline-secondary btn-sm px-3"
                        data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>

                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger btn-sm px-3">
                        <i class="bi bi-trash3 me-1"></i>Yes, Delete
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {

    /* ── Search clear button ─────────────────────────── */
    const searchInput = document.getElementById('searchInput');
    const searchClear = document.getElementById('searchClear');

    function toggleClearBtn() {
        searchClear.style.display =
            searchInput.value.length > 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', toggleClearBtn);
    toggleClearBtn();

    searchClear.addEventListener('click', function () {
        searchInput.value = '';
        toggleClearBtn();
        searchInput.focus();
        document.getElementById('searchForm').submit();
    });

    /* ── Delete modal ────────────────────────────────── */
    const modal      = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const submitBtn  = deleteForm.querySelector('button[type="submit"]');

    modal.addEventListener('show.bs.modal', function (event) {

        const button      = event.relatedTarget;
        const studentId   = button.getAttribute('data-student-id');
        const studentName = button.getAttribute('data-student-name');

        document.getElementById('modalStudentName').textContent =
            studentName;

        deleteForm.action = '/students/' + studentId;

        resetSubmitBtn();
    });

    deleteForm.addEventListener('submit', function () {

        submitBtn.disabled = true;

        submitBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
    });

    modal.addEventListener('hide.bs.modal', resetSubmitBtn);

    function resetSubmitBtn() {

        submitBtn.disabled = false;

        submitBtn.innerHTML =
            '<i class="bi bi-trash3 me-1"></i>Yes, Delete';
    }

})();
</script>
@endpush

        </div>
    </div>
</div>