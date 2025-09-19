<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VideoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Video::where('is_published', true)
                ->with(['material', 'material.category']);

            if ($request->has('material_id')) {
                $query->where('material_id', $request->material_id);
            }

            $videos = $query->orderBy('order')->paginate(10);

            return response()->json([
                'status' => 'success',
                'message' => 'Videos retrieved successfully',
                'data' => $videos->items(),
                'pagination' => [
                    'current_page' => $videos->currentPage(),
                    'last_page' => $videos->lastPage(),
                    'per_page' => $videos->perPage(),
                    'total' => $videos->total()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve videos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($slug): JsonResponse
    {
        try {
            $video = Video::where('slug', $slug)
                ->where('is_published', true)
                ->with(['material', 'material.category'])
                ->first();

            if (!$video) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Video not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Video retrieved successfully',
                'data' => $video
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve video',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}