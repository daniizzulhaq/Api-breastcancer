<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with('category')
            ->withCount('videos')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.materials.index', compact('materials'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.materials.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'duration_minutes' => 'nullable|integer|min:0',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'category_id', 'title', 'description', 'content', 
            'duration_minutes', 'difficulty', 'order'
        ]);
        
        $data['is_published'] = $request->has('is_published');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('materials', 'public');
        }

        Material::create($data);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material created successfully');
    }

    public function show(Material $material)
    {
        $material->load(['category', 'videos']);
        return view('admin.materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.materials.edit', compact('material', 'categories'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'duration_minutes' => 'nullable|integer|min:0',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'category_id', 'title', 'description', 'content', 
            'duration_minutes', 'difficulty', 'order'
        ]);
        
        $data['is_published'] = $request->has('is_published');

        if ($request->hasFile('thumbnail')) {
            if ($material->thumbnail) {
                Storage::disk('public')->delete($material->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('materials', 'public');
        }

        $material->update($data);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material updated successfully');
    }

    public function destroy(Material $material)
    {
        if ($material->thumbnail) {
            Storage::disk('public')->delete($material->thumbnail);
        }
        
        $material->delete();

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material deleted successfully');
    }
}