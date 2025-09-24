<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Services\TeacherService;
use App\DTOs\Teacher\TeacherIndexDTO;
use App\Http\Resources\TeacherResource;
use App\Http\Requests\Teacher\TeacherIndexRequest;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TeacherService $service , TeacherIndexRequest $request)
    {
        // get all teachers by the super admin
        // queries : search , page , per_page , sort_by , sort_dir
        try{

            $dto = TeacherIndexDTO::fromRequest($request);
            $paginatedTeachers = $service->getAll($dto);
            $data = formatPaginatedData($paginatedTeachers, TeacherResource::class);

        return response()->json([
                'success' => true,
                'message' => 'successfully',
                "data" => $data
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
