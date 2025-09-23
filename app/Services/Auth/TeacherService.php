<?php

namespace App\Services\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Teacher;
use App\DTOs\Auth\SignUpTeacherDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;

class TeacherService
{
    public function register(SignUpTeacherDTO $dto)
    {
        return DB::transaction(function () use ($dto) {
            // Get teacher role
            $role = Role::where('name', 'teacher')->firstOrFail();

            // Create user
            $user = User::create([
                'first_name' => $dto->first_name,
                'last_name'  => $dto->last_name,
                'email'      => $dto->email,
                'role_id'    => $role->id,
                'password'   => Hash::make($dto->password),
            ]);

            // Store files privately (not publicly accessible)
            $teacherImagePath = $dto->teacher_image->store('teachers/images', 'local');
            $idCardImagePath  = $dto->id_card_image->store('teachers/id_cards', 'local');
            $cvPath           = $dto->cv_pdf->store('teachers/cvs', 'local');

            // Create teacher
            Teacher::create([
                'user_id'              => $user->id,
                'gender'               => $dto->gender,
                'phone_number'         => $dto->phone_number,
                'second_phone_number'  => $dto->second_phone_number,
                'nationality'          => $dto->nationality,
                'date_of_birth'        => $dto->date_of_birth,
                'address'              => $dto->address,
                'id_card_image_url'    => $idCardImagePath, // private path
                'cv_url'               => $cvPath,          // private path
                'teacher_image_url' => $teacherImagePath,
                'primary_subject'      => $dto->primary_subject,
                'other_subjects'       => $dto->other_subjects,
                'teaching_level'       => $dto->teaching_level,
                'years_of_experience'  => $dto->years_of_experience,
                'ccp_number'           => $dto->ccp_number,
                'ccp_key'              => $dto->ccp_key,
                'ccp_account_name'     => $dto->ccp_account_name,
                'card_number'          => $dto->card_number,
                'card_expiry'          => $dto->card_expiry,
                'card_cvv'             => $dto->card_cvv,
                'card_holder_name'     => $dto->card_holder_name,
                'credit'               => 0, // default
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
