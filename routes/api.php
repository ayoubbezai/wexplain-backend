<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;





// public endpoint
// for all roles
// inputs :
// method : post
Route::post('/v1/signup-student', [AuthController::class, 'signupStudent']);
Route::post('/v1/signup-teacher', [AuthController::class, 'signupTeacher']);
Route::post('/v1/login', [AuthController::class, 'login']);
Route::post('/v1/logout', [AuthController::class, 'logout']);
Route::middleware('auth_cookie')->get('/v1/user', [AuthController::class, 'me']);

// get all teachers
// auth : required
// roles : super_admin
// vercion : 1
Route::get('/v1/teachers', [TeacherController::class, 'index']);


// same but for one teacher
Route::get('/v1/teachers/{id}', [TeacherController::class, 'show']);

Route::get('/teachers/{teacherId}/file/{fileType}', [TeacherController::class, 'file'])->name("teachers.file");



// get all students
// auth : required
// roles : super_admin
// vercion : 1
Route::get('/v1/students', [StudentController::class, 'index']);
Route::get('/students/{studentId}', [StudentController::class, 'file'])->name("students.file");
