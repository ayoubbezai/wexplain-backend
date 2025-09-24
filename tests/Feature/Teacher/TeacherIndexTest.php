<?php

namespace Tests\Feature\Teacher;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'teacher']);

        $user1 = User::factory()->create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'role_id'    => $role->id,
            'email'      => 'john@example.com',
            'password'   => bcrypt('password123'),
        ]);

        Teacher::create([
            'user_id' => $user1->id,
            'gender' => 'male',
            'phone_number' => '1234567890',
            'second_phone_number' => '0987654321',
            'primary_subject' => 'Math',
            'teaching_level' => 'High School',
            'years_of_experience' => 5,
            'credit' => 0,
            'date_of_birth' => '1990-01-01',
            'teacher_image_url' => '',
            'id_card_image_url' => '',
            'cv_url' => '',
            'address' => '',
            'nationality' => 'USA',
        ]);

        $user2 = User::factory()->create([
            'first_name' => 'Jane',
            'last_name'  => 'Smith',
            'role_id'    => $role->id,
            'email'      => 'jane@example.com',
            'password'   => bcrypt('password123'),
        ]);

        Teacher::create([
            'user_id' => $user2->id,
            'gender' => 'female',
            'phone_number' => '5555555555',
            'second_phone_number' => '4444444444',
            'primary_subject' => 'Science',
            'teaching_level' => 'Middle School',
            'years_of_experience' => 3,
            'credit' => 0,
            'date_of_birth' => '1985-05-10',
            'teacher_image_url' => '',
            'id_card_image_url' => '',
            'cv_url' => '',
            'address' => '',
            'nationality' => 'USA',
        ]);
    }

   public function test_can_get_teachers_index_with_search_and_gender(): void
{
    $response = $this->getJson('/api/v1/teachers?search=John&gender=male&page=1&per_page=10');

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
                             'second_phone_number',
                             'gender',
                             'primary_subject',
                             'teaching_level',
                             'years_of_experience',
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

public function test_can_get_all_teachers_without_filters(): void
{
    $response = $this->getJson('/api/v1/teachers');

    $response->assertStatus(200);
    $this->assertEquals(2, $response->json('data.pagination.total_items'));
}

}
