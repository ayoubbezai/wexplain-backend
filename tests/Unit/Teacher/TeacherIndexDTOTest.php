<?php
namespace Tests\Unit\Teacher;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\DTOs\Teacher\TeacherIndexDTO;

class TeacherIndexDTOTest extends TestCase
{
    public function test_from_request_maps_fields_correctly(): void
    {
        $request = new Request([
            'search'    => 'John Doe',
            'page'      => 2,
            'per_page'  => 15,
            'sort_by'   => 'created_at',
            'sort_dir'  => 'ASC',
            'gender'    => 'male',
        ]);

        $dto = TeacherIndexDTO::fromRequest($request);

        $this->assertEquals('John Doe', $dto->search);
        $this->assertEquals(2, $dto->page);
        $this->assertEquals(15, $dto->perPage);
        $this->assertEquals('created_at', $dto->sortBy);
        $this->assertEquals('asc', $dto->sortDir); // lowercase in fromRequest
        $this->assertEquals('male', $dto->gender);
    }

    public function test_from_request_uses_default_values(): void
    {
        $request = new Request([]);

        $dto = TeacherIndexDTO::fromRequest($request);

        $this->assertNull($dto->search);
        $this->assertEquals(1, $dto->page);
        $this->assertEquals(10, $dto->perPage);
        $this->assertEquals('created_at', $dto->sortBy);
        $this->assertEquals('desc', $dto->sortDir);
        $this->assertNull($dto->gender);
    }
}
