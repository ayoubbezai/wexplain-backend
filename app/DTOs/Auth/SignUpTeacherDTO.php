<?php

namespace App\DTOs\Auth;

use Illuminate\Http\UploadedFile;

class SignUpTeacherDTO
{
    public function __construct(
        // User fields
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly string $password,

        // Teacher fields
        public readonly string $gender,
        public readonly string $phone_number,
        public readonly ?string $second_phone_number,
        public readonly string $nationality,
        public readonly string $date_of_birth,
        public readonly ?string $address,

        // File uploads (not stored yet, just passed)
        public readonly UploadedFile $teacher_image,
        public readonly UploadedFile $id_card_image,
        public readonly UploadedFile $cv_pdf,

        // Teaching info
        public readonly string $primary_subject,
        public readonly ?string $other_subjects,
        public readonly string $teaching_level,
        public readonly int $years_of_experience,

        // Optional financial info
        public readonly ?string $ccp_number,
        public readonly ?string $ccp_key,
        public readonly ?string $ccp_account_name,
        public readonly ?string $card_number,
        public readonly ?string $card_expiry,
        public readonly ?string $card_cvv,
        public readonly ?string $card_holder_name,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            // User
            first_name: strip_tags($request->input('first_name')),
            last_name: strip_tags($request->input('last_name')),
            email: strtolower($request->input('email')),
            password: $request->input('password'),

            // Teacher
            gender: $request->input('gender'),
            phone_number: $request->input('phone_number'),
            second_phone_number: $request->filled('second_phone_number') ? $request->input('second_phone_number') : null,
            nationality: $request->input('nationality'),
            date_of_birth: $request->input('date_of_birth'),
            address: $request->filled('address') ? $request->input('address') : null,

            // Files
            teacher_image: $request->file('teacher_image'),
            id_card_image: $request->file('id_card_image'),
            cv_pdf: $request->file('cv_pdf'),

            // Teaching info
            primary_subject: $request->input('primary_subject'),
            other_subjects: $request->filled('other_subjects') ? $request->input('other_subjects') : null,
            teaching_level: $request->input('teaching_level'),
            years_of_experience: (int) $request->input('years_of_experience'),

            // Optional financial info
            ccp_number: $request->filled('ccp_number') ? $request->input('ccp_number') : null,
            ccp_key: $request->filled('ccp_key') ? $request->input('ccp_key') : null,
            ccp_account_name: $request->filled('ccp_account_name') ? $request->input('ccp_account_name') : null,
            card_number: $request->filled('card_number') ? $request->input('card_number') : null,
            card_expiry: $request->filled('card_expiry') ? $request->input('card_expiry') : null,
            card_cvv: $request->filled('card_cvv') ? $request->input('card_cvv') : null,
            card_holder_name: $request->filled('card_holder_name') ? $request->input('card_holder_name') : null,
        );
    }
}
