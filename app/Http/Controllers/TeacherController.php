<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\TeacherService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\DTOs\Teacher\TeacherIndexDTO;
use App\Http\Resources\TeacherResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Teacher\TeacherIndexRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TeacherService $service, TeacherIndexRequest $request)
    {
        try {
            $dto = TeacherIndexDTO::fromRequest($request);
            $paginatedTeachers = $service->getAll($dto);
            $data = formatPaginatedData($paginatedTeachers, TeacherResource::class);

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
     * Display the specified teacher.
     */
    public function show(TeacherService $service, string $id)
    {
        try {
            $teacher = $service->getOne((int) $id);

            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'not_found',
                    'data'    => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'successfully',
                'data'    => $teacher,
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

public function file(Request $request, int $teacherId, string $fileType)
{
    // 1. Authorization Check (Highly Recommended for private files)
    // You should ensure the currently authenticated user is allowed to view this teacher's file.
    // e.g., if (!Auth::user()->is_admin() && Auth::id() !== $teacherId) { abort(403); }

    // Find teacher
    $teacher = Teacher::findOrFail($teacherId);

    // Map file types to database columns (assuming these columns hold paths relative to 'storage/app/private')
    $filePath = match($fileType) {
        'cv'      => $teacher->cv_url,
        'id_card' => $teacher->id_card_image_url,
        'image'   => $teacher->teacher_image_url,
        default   => null,
    };

    // 2. Path Validation
    if (!$filePath) {
        abort(404, 'File type not found');
    }



    $diskName = 'local';

    if (!Storage::disk($diskName)->exists($filePath)) {
        // Log the attempt for debugging purposes
        Log::warning("Private file not found on disk '{$diskName}': {$filePath}");
        abort(404, 'File not found in private storage.');
    }


    return response()->file(Storage::disk('local')->path($filePath));
}
}
