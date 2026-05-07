@extends('layouts.app')

@section('title', 'All Students — SDMS')

@section('page-header')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h1><i class="bi bi-people-fill me-2"></i>Student Records</h1>
            <p>Manage all registered students in one place.</p>
        </div>
        <a href="{{ route('students.create') }}" class="btn btn-light fw-semibold">
            <i class="bi bi-person-plus-fill me-1"></i>Add New Student
        </a>
    </div>
@endsection

@push('styles')
<style>
    /* ── Stat card ─────────────────────────────────── */
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
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* ── Table ─────────────────────────────────────── */
    .students-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .students-table thead th {
        background: #1e3a5f;
        color: white;
        font-weight: 600;
        font-size: 0.82rem;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        padding: 0.9rem 1.1rem;
        border: none;
        white-space: nowrap;
    }
    .students-table thead th:first-child {
        border-radius: 10px 0 0 0;
    }
    .students-table thead th:last-child {
        border-radius: 0 10px 0 0;
    }
    .students-table tbody tr {
        transition: background 0.15s, transform 0.15s;
        cursor: default;
    }
    .students-table tbody tr:hover {
        background-color: #eef4fb;
        transform: scale(1.002);
    }
    .students-table tbody td {
        padding: 0.85rem 1.1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.93rem;
        color: #374151;
    }
    .students-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ── Avatar circle ─────────────────────────────── */
    .avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    /* ── Course badge ──────────────────────────────── */
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

    /* ── Age pill ──────────────────────────────────── */
    .age-pill {
        display: inline-block;
        padding: 0.22rem 0.65rem;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
        background: #f3f4f6;
        color: #374151;
    }

    /* ── Action buttons ────────────────────────────── */
    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        border: none;
        transition: all 0.18s;
        text-decoration: none;
    }
    .btn-edit {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .btn-edit:hover {
        background: #1d4ed8;
        color: white;
        transform: translateY(-1px);
    }
    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }
    .btn-delete:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-1px);
    }
    .btn-view {
        background: #d1fae5;
        color: #065f46;
    }
    .btn-view:hover {
        background: #065f46;
        color: white;
        transform: translateY(-1px);
    }

    /* ── Empty state ───────────────────────────────── */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: #9ca3af;
    }
    .empty-state i {
        font-size: 4rem;
        display: block;
        margin-bottom: 1rem;
        opacity: 0.4;
    }
    .empty-state h5 {
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    /* ── Row number ────────────────────────────────── */
    .row-num {
        font-size: 0.78rem;
        font-weight: 700;
        color: #9ca3af;
        min-width: 28px;
        text-align: center;
    }
</style>
@endpush

@section('content')

{{-- ── STAT CARDS ROW ─────────────────────────────────── --}}
<div class="row g-3 mb-4 mt-1">

    {{-- Total Students --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e0eaf5;">
                    <i class="bi bi-people-fill" style="color:#1e3a5f;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">
                        Total Students
                    </div>
                    <div style="font-size:1.8rem;font-weight:700;line-height:1.1;color:#1e3a5f;">
                        {{ $totalStudents }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Showing on this page --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#d1fae5;">
                    <i class="bi bi-card-list" style="color:#065f46;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">
                        Showing Now
                    </div>
                    <div style="font-size:1.8rem;font-weight:700;line-height:1.1;color:#065f46;">
                        {{ $students->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Current Page --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fef3c7;">
                    <i class="bi bi-file-earmark-text" style="color:#92400e;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">
                        Current Page
                    </div>
                    <div style="font-size:1.8rem;font-weight:700;line-height:1.1;color:#92400e;">
                        {{ $students->currentPage() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Pages --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#ede9fe;">
                    <i class="bi bi-layers" style="color:#5b21b6;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">
                        Total Pages
                    </div>
                    <div style="font-size:1.8rem;font-weight:700;line-height:1.1;color:#5b21b6;">
                        {{ $students->lastPage() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- /row --}}

{{-- ── STUDENTS TABLE CARD ─────────────────────────────── --}}
<div class="card">
    <div class="card-header bg-white d-flex align-items-center justify-content-between
                flex-wrap gap-2 border-bottom">
        <span style="font-weight:700;font-size:1rem;color:#1e3a5f;">
            <i class="bi bi-table me-2"></i>Student Records
        </span>
        <span class="badge rounded-pill"
              style="background:#e0eaf5;color:#1e3a5f;font-size:0.8rem;padding:.4rem .9rem;">
            {{ $totalStudents }} {{ Str::plural('student', $totalStudents) }} registered
        </span>
    </div>

    <div class="card-body p-0">

        @if($students->isEmpty())

            {{-- ── EMPTY STATE ── --}}
            <div class="empty-state">
                <i class="bi bi-person-x"></i>
                <h5>No students registered yet</h5>
                <p class="mb-4" style="font-size:0.92rem;">
                    Get started by adding your first student record.
                </p>
                <a href="{{ route('students.create') }}" class="btn btn-primary px-4">
                    <i class="bi bi-person-plus me-1"></i>Add First Student
                </a>
            </div>

        @else

            {{-- ── TABLE ── --}}
            <div class="table-responsive">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Student</th>
                            <th>Age</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Registered</th>
                            <th style="width:120px; text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        <tr>
                            {{-- Row number (continues across pages) --}}
                            <td>
                                <span class="row-num">
                                    {{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}
                                </span>
                            </td>

                            {{-- Name + Avatar --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    {{--
                                        Avatar uses first letter of name.
                                        Color cycles through 5 options using
                                        the student's id mod 5.
                                    --}}
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
                                            {{ $student->name }}
                                        </div>
                                        <div style="font-size:0.78rem;color:#9ca3af;">
                                            ID #{{ $student->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Age --}}
                            <td>
                                <span class="age-pill">{{ $student->age }} yrs</span>
                            </td>

                            {{-- Email --}}
                            <td>
                                <a href="mailto:{{ $student->email }}"
                                   class="text-decoration-none"
                                   style="color:#2d6a9f;font-size:0.88rem;">
                                    {{ $student->email }}
                                </a>
                            </td>

                            {{-- Course --}}
                            <td>
                                <span class="course-badge">{{ $student->course }}</span>
                            </td>

                            {{-- Registered date --}}
                            <td style="color:#9ca3af;font-size:0.82rem;white-space:nowrap;">
                                {{ $student->created_at->format('d M Y') }}
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    {{-- View --}}
                                    <a href="{{ route('students.show', $student) }}"
                                       class="btn-action btn-view"
                                       title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('students.edit', $student) }}"
                                       class="btn-action btn-edit"
                                       title="Edit Student">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Delete — triggers modal --}}
                                    <button
                                        type="button"
                                        class="btn-action btn-delete"
                                        title="Delete Student"
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

            {{-- ── TABLE FOOTER: count + pagination ── --}}
            <div class="d-flex align-items-center justify-content-between
                        flex-wrap gap-2 px-4 py-3 border-top"
                 style="background:#fafafa;">

                <small class="text-muted">
                    Showing
                    <strong>{{ $students->firstItem() }}</strong>–<strong>{{ $students->lastItem() }}</strong>
                    of <strong>{{ $students->total() }}</strong> students
                </small>

                {{--
                    ->links() renders Bootstrap-styled pagination.
                    We need to tell Laravel to use Bootstrap pagination views.
                    Add this to AppServiceProvider boot() — explained below.
                --}}
                <div>
                    {{ $students->links() }}
                </div>

            </div>

        @endif

    </div>{{-- /card-body --}}
</div>{{-- /card --}}

{{-- ── DELETE CONFIRMATION MODAL ──────────────────────── --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:14px;overflow:hidden;">

            {{-- Modal Header --}}
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body px-4 pt-3 pb-2" style="background:#fff8f8;">
                <p class="mb-0" style="color:#4b5563;">
                    Are you sure you want to permanently delete
                    <strong id="modalStudentName" style="color:#dc2626;"></strong>?
                </p>
                <p class="mb-0 mt-2" style="font-size:0.83rem;color:#9ca3af;">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    This action is irreversible. All data for this student
                    will be permanently removed from the database.
                </p>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 pt-2 gap-2" style="background:#fff8f8;">

                {{-- Cancel button — closes modal, does nothing to DB --}}
                <button type="button"
                        class="btn btn-outline-secondary btn-sm px-3"
                        data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>

                {{--
                    Delete form:
                    - method POST  (HTML can't send DELETE directly)
                    - @csrf        (protect against CSRF)
                    - @method('DELETE') adds hidden _method=DELETE field
                    - action is set dynamically by JavaScript
                --}}
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
/**
 * Delete Modal — full implementation
 *
 * Responsibilities:
 * 1. Populate modal with the correct student name + form action
 * 2. Prevent double-submission with a loading state
 * 3. Re-enable the button if the user cancels and opens another
 */
(function () {

    const modal      = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const submitBtn  = deleteForm.querySelector('button[type="submit"]');

    /**
     * When the modal is about to open, Bootstrap passes the
     * triggering element (the delete button) as event.relatedTarget.
     * We read its data attributes to personalise the modal.
     */
    modal.addEventListener('show.bs.modal', function (event) {
        const button      = event.relatedTarget;
        const studentId   = button.getAttribute('data-student-id');
        const studentName = button.getAttribute('data-student-name');

        // 1. Fill in the student name in the modal body
        document.getElementById('modalStudentName').textContent = studentName;

        // 2. Set the form action to the correct destroy URL
        //    e.g. /students/7
        deleteForm.action = '/students/' + studentId;

        // 3. Reset submit button in case it was left in loading state
        //    from a previous modal open (e.g. user opened modal,
        //    clicked delete, then navigated back with browser back btn)
        resetSubmitBtn();
    });

    /**
     * On form submission, disable the button and show a spinner.
     * This prevents double-clicking from sending two DELETE requests.
     */
    deleteForm.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" ' +
            'role="status" aria-hidden="true"></span>Deleting...';
    });

    /**
     * If the user closes the modal without confirming,
     * reset the button back to its default state.
     */
    modal.addEventListener('hide.bs.modal', function () {
        resetSubmitBtn();
    });

    function resetSubmitBtn() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-trash3 me-1"></i>Yes, Delete';
    }

})();
</script>
@endpush