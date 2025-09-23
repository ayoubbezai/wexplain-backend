<?php

namespace App\Services\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\DTOs\Auth\SignUpStudentDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class StudentService
{
    public function register(SignUpStudentDTO $dto)
    {
        return DB::transaction(function () use ($dto) {

            // Get student role
            $role = Role::where("name", "student")->firstOrFail();

            // Create user
            $user = User::create([
                'first_name'     => $dto->first_name,
                'last_name'     => $dto->last_name,
                'email'    => $dto->email,
                'role_id'  => $role->id,
                'password' => $dto->password,
            ]);

            // Create student
            Student::create([
                'user_id'       => $user->id,
                'phone_number'  => $dto->phone_number,
                'second_number' => $dto->second_number,
                'parent_number' => $dto->parent_number,
                'date_of_birth' => $dto->date_of_birth,
                'address'       => $dto->address,
                'year_of_study' => $dto->year_of_study,
            ]);

            // Generate token (using Sanctum)
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return token cookie
            return Cookie::make(
                'auth_token',  // cookie name
                $token,        // token value
                60 * 24,       // 1 day in minutes
                '/',           // path
                null,          // domain
                false,         // secure, set true in production
                true           // httpOnly
            );
        });
    }
}
