<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\DTOs\Auth\LoginDto;
use Illuminate\Http\Request;

class LoginDtoTest extends TestCase
{
    public function test_from_request_maps_fields_correctly(): void
    {
        $request = new Request([
            'email' => 'USER@EXAMPLE.COM',
            'password' => 'secretPassword',
        ]);

        $dto = LoginDto::fromRequest($request);

        $this->assertEquals('user@example.com', $dto->email); // email lowercased
        $this->assertEquals('secretPassword', $dto->password);
    }

    public function test_from_request_with_missing_fields_throws_error(): void
    {
        $this->expectException(\TypeError::class);

        // Missing email or password will throw TypeError because constructor requires them
        $request = new Request([]);
        LoginDto::fromRequest($request);
    }
}
