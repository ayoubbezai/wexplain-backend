<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SignUpStudentTest extends TestCase
{
    use RefreshDatabase; //rest db after test
    // to make sure student role exict



    public function test_student_can_sign_up()
    {
        Role::create(['name' => 'student']);

                $payload = [
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'email'                 => 'john.doe@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'phone_number'          => '1234567890',
            'second_number'         => '0987654321',
            'parent_number'         => '1122334455',
            'date_of_birth'         => '2005-08-15',
            'address'               => '123 Main Street',
            'year_of_study'         => '10th Grade'
        ];

        $response = $this->postJson('/api/v1/signup-student',$payload);

      $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Student registered successfully'
                 ]);

              // Assert user exists in DB
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com'
        ]);



        // Assert cookie is set
        $response->assertCookie('auth_token');



}
 public function test_email_must_be_unique()
    {
        Role::create(['name' => 'student']);

        // Create first user
        $this->postJson('/api/v1/signup-student', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone_number' => '1234567890',
            'parent_number' => '1122334455',
            'date_of_birth' => '2005-08-15',
            'year_of_study' => '10th Grade'
        ]);

        // Try signing up with the same email again
        $response = $this->postJson('/api/v1/signup-student', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone_number' => '0987654321',
            'parent_number' => '6677889900',
            'date_of_birth' => '2006-02-20',
            'year_of_study' => '9th Grade'
        ]);

        $response->assertStatus(422)
                 ->assertJsonFragment(['email_unique']);
    }
}
