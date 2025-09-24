<?php

namespace App\Http\Controllers;

use Throwable;
use App\DTOs\Auth\LoginDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\DTOs\Auth\SignUpStudentDTO;
use App\DTOs\Auth\SignUpTeacherDTO;
use App\Services\Auth\LoginService;
use App\Services\Auth\StudentService;
use App\Services\TeacherService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignUpStudentRequest;
use App\Http\Requests\Auth\SignUpTeacherRequest;

class AuthController extends Controller
{
    public function signupStudent(SignUpStudentRequest $request, StudentService $service): JsonResponse
    {
        try {
            $dto = SignUpStudentDTO::fromRequest($request);

            $cookie = $service->register($dto);

            return response()->json([
                'success' => true,
                'message' => 'Student registered successfully'
            ], 201)->withCookie($cookie);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function signupTeacher(SignUpTeacherRequest $request, TeacherService $service): JsonResponse
    {
        try {
            $dto = SignUpTeacherDTO::fromRequest($request);

            $cookie = $service->register($dto);

            return response()->json([
                'success' => true,
                'message' => 'Teacher registered successfully',


            ], 201)->withCookie($cookie);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function login (LoginRequest $request , LoginService $service):JsonResponse {
        try{
            $dto = LoginDto::fromRequest($request);

            $loginResult = $service->login($dto);

            if ($loginResult) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged in successfully',
                    'token'   => $loginResult['token'],
                    'role'    => $loginResult['role'],
                ], 200)->withCookie($loginResult['cookie']);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'invalid_credentials',
                ], 401);
            }


         } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

}
