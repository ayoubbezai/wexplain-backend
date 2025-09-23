<?php

namespace App\Http\Controllers;


use App\Services\Auth\StudentService;
use App\DTOs\Auth\SignUpStudentDTO;

use App\Http\Requests\Auth\SignUpStudentRequest;

class AuthController extends Controller
{
    public function signupStudent(SignUpStudentRequest $request, StudentService $service)
    {
        $dto = SignUpStudentDTO::fromRequest($request);
        $cookie  =   $service->register($dto);


    return response()->json([
        'success' => true,
        'message' => 'Student registered successfully'
    ],201)->withCookie($cookie);
    }
}
