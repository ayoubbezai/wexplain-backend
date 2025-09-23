<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\DTOs\Auth\SignUpStudentDTO;
use App\Services\Auth\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_student()
    {
        Role::create(['name' => 'student']);

        $dto = new SignUpStudentDTO(
            first_name: 'John',
            last_name: 'Doe',
            email: 'john.doe@example.com',
            password: 'password123',
            phone_number: '1234567890',
            second_number: null,
            parent_number: '1122334455',
            date_of_birth: '2005-08-15',
            address: null,
            year_of_study: '10th Grade'
        );

        $service = new StudentService();
        $service->register($dto);

        $this->assertDatabaseHas('users', ['email' => 'john.doe@example.com']);
    }
}
