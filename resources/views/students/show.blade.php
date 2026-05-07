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
            <div class="card-footer bg-white border-top d-flex gap-2 justify-content-end p-3">
                <a href="{{ route('students.edit', $student) }}"
                   class="btn btn-primary btn-sm px-3">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('students.index') }}"
                   class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>

    </div>
</div>
@endsection