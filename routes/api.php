<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EnrollmentController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API Routes (or protected if needed, keeping simple for now)
Route::apiResource('courses', CourseController::class, ['as' => 'api']);
Route::apiResource('students', StudentController::class, ['as' => 'api']);
Route::apiResource('enrollments', EnrollmentController::class, ['as' => 'api']);
