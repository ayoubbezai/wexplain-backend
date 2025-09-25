<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\DTOs\Student\StudentIndexDTO;
use App\Services\StudentService;
use App\Http\Resources\StudentResource;
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
}
