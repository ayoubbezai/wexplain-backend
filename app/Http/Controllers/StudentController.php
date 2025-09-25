<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Services\StudentService;
use App\DTOs\Student\StudentIndexDTO;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Student\StudentIndexRequest;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(StudentService $service, StudentIndexRequest $request)
    {
        try {
            $dto = StudentIndexDTO::fromRequest($request);
            $paginatedStudents = $service->getAll($dto);
            $data = formatPaginatedData($paginatedStudents, StudentResource::class);

            return response()->json([
                'success' => true,
                'message' => 'successfully',
                "data"    => $data
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


public function file(int $studentId)
{
    // Find student
    $student = Student::findOrFail($studentId);

    // Get file path from database
    $filePath = $student->student_image_url;

    // Validate path
    if (!$filePath || !Storage::disk('local')->exists($filePath)) {
        abort(404, 'File not found in storage.');
    }

    // Return the file as a response
    return response()->file(Storage::disk('local')->path($filePath));
}
}
