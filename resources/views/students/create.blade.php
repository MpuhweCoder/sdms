@extends('layouts.app')

@section('title', 'Add Student — SDMS')

@section('page-header')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('students.index') }}">Students</a>
                    </li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>
            <h1><i class="bi bi-person-plus-fill me-2"></i>Add New Student</h1>
            <p>Fill in the details below to register a new student.</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Students
        </a>
    </div>
@endsection

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-lg-7 col-md-9">

        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-person-vcard me-2"></i>Student Registration Form
            </div>

            <div class="card-body p-4">

                {{--
                    ACTION  → students.store  (POST /students)
                    METHOD  → POST
                    The @csrf directive inserts a hidden _token field.
                    Laravel verifies this on every POST/PUT/DELETE to
                    protect against Cross-Site Request Forgery attacks.
                --}}
                <form action="{{ route('students.store') }}" method="POST" novalidate>
                    @csrf

                    {{-- Name --}}
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            Full Name <span class="required-star">*</span>
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
                                value="{{ old('name') }}"
                                placeholder="e.g. Arjun Sharma"
                                maxlength="100"
                                autofocus
                            >
                            {{--
                                @error('name') checks if Laravel's validator
                                put an error message for the 'name' field.
                                is-invalid adds Bootstrap's red border.
                                old('name') repopulates the field on failure
                                so the user doesn't retype everything.
                            --}}
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Age --}}
                    <div class="mb-4">
                        <label for="age" class="form-label">
                            Age <span class="required-star">*</span>
                        </label>
                        <div class="input-group" style="max-width: 200px;">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-calendar3 text-secondary"></i>
                            </span>
                            <input
                                type="number"
                                id="age"
                                name="age"
                                class="form-control @error('age') is-invalid @enderror"
                                value="{{ old('age') }}"
                                placeholder="e.g. 20"
                                min="1"
                                max="120"
                            >
                            @error('age')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            Email Address <span class="required-star">*</span>
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
                                value="{{ old('email') }}"
                                placeholder="e.g. arjun@example.com"
                                maxlength="150"
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Course --}}
                    <div class="mb-4">
                        <label for="course" class="form-label">
                            Course / Program <span class="required-star">*</span>
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
                                <option value="" disabled {{ old('course') ? '' : 'selected' }}>
                                    — Select a course —
                                </option>
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
                                    <option value="{{ $c }}"
                                        {{ old('course') === $c ? 'selected' : '' }}>
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
                    </div>

                    {{-- Divider --}}
                    <hr class="my-4">

                    {{-- Submit --}}
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-person-check me-1"></i>Register Student
                        </button>
                    </div>

                </form>
            </div>{{-- card-body --}}
        </div>{{-- card --}}

    </div>
</div>
@endsection