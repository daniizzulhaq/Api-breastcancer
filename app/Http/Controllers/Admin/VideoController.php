<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with(['material', 'material.category'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        $materials = Material::with('category')->where('is_published', true)->get();
        return view('admin.videos.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|string',
            'video_type' => 'required|in:youtube,vimeo,local',
            'thumbnail' => 'nullable|image|max:2048',
            'duration_seconds' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'material_id', 'title', 'description', 'video_url',
            'video_type', 'duration_seconds', 'order'
        ]);
        
        $data['is_published'] = $request->has('is_published');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('videos', 'public');
        }

        Video::create($data);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video created successfully');
    }

    public function show(Video $video)
    {
        $video->load(['material', 'material.category']);
        return view('admin.videos.show', compact('video'));
    }

    public function edit(Video $video)
    {
        $materials = Material::with('category')->where('is_published', true)->get();
        return view('admin.videos.edit', compact('video', 'materials'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|string',
            'video_type' => 'required|in:youtube,vimeo,local',
            'thumbnail' => 'nullable|image|max:2048',
            'duration_seconds' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'material_id', 'title', 'description', 'video_url',
            'video_type', 'duration_seconds', 'order'
        ]);
        
        $data['is_published'] = $request->has('is_published');

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('videos', 'public');
        }

        $video->update($data);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video updated successfully');
    }

    public function destroy(Video $video)
    {
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }
        
        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video deleted successfully');
    }
}