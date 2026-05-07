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
    /**
 * INDEX — Display a listing of all students.
 * Route: GET /students
 *
 * Student::latest() orders by created_at DESC so newest students
 * appear at the top. paginate(10) splits results into pages of 10.
 */
public function index(): View
{
    $students = Student::latest()->paginate(10);

    // total count for the stat card
    $totalStudents = Student::count();

    return view('students.index', compact('students', 'totalStudents'));
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
 * EDIT — Show the prefilled edit form.
 * Route: GET /students/{student}/edit
 *
 * Laravel's Route Model Binding automatically fetches the
 * Student record from the DB using the {student} ID in the URL.
 * If the ID doesn't exist → automatic 404. No manual query needed.
 */
    public function edit(Student $student): View
    {
        return view('students.edit', compact('student'));
    }

   /**
 * UPDATE — Validate and save changes to the DB.
 * Route: PUT /students/{student}
 *
 * The crucial difference from store() is the email uniqueness rule.
 * We must tell Laravel: "this email is unique in the students table,
 * BUT ignore the row whose id = $student->id".
 * Without this, submitting the form without changing the email
 * would fail its own uniqueness check.
 */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:100',
            'age'    => 'required|integer|min:1|max:120',
                 // unique:table,column,ignoreId
        // This tells the validator: check the students table,
        // email column, but skip the row with id = $student->id
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