<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;


// public endpoint
// for all roles
// inputs :
// method : post
Route::post('/v1/signup-student', [AuthController::class, 'signupStudent']);
Route::post('/v1/signup-teacher', [AuthController::class, 'signupTeacher']);
Route::post('/v1/login', [AuthController::class, 'login']);

// get all teachers
// auth : required
// roles : super_admin
// vercion : 1
Route::get('/v1/teachers', [TeacherController::class, 'index']);
