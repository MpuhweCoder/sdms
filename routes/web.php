<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root URL to students list
Route::get('/', function () {
    return redirect()->route('students.index');
});

// Resource routes — generates all 7 RESTful routes automatically
Route::resource('students', StudentController::class);