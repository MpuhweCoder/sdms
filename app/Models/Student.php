<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The table associated with this model.
     * Laravel would guess 'students' automatically,
     * but being explicit is good practice.
     */
    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     * These are the fields we'll fill via create() or update().
     * Every field you want to store must be listed here.
     */
    protected $fillable = [
        'name',
        'email',
        'age',
        'course',
    ];

    /**
     * Cast age to an integer automatically.
     * This means $student->age will always be an int, not a string.
     */
    protected $casts = [
        'age' => 'integer',
    ];
}