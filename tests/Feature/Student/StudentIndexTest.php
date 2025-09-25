<?php

namespace Tests\Feature\Student;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StudentIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local'); // fake storage for student images

        $role = Role::firstOrCreate(['name' => 'student']);

        // Student 1
        $user1 = User::factory()->create([
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

        // Student 2
        $user2 = User::factory()->create([
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
    }

    public function test_can_get_students_index_with_search_and_gender(): void
    {
        $response = $this->getJson('/api/v1/students?search=John&gender=male&page=1&per_page=10');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'data' => [
                             '*' => [
                                 'id',
                                 'first_name',
                                 'last_name',
                                 'email',
                                 'phone_number',
                                 'second_number',
                                 'parent_number',
                                 'gender',
                                 'student_image_url',
                                 'date_of_birth',
                                 'address',
                                 'year_of_study',
                             ]
                         ],
                         'pagination' => [
                             'total_pages',
                             'current_page',
                             'total_items',
                         ]
                     ]
                 ]);

        $this->assertEquals(1, $response->json('data.pagination.total_items'));
        $this->assertEquals('John', $response->json('data.data.0.first_name'));
        $this->assertEquals('male', $response->json('data.data.0.gender'));
    }

    public function test_can_get_all_students_without_filters(): void
    {
        $response = $this->getJson('/api/v1/students');

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('data.pagination.total_items'));
    }
}
