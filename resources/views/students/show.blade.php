@extends('layouts.app')

@section('title', $student->name . ' — SDMS')

@section('page-header')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-person-badge-fill me-2"></i>Student Profile</h1>
            <p>Viewing full details for {{ $student->name }}</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Students
        </a>
    </div>
@endsection

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-lg-6 col-md-8">

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

        <div class="card">
            {{-- Profile header --}}
            <div class="card-body text-center py-4"
                 style="background:linear-gradient(135deg,#1e3a5f,#2d6a9f);
                        border-radius:12px 12px 0 0;">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:72px;height:72px;border-radius:50%;
                            background:{{ $color['bg'] }};font-size:1.8rem;
                            font-weight:700;color:{{ $color['text'] }};">
                    {{ strtoupper(substr($student->name, 0, 1)) }}
                </div>
                <h4 class="mb-0 text-white fw-bold">{{ $student->name }}</h4>
                <small style="color:rgba(255,255,255,0.7);">Student ID #{{ $student->id }}</small>
            </div>

            {{-- Details --}}
            <div class="card-body px-4 py-3">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td style="width:40%;color:#9ca3af;font-size:0.85rem;
                                       font-weight:600;text-transform:uppercase;
                                       letter-spacing:.5px;">
                                <i class="bi bi-calendar3 me-2"></i>Age
                            </td>
                            <td style="font-weight:600;color:#111827;">
                                {{ $student->age }} years old
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#9ca3af;font-size:0.85rem;
                                       font-weight:600;text-transform:uppercase;
                                       letter-spacing:.5px;">
                                <i class="bi bi-envelope me-2"></i>Email
                            </td>
                            <td>
                                <a href="mailto:{{ $student->email }}"
                                   style="color:#2d6a9f;font-weight:500;">
                                    {{ $student->email }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#9ca3af;font-size:0.85rem;
                                       font-weight:600;text-transform:uppercase;
                                       letter-spacing:.5px;">
                                <i class="bi bi-book me-2"></i>Course
                            </td>
                            <td>
                                <span style="display:inline-block;padding:.28rem .75rem;
                                             border-radius:20px;font-size:.8rem;
                                             font-weight:600;background:#e0eaf5;
                                             color:#1e3a5f;">
                                    {{ $student->course }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#9ca3af;font-size:0.85rem;
                                       font-weight:600;text-transform:uppercase;
                                       letter-spacing:.5px;">
                                <i class="bi bi-clock me-2"></i>Registered
                            </td>
                            <td style="color:#374151;">
                                {{ $student->created_at->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

           {{-- Action buttons --}}
<div class="card-footer bg-white border-top d-flex gap-2
            justify-content-between align-items-center p-3">

    <a href="{{ route('students.index') }}"
       class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>

    <div class="d-flex gap-2">
        <a href="{{ route('students.edit', $student) }}"
           class="btn btn-primary btn-sm px-3">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>

        {{-- Delete button triggers modal --}}
        <button type="button"
                class="btn btn-danger btn-sm px-3"
                data-bs-toggle="modal"
                data-bs-target="#deleteModal"
                data-student-id="{{ $student->id }}"
                data-student-name="{{ $student->name }}">
            <i class="bi bi-trash3 me-1"></i>Delete
        </button>
    </div>
</div>

{{-- Include the delete modal (reused from index) --}}
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
                    <h5 class="modal-title mb-0" style="font-weight:700;">
                        Confirm Deletion
                    </h5>
                </div>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-2" style="background:#fff8f8;">
                <p class="mb-0" style="color:#4b5563;">
                    Are you sure you want to permanently delete
                    <strong id="modalStudentName"
                            style="color:#dc2626;">{{ $student->name }}</strong>?
                </p>
                <p class="mb-0 mt-2" style="font-size:0.83rem;color:#9ca3af;">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    This cannot be undone.
                </p>
            </div>
            <div class="modal-footer border-0 pt-2" style="background:#fff8f8;">
                <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
                <form id="deleteForm"
                      action="{{ route('students.destroy', $student) }}"
                      method="POST" class="d-inline">
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

@push('scripts')
<script>
(function () {
    const deleteForm = document.getElementById('deleteForm');
    const submitBtn  = deleteForm.querySelector('button[type="submit"]');
    deleteForm.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
    });
})();
</script>
@endpush

        </div>
    </div>
</div>
@endsection