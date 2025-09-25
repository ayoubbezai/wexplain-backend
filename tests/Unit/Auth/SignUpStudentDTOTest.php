<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\DTOs\Auth\SignUpStudentDTO;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class SignUpStudentDTOTest extends TestCase
{
    public function test_from_request_maps_fields_correctly()
    {
        $request = new Request([
            'first_name'      => 'John',
            'last_name'       => 'Doe',
            'email'           => 'JOHN.DOE@EXAMPLE.COM',
            'password'        => 'password123',
            'gender'          => 'male',
            'phone_number'    => '1234567890',
            'second_number'   => '', // should map to null
            'parent_number'   => '', // should map to null
            'date_of_birth'   => '2005-08-15',
            'address'         => null,
            'year_of_study'   => '10th Grade',
        ]);

        // Attach the fake uploaded file
        $request->files->set('student_image', UploadedFile::fake()->image('student.jpg'));

        $dto = SignUpStudentDTO::fromRequest($request);

        $this->assertEquals('John', $dto->first_name);
        $this->assertEquals('Doe', $dto->last_name);
        $this->assertEquals('john.doe@example.com', $dto->email); // sanitized
        $this->assertEquals('password123', $dto->password);
        $this->assertEquals('male', $dto->gender);
        $this->assertEquals('1234567890', $dto->phone_number);
        $this->assertNull($dto->second_number); // empty string -> null
        $this->assertNull($dto->parent_number); // empty string -> null
        $this->assertEquals('2005-08-15', $dto->date_of_birth);
        $this->assertNull($dto->address);
        $this->assertEquals('10th Grade', $dto->year_of_study);
        $this->assertNotNull($dto->student_image); // file exists
    }
}
