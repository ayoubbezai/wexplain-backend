<?php

namespace Tests\Feature\Teacher;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;

class TeacherShowTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_retrieve_a_teacher_after_signup(): void
    {
        Storage::fake('local');
        Role::create(['name' => 'teacher']);

        // Register a teacher via API
        $signupResponse = $this->postJson('/api/v1/signup-teacher', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'gender' => 'male',
            'nationality' => 'Algerian',
            'phone_number' => '0551122334',
            'date_of_birth' => '1990-01-01',
            'primary_subject' => 'Math',
            'teaching_level' => 'High School',
            'years_of_experience' => 5,
            'id_card_image' => UploadedFile::fake()->create('id.jpg', 200, 'image/jpeg'),
            'teacher_image' => UploadedFile::fake()->create('teacher.jpg', 200, 'image/jpeg'),
            'cv_pdf' => UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf'),
        ]);

        $signupResponse->assertStatus(201)
                       ->assertJson(['success' => true]);

        // Get teacher from DB since the signup response may not return teacher ID
        $teacherId = \App\Models\Teacher::first()->id;

        // Retrieve the teacher via show API
        $showResponse = $this->getJson("/api/v1/teachers/{$teacherId}");

        $showResponse->assertStatus(200)
                     ->assertJson([
                         'success' => true,
                         'data' => [
                             'user_id' => \App\Models\Teacher::first()->user_id,
                             'first_name' => 'John',
                             'last_name' => 'Doe',
                             'gender' => 'male',
                         ],
                     ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_404_if_teacher_does_not_exist(): void
    {
        $response = $this->getJson('/api/v1/teachers/99999'); // non-existent ID

        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'not_found',
                     'data' => null,
                 ]);
    }
}
