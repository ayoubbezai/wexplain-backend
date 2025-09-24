<?php

namespace App\DTOs\Teacher;

class TeacherIndexDTO
{
    public function __construct(
        public readonly ?string $search,
        public readonly int $page,
        public readonly int $perPage,
        public readonly string $sortBy,
        public readonly string $sortDir,
        public readonly ?string $gender,
    ) {}

    public static function fromRequest($request): self
    {
        $sortDir = strtolower($request->input('sort_dir', 'desc'));
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $gender = strtolower($request->input('gender'));
        if (!in_array($gender, ['male', 'female'])) {
            $gender = null; // ignore invalid gender
        }

        return new self(
            search: $request->input('search'),
            page: $request->input('page', 1),
            perPage: $request->input('per_page', 10),
            sortBy: $request->input('sort_by', 'created_at'),
            sortDir: $sortDir,
            gender: $gender
        );
    }
}
