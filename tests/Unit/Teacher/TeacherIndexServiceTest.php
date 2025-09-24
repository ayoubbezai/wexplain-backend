<?php

namespace Tests\Unit\Teacher;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Teacher;
use App\DTOs\Teacher\TeacherIndexDTO;
use App\Services\TeacherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class TeacherIndexServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_with_search_and_gender_filters(): void
    {
        // Create role
        $role = Role::firstOrCreate(['name' => 'teacher']);

        // Create users and teachers
        $user1 = User::create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'role_id'    => $role->id,
            'email'      => 'john@example.com',
            'password'   => Hash::make('password123'),
        ]);

        Teacher::create([
            'user_id' => $user1->id,
            'gender' => 'male',
            'phone_number' => '1234567890',
            'second_phone_number' => '0987654321',
            'nationality' => 'USA',
            'teacher_image_url' => '',
            'id_card_image_url' => '',
            'cv_url' => '',
            'address' => '',
            'primary_subject' => 'Math',
            'teaching_level' => 'High School',
            'years_of_experience' => 5,
            'credit' => 0,
            'date_of_birth' => '1990-01-01',
        ]);

        $user2 = User::create([
            'first_name' => 'Jane',
            'last_name'  => 'Smith',
            'role_id'    => $role->id,
            'email'      => 'jane@example.com',
            'password'   => Hash::make('password123'),
        ]);

        Teacher::create([
            'user_id' => $user2->id,
            'gender' => 'female',
            'phone_number' => '5555555555',
            'second_phone_number' => '4444444444',
            'nationality' => 'USA',
            'teacher_image_url' => '',
            'id_card_image_url' => '',
            'cv_url' => '',
            'address' => '',
            'primary_subject' => 'Science',
            'teaching_level' => 'Middle School',
            'years_of_experience' => 3,
            'credit' => 0,
            'date_of_birth' => '1985-05-10',
        ]);

        // Create DTO
        $dto = new TeacherIndexDTO(
            search: 'John',
            page: 1,
            perPage: 10,
            sortBy: 'created_at',
            sortDir: 'desc',
            gender: 'male'
        );

        $service = app()->make(TeacherService::class);
        $result = $service->getAll($dto);

        // Assertions
        $this->assertEquals(1, $result->total()); // only John matches
        $teacher = $result->first();
        $this->assertEquals('John', $teacher->user->first_name);
        $this->assertEquals('male', $teacher->gender);
    }
}
