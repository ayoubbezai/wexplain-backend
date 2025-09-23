<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SignUpTeacherTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_register_successfully(): void
    {
        Storage::fake('local'); // fakes file storage
        Role::create(['name' => 'teacher']);


        $response = $this->postJson('/api/v1/signup-teacher', [
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

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Teacher registered successfully',
            ]);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('local');
    $user = \App\Models\User::where('email', 'john@example.com')->firstOrFail();
    $teacher = \App\Models\Teacher::where('user_id', $user->id)->firstOrFail();

    // assert using the actual DB paths
    $disk->assertExists($teacher->cv_url);
    $disk->assertExists($teacher->id_card_image_url);
    $disk->assertExists($teacher->teacher_image_url);

    }

    public function test_teacher_registration_fails_with_missing_fields(): void
    {
                        Role::create(['name' => 'teacher']);

        $response = $this->postJson('/api/v1/signup-teacher', []);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }
}
