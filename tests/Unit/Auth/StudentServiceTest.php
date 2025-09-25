<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\DTOs\Auth\SignUpStudentDTO;
use App\Services\StudentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StudentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_student()
    {
        // Fake storage for file uploads
        Storage::fake('local');

        Role::create(['name' => 'student']);

        $dto = new SignUpStudentDTO(
            first_name: 'John',
            last_name: 'Doe',
            email: 'john.doe@example.com',
            password: 'password123',
            gender: 'male', // required now
            phone_number: '1234567890',
            second_number: null,
            parent_number: '1122334455',
            date_of_birth: '2005-08-15',
            address: null,
            year_of_study: '10th Grade',
            student_image: UploadedFile::fake()->image('student.jpg') // required now
        );

        $service = new StudentService();
        $service->register($dto);

        // Assert user was created
        $this->assertDatabaseHas('users', ['email' => 'john.doe@example.com']);

        // Assert student was created
        $user = User::where('email', 'john.doe@example.com')->firstOrFail();
        $this->assertDatabaseHas('students', ['user_id' => $user->id]);

        // Assert student image exists on disk
        $student = Student::where('user_id', $user->id)->firstOrFail();

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        $disk->assertExists($student->student_image_url);

        // Cleanup: remove uploaded student image
        Storage::disk('local')->delete($student->student_image_url);
    }
}
