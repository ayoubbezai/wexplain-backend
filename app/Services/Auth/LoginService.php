<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class LoginService
{
    /**
     * Attempt login and return token cookie and token string.
     */
    public function login(LoginDto $dto): ?array
    {
        $user = User::where('email', $dto->email)->with('role')->first();

        if ($user && Hash::check($dto->password, $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;


            $cookie = Cookie::make(
                'auth_token', // cookie name
                $token,       // token value
                60 * 24,      // 1 day in minutes
                '/',          // path
                null,         // domain
                false,        // secure, set true in production
                true          // httpOnly
            );

            // Return both cookie and token
            return [
                'token'  => $token,
                'cookie' => $cookie,
                'user'   => $user
            ];
        }

        return null; // Invalid credentials
    }
}
