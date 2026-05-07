@extends('layouts.app')

@section('title', 'Edit ' . $student->name . ' — SDMS')

@section('page-header')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-pencil-square me-2"></i>Edit Student</h1>
            <p>Update the details for <strong>{{ $student->name }}</strong></p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Students
        </a>
    </div>
@endsection

@push('styles')
<style>
    /*
     * Changed-field highlight:
     * When a field's value differs from the original,
     * we add the .field-changed class via JS to give
     * the user visual feedback on what they've modified.
     */
    .field-changed .form-control,
    .field-changed .form-select {
        border-color: #f59e0b !important;
        background: #fffbeb !important;
        box-shadow: 0 0 0 3px rgba(245,158,11,0.15) !important;
    }
    .field-changed .form-label::after {
        content: ' ✎ modified';
        font-size: 0.72rem;
        color: #b45309;
        font-weight: 500;
        margin-left: 6px;
    }

    /* Original value tooltip shown below the field */
    .original-value {
        font-size: 0.76rem;
        color: #9ca3af;
        margin-top: 4px;
        display: none;         /* hidden until field changes */
    }
    .field-changed .original-value {
        display: block;        /* show when changed */
        color: #b45309;
    }

    /* Change summary badge */
    #changeSummary {
        display: none;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        background: #fffbeb;
        border: 1.5px solid #fde68a;
        color: #92400e;
        font-size: 0.88rem;
        font-weight: 500;
    }
    #changeSummary.visible {
        display: block;
    }
    #changeSummary i {
        color: #f59e0b;
    }

    /* Profile chip in the card header */
    .student-chip {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 6px 14px 6px 6px;
        background: rgba(255,255,255,0.15);
        border-radius: 50px;
    }
    .student-chip-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-lg-7 col-md-9">

        {{-- ── STUDENT IDENTITY CHIP ──────────────────── --}}
        {{--
            Shows who you're editing at a glance.
            Helps prevent accidentally editing the wrong student
            in a long list.
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

        <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3"
             style="background:white;box-shadow:0 1px 8px rgba(0,0,0,0.06);">
            <div style="width:46px;height:46px;border-radius:50%;
                        background:{{ $color['bg'] }};color:{{ $color['text'] }};
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.2rem;font-weight:700;flex-shrink:0;">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
                <div style="font-weight:700;color:#111827;">{{ $student->name }}</div>
                <div style="font-size:0.8rem;color:#9ca3af;">
                    Student ID #{{ $student->id }} &nbsp;·&nbsp;
                    Registered {{ $student->created_at->format('d M Y') }}
                </div>
            </div>
            <a href="{{ route('students.show', $student) }}"
               class="btn btn-outline-secondary btn-sm ms-auto">
                <i class="bi bi-eye me-1"></i>View Profile
            </a>
        </div>

        {{-- ── CHANGE SUMMARY BANNER ──────────────────── --}}
        {{-- Shown by JS when any field is modified --}}
        <div id="changeSummary" class="mb-3">
            <i class="bi bi-pencil me-2"></i>
            <span id="changeSummaryText">You have unsaved changes.</span>
        </div>

        {{-- ── EDIT CARD ────────────────────────────────── --}}
        <div class="card">
            <div class="card-header text-white"
                 style="background:linear-gradient(135deg,#1e3a5f,#2d6a9f);">
                <i class="bi bi-pencil-square me-2"></i>Edit Student Details
            </div>

            <div class="card-body p-4">

                {{--
                    ACTION  → students.update  (PUT /students/{id})
                    METHOD  → POST  (HTML forms only support GET/POST)
                    @method('PUT') adds a hidden _method=PUT field.
                    Laravel reads this and routes to update() not store().

                    data-original-* attributes store the current DB values.
                    JavaScript compares against these to detect changes.
                --}}
                <form
                    action="{{ route('students.update', $student) }}"
                    method="POST"
                    novalidate
                    id="editForm"
                    data-original-name="{{ $student->name }}"
                    data-original-age="{{ $student->age }}"
                    data-original-email="{{ $student->email }}"
                    data-original-course="{{ $student->course }}"
                >
                    @csrf
                    @method('PUT')

                    {{-- ── Name ────────────────────────────── --}}
                    <div class="mb-4 field-wrapper" id="wrap-name">
                        <label for="name" class="form-label">
                            Full Name <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-person text-secondary"></i>
                            </span>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $student->name) }}"
                                placeholder="Full name"
                                maxlength="100"
                                autofocus
                            >
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{--
                            old('name', $student->name) means:
                            - If coming back from a validation failure → show old input
                            - Otherwise (first load) → show the DB value
                        --}}
                        <div class="original-value">
                            <i class="bi bi-clock-history me-1"></i>
                            Original: <strong>{{ $student->name }}</strong>
                        </div>
                    </div>

                    {{-- ── Age ─────────────────────────────── --}}
                    <div class="mb-4 field-wrapper" id="wrap-age">
                        <label for="age" class="form-label">
                            Age <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="input-group" style="max-width:200px;">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-calendar3 text-secondary"></i>
                            </span>
                            <input
                                type="number"
                                id="age"
                                name="age"
                                class="form-control @error('age') is-invalid @enderror"
                                value="{{ old('age', $student->age) }}"
                                min="1"
                                max="120"
                            >
                            @error('age')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="original-value">
                            <i class="bi bi-clock-history me-1"></i>
                            Original: <strong>{{ $student->age }} years</strong>
                        </div>
                    </div>

                    {{-- ── Email ────────────────────────────── --}}
                    <div class="mb-4 field-wrapper" id="wrap-email">
                        <label for="email" class="form-label">
                            Email Address <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-envelope text-secondary"></i>
                            </span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $student->email) }}"
                                placeholder="email@example.com"
                                maxlength="150"
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="original-value">
                            <i class="bi bi-clock-history me-1"></i>
                            Original: <strong>{{ $student->email }}</strong>
                        </div>
                    </div>

                    {{-- ── Course ───────────────────────────── --}}
                    <div class="mb-4 field-wrapper" id="wrap-course">
                        <label for="course" class="form-label">
                            Course / Program <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-book text-secondary"></i>
                            </span>
                            <select
                                id="course"
                                name="course"
                                class="form-select @error('course') is-invalid @enderror"
                            >
                                <option value="" disabled>— Select a course —</option>
                                @php
                                    $courses = [
                                        'B.Tech Computer Science',
                                        'B.Tech Information Technology',
                                        'B.Tech Electronics',
                                        'B.Sc Mathematics',
                                        'B.Sc Physics',
                                        'B.Com',
                                        'BBA',
                                        'MBA',
                                        'MCA',
                                        'M.Tech Computer Science',
                                    ];
                                @endphp
                                @foreach($courses as $c)
                                    {{--
                                        old('course', $student->course) picks the
                                        right option just like the text inputs:
                                        validation failure → old input
                                        first load → current DB value
                                    --}}
                                    <option value="{{ $c }}"
                                        {{ old('course', $student->course) === $c ? 'selected' : '' }}>
                                        {{ $c }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="original-value">
                            <i class="bi bi-clock-history me-1"></i>
                            Original: <strong>{{ $student->course }}</strong>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- ── Buttons ──────────────────────────── --}}
                    <div class="d-flex gap-3 justify-content-between align-items-center">

                        <a href="{{ route('students.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </a>

                        <div class="d-flex gap-2">
                            {{-- Reset button — restores all fields to DB values --}}
                            <button type="button"
                                    id="resetBtn"
                                    class="btn btn-outline-warning"
                                    style="display:none!important;"
                                    onclick="resetFields()">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                            </button>

                            <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                <i class="bi bi-check-circle me-1"></i>Save Changes
                            </button>
                        </div>

                    </div>

                </form>
            </div>{{-- /card-body --}}
        </div>{{-- /card --}}

    </div>
