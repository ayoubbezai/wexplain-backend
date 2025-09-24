<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'student']);
        Role::firstOrCreate(['name' => 'teacher']);
        Role::firstOrCreate(['name' => 'super_admin']);
    }

    private function createUserWithRole(string $email, string $roleName): User
    {
        $role = Role::where('name', $roleName)->firstOrFail();
        return User::factory()->create([
            'email'    => $email,
            'password' => Hash::make('password123'),
            'role_id'  => $role->id,
        ]);
    }

    private function loginPayload(string $email, string $password = 'password123'): array
    {
        return compact('email', 'password');
    }

    public function test_user_login_successfully_with_roles(): void
    {
        foreach (['student', 'teacher', 'super_admin'] as $role) {
            $user = $this->createUserWithRole($role . '@example.com', $role);

            $response = $this->postJson('/api/v1/login', $this->loginPayload($user->email));

            $response->assertStatus(200)
                     ->assertJson([
                         'success' => true,
                         'role'    => $role,
                     ])
                     ->assertCookie('auth_token');
        }
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $this->createUserWithRole('test@example.com', 'student');

        $response = $this->postJson('/api/v1/login', $this->loginPayload('test@example.com', 'wrong-password'));

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'invalid_credentials',
                 ]);
    }

    public function test_user_cannot_login_with_non_existent_email(): void
    {
        $response = $this->postJson('/api/v1/login', $this->loginPayload('nonexistent@example.com'));

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'invalid_credentials',
                 ]);
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->postJson('/api/v1/login', []);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'errors'  => [
                         'email_required',
                         'password_required',
                     ],
                 ]);
    }
}
