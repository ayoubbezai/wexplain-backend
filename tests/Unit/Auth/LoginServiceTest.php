<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Services\Auth\LoginService;
use App\DTOs\Auth\LoginDto;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginServiceTest extends TestCase
{
    use RefreshDatabase;

    private LoginService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LoginService();

        // Ensure roles exist
        Role::firstOrCreate(['name' => 'student']);
        Role::firstOrCreate(['name' => 'teacher']);
        Role::firstOrCreate(['name' => 'super_admin']);
    }

    private function createUserWithRole(string $email, string $roleName, string $password = 'password123'): User
    {
        $role = Role::where('name', $roleName)->firstOrFail();
        return User::factory()->create([
            'email'    => $email,
            'password' => Hash::make($password),
            'role_id'  => $role->id,
        ]);
    }

    public function test_login_returns_token_and_role_for_valid_credentials(): void
    {
        $user = $this->createUserWithRole('student@example.com', 'student');

        $dto = new LoginDto(email: $user->email, password: 'password123');

        $result = $this->service->login($dto);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('cookie', $result);
        $this->assertArrayHasKey('role', $result);
        $this->assertEquals('student', $result['role']);
    }

    public function test_login_returns_null_for_invalid_password(): void
    {
        $user = $this->createUserWithRole('student@example.com', 'student');

        $dto = new LoginDto(email: $user->email, password: 'wrong-password');

        $result = $this->service->login($dto);

        $this->assertNull($result);
    }

    public function test_login_returns_null_for_non_existent_user(): void
    {
        $dto = new LoginDto(email: 'nonexistent@example.com', password: 'password123');

        $result = $this->service->login($dto);

        $this->assertNull($result);
    }


}
