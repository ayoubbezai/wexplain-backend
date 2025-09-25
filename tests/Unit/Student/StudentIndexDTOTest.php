<?php

namespace Tests\Unit\Student;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\DTOs\Student\StudentIndexDTO;

class StudentIndexDTOTest extends TestCase
{
    public function test_from_request_maps_fields_correctly(): void
    {
        $request = new Request([
            'search'    => 'Jane Doe',
            'page'      => 3,
            'per_page'  => 20,
            'sort_by'   => 'first_name',
            'sort_dir'  => 'ASC',
            'gender'    => 'female',
        ]);

        $dto = StudentIndexDTO::fromRequest($request);

        $this->assertEquals('Jane Doe', $dto->search);
        $this->assertEquals(3, $dto->page);
        $this->assertEquals(20, $dto->perPage);
        $this->assertEquals('first_name', $dto->sortBy);
        $this->assertEquals('asc', $dto->sortDir); // lowercase in fromRequest
        $this->assertEquals('female', $dto->gender);
    }

    public function test_from_request_uses_default_values(): void
    {
        $request = new Request([]);

        $dto = StudentIndexDTO::fromRequest($request);

        $this->assertNull($dto->search);
        $this->assertEquals(1, $dto->page);
        $this->assertEquals(10, $dto->perPage);
        $this->assertEquals('created_at', $dto->sortBy);
        $this->assertEquals('desc', $dto->sortDir);
        $this->assertNull($dto->gender);
    }
}
