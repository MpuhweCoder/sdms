<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentController extends Controller
{
    /**
 * INDEX — List students with search, sort and pagination.
 * Route: GET /students
 *
 * Query parameters accepted:
 *   ?search=arjun        → filters name OR email OR course
 *   ?sort=name           → column to sort by (name/age/email/course/created_at)
 *   ?direction=asc|desc  → sort direction (default: asc)
 *   ?page=2              → pagination page (handled by Laravel automatically)
 */
public function index(Request $request): View
{
    // ── 1. Read & sanitise query parameters ──────────────
    $search    = trim($request->get('search', ''));
    $sort      = $request->get('sort', 'created_at');
    $direction = $request->get('direction', 'desc');

    // Whitelist allowed sort columns to prevent SQL injection
    // via the sort parameter. Any value not in this list
    // falls back to 'created_at'.
    $allowedSorts = ['name', 'age', 'email', 'course', 'created_at'];
    if (!in_array($sort, $allowedSorts)) {
        $sort = 'created_at';
    }

    // Whitelist direction too
    $direction = $direction === 'asc' ? 'asc' : 'desc';

    // ── 2. Build the query ────────────────────────────────
    $query = Student::query();

    if ($search !== '') {
        /*
         * Search across three columns simultaneously.
         * We wrap the OR conditions in a closure so they
         * are grouped together:
         *
         *   WHERE (name LIKE ? OR email LIKE ? OR course LIKE ?)
         *
         * Without the closure wrapping, adding any future
         * AND conditions would break the logic.
         */
        $query->where(function ($q) use ($search) {
            $q->where('name',   'LIKE', "%{$search}%")
              ->orWhere('email',  'LIKE', "%{$search}%")
              ->orWhere('course', 'LIKE', "%{$search}%");
        });
    }

    // ── 3. Apply sort ─────────────────────────────────────
    $query->orderBy($sort, $direction);

    // ── 4. Paginate ───────────────────────────────────────
    /*
     * ->withQueryString() is crucial here.
     * Without it, clicking page 2 would produce:
     *   /students?page=2
     * losing your search and sort parameters.
     *
     * With it, page links carry ALL current query params:
     *   /students?search=arjun&sort=name&direction=asc&page=2
     */
    $students = $query->paginate(10)->withQueryString();

    // ── 5. Stats ──────────────────────────────────────────
    $totalStudents = Student::count();   // always the grand total
    $filteredCount = $query->toBase()->getCountForPagination();

    return view('students.index', compact(
        'students',
        'totalStudents',
        'search',
        'sort',
        'direction'
    ));
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
 * DESTROY — Delete a student record from the database.
 * Route: DELETE /students/{student}
 *
 * Route Model Binding gives us the Student instance directly.
 * If the ID doesn't exist Laravel throws a 404 automatically —
 * no need for findOrFail().
 *
 * After deletion we redirect back to the index with a
 * success flash message so the user knows it worked.
 */
public function destroy(Student $student): RedirectResponse
{
    // Store the name before we delete so we can use it
    // in the flash message (after deletion $student is gone)
    $studentName = $student->name;

    // Eloquent delete — fires model events (useful for observers later)
    $student->delete();

    return redirect()->route('students.index')
                     ->with('success', "Student \"{$studentName}\" has been deleted successfully.");
}