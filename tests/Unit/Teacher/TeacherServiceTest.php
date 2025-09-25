<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\TeacherService;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherServiceTest extends TestCase
{
    use RefreshDatabase;

    private TeacherService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TeacherService();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_teacher_by_id(): void
    {
        $user = User::factory()->create();
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'gender' => 'male',
            'phone_number' => '0551122334',
            'second_phone_number' => '0551122335',
            'nationality' => 'Algerian',
            'date_of_birth' => '1990-01-01',
            'address' => 'Some Address',
            'id_card_image_url' => 'id_card.jpg',
            'cv_url' => 'cv.pdf',
            'teacher_image_url' => 'teacher.jpg',
            'primary_subject' => 'Math',
            'other_subjects' => 'Physics',
            'teaching_level' => 'High School',
            'years_of_experience' => 5,
            'ccp_number' => '12345',
            'ccp_key' => 'abcde',
            'ccp_account_name' => 'John Doe',
            'card_number' => '4111111111111111',
            'card_expiry' => '12/25',
            'card_cvv' => '123',
            'card_holder_name' => 'John Doe',
            'credit' => 0,
        ]);

        $result = $this->service->getOne($teacher->id);

        $this->assertNotNull($result);
        $this->assertEquals($teacher->id, $result['id']);
        $this->assertEquals($user->first_name ?? null, $result['first_name']);
        $this->assertEquals($user->last_name ?? null, $result['last_name']);
        $this->assertEquals($user->id, $result['user_id']); // optional check if needed
        $this->assertEquals($teacher->gender, $result['gender']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_null_if_teacher_not_found(): void
    {
        $result = $this->service->getOne(99999); // non-existent ID
        $this->assertNull($result);
    }
}
