@extends('layouts.admin')

@section('title', 'Create Video')

@section('page-title', 'Create Video')

@section('page-description', 'Add new video content to learning materials')

@section('content')
<!-- Header with Back Button -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Create New Video</h1>
        <p class="text-gray-600 mt-1">Add engaging video content to enhance learning experience</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.videos.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i>Back to Videos
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Video Information
                </h2>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Material Selection -->
                    <div class="mb-6">
                        <label for="material_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Material <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('material_id') border-red-300 ring-red-500 @enderror" 
                                id="material_id" name="material_id" required>
                            <option value="">Select Material</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}" 
                                    {{ old('material_id', request('material_id')) == $material->id ? 'selected' : '' }}>
                                    [{{ $material->category->name }}] {{ $material->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('material_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Video Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-300 ring-red-500 @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Be specific and descriptive</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-300 ring-red-500 @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Video Type -->
                    <div class="mb-6">
                        <label for="video_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Video Type <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('video_type') border-red-300 ring-red-500 @enderror" 
                                id="video_type" name="video_type" required onchange="toggleVideoUrlHelp()">
                            <option value="">Select Type</option>
                            <option value="youtube" {{ old('video_type') == 'youtube' ? 'selected' : '' }}>
                                📺 YouTube
                            </option>
                            <option value="vimeo" {{ old('video_type') == 'vimeo' ? 'selected' : '' }}>
                                🎬 Vimeo
                            </option>
                            <option value="local" {{ old('video_type') == 'local' ? 'selected' : '' }}>
                                💾 Local File
                            </option>
                        </select>
                        @error('video_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Video URL -->
                    <div class="mb-6">
                        <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">
                            Video URL <span class="text-red-500">*</span>
                        </label>
                        <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('video_url') border-red-300 ring-red-500 @enderror" 
                               id="video_url" name="video_url" value="{{ old('video_url') }}" required>
                        @error('video_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 text-sm text-gray-500" id="url-help">
                            <div id="youtube-help" style="display: none;" class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                                <i class="fab fa-youtube text-red-500 mr-2"></i> 
                                Example: https://www.youtube.com/watch?v=VIDEO_ID
                            </div>
                            <div id="vimeo-help" style="display: none;" class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <i class="fab fa-vimeo text-blue-500 mr-2"></i> 
                                Example: https://vimeo.com/VIDEO_ID
                            </div>
                            <div id="local-help" style="display: none;" class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                <i class="fas fa-server text-green-500 mr-2"></i> 
                                Example: /storage/videos/video.mp4 or full URL
                            </div>
                        </div>
                    </div>

                    <!-- Thumbnail Upload -->
                    <div class="mb-6">
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Thumbnail Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a thumbnail</span>
                                        <input id="thumbnail" name="thumbnail" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                        @error('thumbnail')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from video</p>
                    </div>

                    <!-- Form Grid for smaller fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Duration -->
                        <div>
                            <label for="duration_seconds" class="block text-sm font-medium text-gray-700 mb-2">Duration (Seconds)</label>
                            <div class="relative">
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('duration_seconds') border-red-300 ring-red-500 @enderror" 
                                       id="duration_seconds" name="duration_seconds" value="{{ old('duration_seconds', 0) }}" min="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                            </div>
                            @error('duration_seconds')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Auto-detected for YouTube/Vimeo</p>
                        </div>

                        <!-- Order -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <div class="relative">
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('order') border-red-300 ring-red-500 @enderror" 
                                       id="order" name="order" value="{{ old('order', 1) }}" min="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-sort-numeric-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                        </div>
                    </div>

                    <!-- Published Status -->
                    <div class="mb-8">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_published" name="is_published" type="checkbox" 
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                       {{ old('is_published') ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_published" class="font-medium text-gray-700">Published</label>
                                <p class="text-gray-500">Only published videos will appear in API</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center shadow-lg">
                            <i class="fas fa-save mr-2"></i>Create Video
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Guidelines Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i>Video Guidelines
                </h2>
            </div>
            <div class="p-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h3 class="text-sm font-semibold text-blue-800 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>Best Practices
                    </h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            <span><strong>Title:</strong> Clear and specific</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            <span><strong>Description:</strong> Brief overview</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            <span><strong>Order:</strong> Logical sequence</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            <span><strong>Quality:</strong> HD resolution preferred</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-yellow-800 mb-2 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Video Types
                    </h3>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li class="flex items-start">
                            <i class="fab fa-youtube text-red-500 mr-2 mt-0.5 text-xs"></i>
                            <span><strong>YouTube:</strong> Free, good for public content</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fab fa-vimeo text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            <span><strong>Vimeo:</strong> Better quality, privacy options</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-server text-green-500 mr-2 mt-0.5 text-xs"></i>
                            <span><strong>Local:</strong> Full control, requires storage</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-eye mr-2"></i>Preview
                </h2>
            </div>
            <div class="p-6">
                <div id="video-preview" class="text-center text-gray-500">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-video text-gray-300 text-2xl"></i>
                    </div>
                    <p class="text-sm">Video preview will appear here when you enter a valid URL</p>
                </div>
            </div>
        </div>

        <!-- Quick Tips Card -->
        <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg border border-green-200 p-6">
            <div class="flex items-center mb-3">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-rocket text-green-600"></i>
                </div>
                <h3 class="ml-3 text-sm font-medium text-gray-900">Pro Tips</h3>
            </div>
            <ul class="text-sm text-gray-600 space-y-2">
                <li class="flex items-start">
                    <i class="fas fa-star text-yellow-500 mr-2 mt-0.5 text-xs"></i>
                    Test video links before saving
                </li>
                <li class="flex items-start">
                    <i class="fas fa-star text-yellow-500 mr-2 mt-0.5 text-xs"></i>
                    Add engaging thumbnails
                </li>
                <li class="flex items-start">
                    <i class="fas fa-star text-yellow-500 mr-2 mt-0.5 text-xs"></i>
                    Keep videos under 10 minutes
                </li>
                <li class="flex items-start">
                    <i class="fas fa-star text-yellow-500 mr-2 mt-0.5 text-xs"></i>
                    Use descriptive titles
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleVideoUrlHelp() {
    const videoType = document.getElementById('video_type').value;
    
    // Hide all help texts
    document.getElementById('youtube-help').style.display = 'none';
    document.getElementById('vimeo-help').style.display = 'none';
    document.getElementById('local-help').style.display = 'none';
    
    // Show relevant help
    if (videoType) {
        document.getElementById(videoType + '-help').style.display = 'block';
    }
}

// URL validation and preview
document.getElementById('video_url').addEventListener('input', function(e) {
    const url = e.target.value;
    const videoType = document.getElementById('video_type').value;
    const preview = document.getElementById('video-preview');
    
    if (url && videoType === 'youtube') {
        const videoId = extractYouTubeId(url);
        if (videoId) {
            preview.innerHTML = `
                <div class="aspect-w-16 aspect-h-9">
                    <iframe src="https://www.youtube.com/embed/${videoId}" 
                            class="w-full h-48 rounded-lg" 
                            frameborder="0" allowfullscreen></iframe>
                </div>
            `;
        }
    } else if (url && videoType === 'vimeo') {
        const videoId = extractVimeoId(url);
        if (videoId) {
            preview.innerHTML = `
                <div class="aspect-w-16 aspect-h-9">
                    <iframe src="https://player.vimeo.com/video/${videoId}" 
                            class="w-full h-48 rounded-lg" 
                            frameborder="0" allowfullscreen></iframe>
                </div>
            `;
        }
    } else if (!url) {
        preview.innerHTML = `
            <div class="text-center text-gray-500">
                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-video text-gray-300 text-2xl"></i>
                </div>
                <p class="text-sm">Video preview will appear here when you enter a valid URL</p>
            </div>
        `;
    }
});

function extractYouTubeId(url) {
    const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

function extractVimeoId(url) {
    const regex = /vimeo\.com\/(\d+)/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

// File upload preview
document.getElementById('thumbnail').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create preview if doesn't exist
            let preview = document.getElementById('thumbnail-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'thumbnail-preview';
                preview.className = 'mt-4 text-center';
                e.target.closest('.mb-6').appendChild(preview);
            }
            preview.innerHTML = `
                <div class="relative inline-block">
                    <img src="${e.target.result}" alt="Thumbnail preview" class="w-32 h-24 object-cover rounded-lg border border-gray-200 shadow-sm">
                    <div class="absolute top-1 right-1 bg-green-100 text-green-800 rounded-full p-1">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Thumbnail Preview</p>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }
});

// Duration formatter
document.getElementById('duration_seconds').addEventListener('input', function() {
    const seconds = parseInt(this.value) || 0;
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const remainingSeconds = seconds % 60;
    
    let formatted = '';
    if (hours > 0) {
        formatted = `${hours}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    } else {
        formatted = `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
    
    // Could show this in a helper text or tooltip
    console.log(`Duration: ${formatted} (${seconds} seconds)`);
});

// Form submission loading state
document.querySelector('form').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
    submitBtn.disabled = true;
});
</script>
@endpush