</div>
@endsection

@push('scripts')
<script>
/**
 * Edit form — change detection
 *
 * Reads data-original-* from the <form> element.
 * On every input/change event, compares current values
 * to originals and highlights changed fields.
 */
(function () {
    const form     = document.getElementById('editForm');
    const resetBtn = document.getElementById('resetBtn');
    const summary  = document.getElementById('changeSummary');
    const summaryText = document.getElementById('changeSummaryText');

    // Map each field id → its wrapper div id
    const fields = {
        name:   { input: document.getElementById('name'),   wrap: document.getElementById('wrap-name') },
        age:    { input: document.getElementById('age'),    wrap: document.getElementById('wrap-age') },
        email:  { input: document.getElementById('email'),  wrap: document.getElementById('wrap-email') },
        course: { input: document.getElementById('course'), wrap: document.getElementById('wrap-course') },
    };

    // Original values from the form's data attributes
    const originals = {
        name:   form.dataset.originalName,
        age:    form.dataset.originalAge,
        email:  form.dataset.originalEmail,
        course: form.dataset.originalCourse,
    };

    function getCurrentValues() {
        return {
            name:   fields.name.input.value.trim(),
            age:    fields.age.input.value.trim(),
            email:  fields.email.input.value.trim(),
            course: fields.course.input.value,
        };
    }

    function checkChanges() {
        const current = getCurrentValues();
        const changedFields = [];

        // Loop through each field and compare to original
        for (const key in fields) {
            const changed = String(current[key]) !== String(originals[key]);
            fields[key].wrap.classList.toggle('field-changed', changed);
            if (changed) changedFields.push(key);
        }

        // Show/hide summary banner and reset button
        if (changedFields.length > 0) {
            const names = changedFields.map(f =>
                f.charAt(0).toUpperCase() + f.slice(1)
            ).join(', ');
            summaryText.textContent =
                `${changedFields.length} field${changedFields.length > 1 ? 's' : ''} modified: ${names}`;
            summary.classList.add('visible');
            resetBtn.style.removeProperty('display');
        } else {
            summary.classList.remove('visible');
            resetBtn.style.setProperty('display', 'none', 'important');
        }
    }

    // Attach listeners to all fields
    Object.values(fields).forEach(f => {
        f.input.addEventListener('input',  checkChanges);
        f.input.addEventListener('change', checkChanges);
    });

    // Reset all fields back to original DB values
    window.resetFields = function () {
        fields.name.input.value   = originals.name;
        fields.age.input.value    = originals.age;
        fields.email.input.value  = originals.email;
        fields.course.input.value = originals.course;
        checkChanges(); // re-run to clear highlights
    };

    // Warn before leaving with unsaved changes
    window.addEventListener('beforeunload', function (e) {
        const current = getCurrentValues();
        const hasChanges = Object.keys(originals).some(
            k => String(current[k]) !== String(originals[k])
        );
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Don't warn if they actually submitted the form
    form.addEventListener('submit', function () {
        window.removeEventListener('beforeunload', arguments.callee);
    });

})();
</script>
@endpush