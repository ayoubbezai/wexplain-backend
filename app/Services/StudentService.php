<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use App\DTOs\Auth\SignUpStudentDTO;
use Illuminate\Support\Facades\Hash;
use App\DTOs\Student\StudentIndexDTO;
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
                'gender'  => $dto->gender,
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

        public function getAll(StudentIndexDTO $dto)
    {
        $query = Student::query()
            ->select([
                'students.id',
                'students.user_id',
                'students.gender',
                'students.phone_number',
                'students.second_number',
                'students.date_of_birth',
                'students.address',
                'students.year_of_study',
                'students.created_at'
            ])
            ->join('users', 'students.user_id', '=', 'users.id')
            ->addSelect([
                'users.first_name',
                'users.last_name',
                'users.email',
            ]);

        if ($dto->search) {
            $query->where(function ($q) use ($dto) {
                $searchTerm = "%{$dto->search}%";

                $q->whereAny([
                    "users.first_name",
                    "users.last_name",
                    "students.phone_number",
                    "students.second_number"
                ], 'LIKE', $searchTerm)
                ->orWhereRaw("users.first_name || ' ' || users.last_name LIKE ?", [$searchTerm])
                ->orWhereRaw("users.last_name || ' ' || users.first_name LIKE ?", [$searchTerm]);
            });
        }

        if ($dto->gender && in_array($dto->gender, ['male', 'female'])) {
            $query->where('students.gender', $dto->gender);
        }

        $sortColumn = in_array($dto->sortBy, ['first_name', 'last_name'])
            ? "users.{$dto->sortBy}"
            : "students.{$dto->sortBy}";

        $query->orderBy($sortColumn, $dto->sortDir);

        return $query->paginate($dto->perPage, ['*'], 'page', $dto->page);
    }

}
