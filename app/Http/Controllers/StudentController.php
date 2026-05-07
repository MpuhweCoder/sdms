<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentController extends Controller
{
    /**
     * INDEX — Display a listing of all students.
     * Route: GET /students
     *
     * Later we'll add search + pagination here.
     * For now, just fetch all records.
     */
    public function index(): View
    {
        $students = Student::latest()->paginate(10);

        return view('students.index', compact('students'));
    }

    /**
     * CREATE — Show the form to add a new student.
     * Route: GET /students/create
     */
    public function create(): View
    {
        return view('students.create');
    }

    /**
     * STORE — Validate and save a new student to the DB.
     * Route: POST /students
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:100',
            'age'    => 'required|integer|min:1|max:120',
            'email'  => 'required|email|max:150|unique:students,email',
            'course' => 'required|string|max:100',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
                         ->with('success', 'Student added successfully!');
    }

    /**
     * SHOW — Display a single student's details.
     * Route: GET /students/{student}
     *
     * Laravel automatically resolves {student} to a Student model instance.
     * This is called "Route Model Binding".
     */
    public function show(Student $student): View
    {
        return view('students.show', compact('student'));
    }

    /**
     * EDIT — Show the form to edit an existing student.
     * Route: GET /students/{student}/edit
     */
    public function edit(Student $student): View
    {
        return view('students.edit', compact('student'));
    }

    /**
     * UPDATE — Validate and update the student in the DB.
     * Route: PUT/PATCH /students/{student}
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:100',
            'age'    => 'required|integer|min:1|max:120',
            'email'  => 'required|email|max:150|unique:students,email,' . $student->id,
            'course' => 'required|string|max:100',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
                         ->with('success', 'Student updated successfully!');
    }

    /**
     * DESTROY — Delete a student from the DB.
     * Route: DELETE /students/{student}
     */
    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('students.index')
                         ->with('success', 'Student deleted successfully!');
    }
}