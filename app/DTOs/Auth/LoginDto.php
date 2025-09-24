<?php

namespace App\DTOs\Auth;

class LoginDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            email: strtolower($request->input('email')),
            password: $request->input('password'),
        );
    }
}
