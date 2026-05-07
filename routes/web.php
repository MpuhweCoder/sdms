<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Dashboard — new landing page
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Students resource routes
Route::resource('students', StudentController::class);