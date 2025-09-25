<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SignUpStudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_sign_up()
    {
        // Use the real local disk (instead of Storage::fake)
        Storage::fake('local'); // fake storage

        Role::create(['name' => 'student']);

        $studentImage = UploadedFile::fake()->image('student.jpg');

        $payload = [
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'john.doe@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'gender'                => 'male',
            'phone_number'          => '1234567890',
            'second_number'         => '0987654321',
            'parent_number'         => '1122334455',
            'date_of_birth'         => '2005-08-15',
            'address'               => '123 Main Street',
            'year_of_study'         => '10th Grade',
            'student_image'         => $studentImage,
        ];

        $response = $this->postJson('/api/v1/signup-student', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Student registered successfully',
                 ]);

        // Assert user exists in DB
        $this->assertDatabaseHas('users', ['email' => 'john.doe@example.com']);

        // Assert cookie is set
        $response->assertCookie('auth_token');

        // Assert the uploaded file exists on disk
        $student = \App\Models\Student::first();
        $this->assertNotNull($student);

        
     /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        $disk->assertExists($student->student_image_url);

        // Cleanup: remove uploaded student image
        Storage::disk('local')->delete($student->student_image_url);
    }

    public function test_email_must_be_unique()
    {
        Role::create(['name' => 'student']);

        $this->postJson('/api/v1/signup-student', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'gender' => 'male',
            'phone_number' => '1234567890',
            'parent_number' => '1122334455',
            'date_of_birth' => '2005-08-15',
            'year_of_study' => '10th Grade',
            'student_image' => UploadedFile::fake()->image('student.jpg'),
        ]);

        $response = $this->postJson('/api/v1/signup-student', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'gender' => 'female',
            'phone_number' => '0987654321',
            'parent_number' => '6677889900',
            'date_of_birth' => '2006-02-20',
            'year_of_study' => '9th Grade',
            'student_image' => UploadedFile::fake()->image('student2.jpg'),
        ]);

        $response->assertStatus(422)
                 ->assertJsonFragment(['email_unique']);
    }
}
