<?php

namespace App\DTOs\Auth;

class SignUpStudentDTO
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $phone_number,
        public readonly ?string $second_number,
        public readonly ?string $parent_number,
        public readonly string $date_of_birth,
        public readonly ?string $address,
        public readonly string $year_of_study,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            first_name: strip_tags($request->input('first_name')),
            last_name: strip_tags($request->input('last_name')),
            email: strtolower($request->input('email')),
            password: $request->input('password'),
            phone_number: $request->input('phone_number'),
            second_number: $request->filled('second_number') ? $request->input('second_number') : null,
            parent_number: $request->filled('parent_number') ? $request->input('parent_number') : null,
            date_of_birth: $request->input('date_of_birth'),
            address: $request->filled('address') ? $request->input('address') : null,
            year_of_study: $request->input('year_of_study'),
        );
    }
}
