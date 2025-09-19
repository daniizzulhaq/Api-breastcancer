<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MaterialController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Material::where('is_published', true)
                ->with(['category', 'videos']);

            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('difficulty')) {
                $query->where('difficulty', $request->difficulty);
            }

            $materials = $query->orderBy('order')->paginate(10);

            return response()->json([
                'status' => 'success',
                'message' => 'Materials retrieved successfully',
                'data' => $materials->items(),
                'pagination' => [
                    'current_page' => $materials->currentPage(),
                    'last_page' => $materials->lastPage(),
                    'per_page' => $materials->perPage(),
                    'total' => $materials->total()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve materials',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($slug): JsonResponse
    {
        try {
            $material = Material::where('slug', $slug)
                ->where('is_published', true)
                ->with(['category', 'videos' => function($query) {
                    $query->where('is_published', true)->orderBy('order');
                }])
                ->first();

            if (!$material) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Material not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Material retrieved successfully',
                'data' => $material
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve material',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}