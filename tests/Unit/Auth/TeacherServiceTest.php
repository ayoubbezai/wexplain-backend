<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Http\UploadedFile;
use App\DTOs\Auth\SignUpTeacherDTO;
use App\Services\TeacherService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_teacher_and_user()
    {
        Role::create(['name' => 'teacher']);

        Storage::fake('private');

        $dto = new SignUpTeacherDTO(
            first_name: 'Jane',
            last_name: 'Smith',
            email: 'jane@example.com',
            password: 'password123',
            gender: 'female',
            nationality: 'Algerian',
            phone_number: '0556677889',
            date_of_birth: '1992-05-10',
            second_phone_number: null,
            teacher_image: UploadedFile::fake()->image('teacher.png'),
            id_card_image: UploadedFile::fake()->image('id.png'),
            cv_pdf: UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf'),
            primary_subject: 'Physics',
            other_subjects: 'Math, Chemistry',
            teaching_level: 'Secondary',
            years_of_experience: 3,
            ccp_number: null,
            ccp_key: null,
            ccp_account_name: null,
            card_number: null,
            card_expiry: null,
            card_cvv: null,
            card_holder_name: null,
            address: 'Algiers'
        );

        $service = new TeacherService();
        $service->register($dto);

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    }
}
