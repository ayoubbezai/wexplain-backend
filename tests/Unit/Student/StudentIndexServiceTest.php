<?php

namespace Tests\Unit\Student;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\DTOs\Student\StudentIndexDTO;
use App\Services\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StudentIndexServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_with_search_and_gender_filters(): void
    {
        // Use fake storage for student images
        Storage::fake('local');

        // Create student role
        $role = Role::firstOrCreate(['name' => 'student']);

        // Create users and students
        $user1 = User::create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'role_id'    => $role->id,
            'email'      => 'john@example.com',
            'password'   => Hash::make('password123'),
        ]);

        Student::create([
            'user_id' => $user1->id,
            'gender' => 'male',
            'phone_number' => '1234567890',
            'second_number' => '0987654321',
            'parent_number' => '1111111111',
            'student_image_url' => UploadedFile::fake()->image('john.jpg')->store('students/images', 'local'),
            'date_of_birth' => '2005-08-15',
            'address' => '123 Main St',
            'year_of_study' => '10th Grade',
        ]);

        $user2 = User::create([
            'first_name' => 'Jane',
            'last_name'  => 'Smith',
            'role_id'    => $role->id,
            'email'      => 'jane@example.com',
            'password'   => Hash::make('password123'),
        ]);

        Student::create([
            'user_id' => $user2->id,
            'gender' => 'female',
            'phone_number' => '5555555555',
            'second_number' => '4444444444',
            'parent_number' => '2222222222',
            'student_image_url' => UploadedFile::fake()->image('jane.jpg')->store('students/images', 'local'),
            'date_of_birth' => '2006-02-20',
            'address' => '456 Oak Ave',
            'year_of_study' => '9th Grade',
        ]);

        // Create DTO with search & gender filter
        $dto = new StudentIndexDTO(
            search: 'John',
            page: 1,
            perPage: 10,
            sortBy: 'created_at',
            sortDir: 'desc',
            gender: 'male'
        );

        $service = app()->make(StudentService::class);
        $result = $service->getAll($dto);

        // Assertions
        $this->assertEquals(1, $result->total()); // only John matches
        $student = $result->first();
        $this->assertEquals('John', $student->first_name);
        $this->assertEquals('male', $student->gender);


     /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        $disk->assertExists($student->student_image_url);

        // Cleanup fake storage (optional, because Storage::fake cleans automatically)
        Storage::disk('local')->delete($student->student_image_url);
    }
}
