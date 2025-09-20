@extends('layouts.admin')

@section('title', 'Edit Video')

@section('page-title', 'Edit Video')

@section('page-description', 'Update video content and settings')

@section('content')
<!-- Header with Action Buttons -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Video: {{ $video->title }}</h1>
        <p class="text-gray-600 mt-1">Update video content and enhance learning experience</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ $video->video_url }}" target="_blank" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center shadow-lg">
            <i class="fas fa-play mr-2"></i>Watch Video
        </a>
        <a href="{{ route('admin.videos.show', $video) }}" 
           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center shadow-lg">
            <i class="fas fa-eye mr-2"></i>View Details
        </a>
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
                    <i class="fas fa-edit mr-2"></i>
                    Video Information
                </h2>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('admin.videos.update', $video) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
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
                                    {{ old('material_id', $video->material_id) == $material->id ? 'selected' : '' }}>
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
                               id="title" name="title" value="{{ old('title', $video->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Current slug: <code class="bg-gray-100 px-2 py-1 rounded">{{ $video->slug }}</code></p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-300 ring-red-500 @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $video->description) }}</textarea>
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
                            <option value="youtube" {{ old('video_type', $video->video_type) == 'youtube' ? 'selected' : '' }}>
                                📺 YouTube
                            </option>
                            <option value="vimeo" {{ old('video_type', $video->video_type) == 'vimeo' ? 'selected' : '' }}>
                                🎬 Vimeo
                            </option>
                            <option value="local" {{ old('video_type', $video->video_type) == 'local' ? 'selected' : '' }}>
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
                               id="video_url" name="video_url" value="{{ old('video_url', $video->video_url) }}" required>
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

                    <!-- Current Thumbnail -->
                    @if($video->thumbnail)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Thumbnail</label>
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/' . $video->thumbnail) }}" 
                                 alt="Current thumbnail" class="w-48 h-32 object-cover rounded-lg border border-gray-200 shadow-sm">
                            <div class="absolute top-2 right-2 bg-green-100 text-green-800 rounded-full px-2 py-1">
                                <i class="fas fa-check text-xs mr-1"></i>
                                <span class="text-xs font-medium">Current</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- New Thumbnail Upload -->
                    <div class="mb-6">
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $video->thumbnail ? 'Replace Thumbnail' : 'Add Thumbnail' }}
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>{{ $video->thumbnail ? 'Upload new thumbnail' : 'Upload a thumbnail' }}</span>
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
                        <p class="mt-1 text-sm text-gray-500">{{ $video->thumbnail ? 'Leave empty to keep current thumbnail' : 'Leave empty to auto-generate from video' }}</p>
                    </div>

                    <!-- Form Grid for smaller fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Duration -->
                        <div>
                            <label for="duration_seconds" class="block text-sm font-medium text-gray-700 mb-2">Duration (Seconds)</label>
                            <div class="relative">
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('duration_seconds') border-red-300 ring-red-500 @enderror" 
                                       id="duration_seconds" name="duration_seconds" value="{{ old('duration_seconds', $video->duration_seconds) }}" min="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                            </div>
                            @error('duration_seconds')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Current: {{ gmdate('H:i:s', $video->duration_seconds) }} ({{ $video->duration_seconds }} seconds)
                            </p>
                        </div>

                        <!-- Order -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <div class="relative">
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('order') border-red-300 ring-red-500 @enderror" 
                                       id="order" name="order" value="{{ old('order', $video->order) }}" min="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-sort-numeric-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Order within the material (0 for automatic ordering)</p>
                        </div>
                    </div>

                    <!-- Status and Options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Status -->
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('is_active') border-red-300 ring-red-500 @enderror" 
                                    id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', $video->is_active) == '1' ? 'selected' : '' }}>
                                    ✅ Active
                                </option>
                                <option value="0" {{ old('is_active', $video->is_active) == '0' ? 'selected' : '' }}>
                                    ❌ Inactive
                                </option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Free Preview -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Free Preview</label>
                            <div class="flex items-start pt-2">
                                <div class="flex items-center h-5">
                                    <input id="is_free_preview" name="is_free_preview" type="checkbox" value="1"
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded @error('is_free_preview') border-red-300 ring-red-500 @enderror"
                                           {{ old('is_free_preview', $video->is_free_preview) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_free_preview" class="font-medium text-gray-700">Allow Free Preview</label>
                                    <p class="text-gray-500">Non-premium users can watch this video</p>
                                </div>
                            </div>
                            @error('is_free_preview')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.videos.show', $video) }}" 
                           class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 flex items-center">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center shadow-lg">
                            <i class="fas fa-save mr-2"></i>Update Video
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Video Information -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>Video Information
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="font-medium">{{ $video->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="text-sm text-gray-500">Last Updated</p>
                        <p class="font-medium">{{ $video->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500">Material</p>
                    <a href="{{ route('admin.materials.show', $video->material) }}" 
                       class="font-medium text-blue-600 hover:text-blue-500 block">
                        {{ $video->material->title }}
                    </a>
                    <p class="text-sm text-gray-500">{{ $video->material->category->name }}</p>
                </div>
                
                <div class="border-t pt-4 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Views</p>
                        <p class="font-medium text-lg">{{ number_format($video->views_count ?? 0) }}</p>
                    </div>
                    @if($video->duration_seconds)
                    <div>
                        <p class="text-sm text-gray-500">Duration</p>
                        <p class="font-medium text-lg">{{ gmdate('H:i:s', $video->duration_seconds) }}</p>
                    </div>
                    @endif
                </div>

                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500 mb-2">Status</p>
                    <div class="flex flex-wrap gap-2">
                        @if($video->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times mr-1"></i>Inactive
                            </span>
                        @endif
                        
                        @if($video->is_free_preview)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-unlock mr-1"></i>Free Preview
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Preview -->
        @if($video->video_type === 'youtube' || $video->video_type === 'vimeo')
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-play mr-2"></i>Video Preview
                </h2>
            </div>
            <div class="p-0">
                <div class="aspect-w-16 aspect-h-9">
                    @if($video->video_type === 'youtube')
                        @php
                            $videoId = '';
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $video->video_url, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp
                        @if($videoId)
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                                    class="w-full h-64 rounded-b-lg" 
                                    frameborder="0" allowfullscreen></iframe>
                        @endif
                    @elseif($video->video_type === 'vimeo')
                        @php
                            $videoId = '';
                            if (preg_match('/vimeo\.com\/(\d+)/', $video->video_url, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp
                        @if($videoId)
                            <iframe src="https://player.vimeo.com/video/{{ $videoId }}" 
                                    class="w-full h-64 rounded-b-lg"
                                    frameborder="0" allowfullscreen></iframe>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions Card -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-6">
            <div class="flex items-center mb-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bolt text-blue-600"></i>
                </div>
                <h3 class="ml-3 text-sm font-medium text-gray-900">Quick Actions</h3>
            </div>
            <div class="space-y-2">
                <a href="{{ $video->video_url }}" target="_blank" 
                   class="flex items-center text-sm text-blue-600 hover:text-blue-500 transition-colors">
                    <i class="fas fa-external-link-alt mr-2 text-xs"></i>
                    Open original video
                </a>
                <a href="{{ route('admin.videos.show', $video) }}" 
                   class="flex items-center text-sm text-blue-600 hover:text-blue-500 transition-colors">
                    <i class="fas fa-eye mr-2 text-xs"></i>
                    View full details
                </a>
                <a href="{{ route('admin.materials.show', $video->material) }}" 
                   class="flex items-center text-sm text-blue-600 hover:text-blue-500 transition-colors">
                    <i class="fas fa-book mr-2 text-xs"></i>
                    View parent material
                </a>
            </div>
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleVideoUrlHelp();
    
    // Auto-format duration display
    const durationInput = document.getElementById('duration_seconds');
    if (durationInput) {
        durationInput.addEventListener('input', function() {
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
            
            console.log(`Duration: ${formatted} (${seconds} seconds)`);
        });
    }
});

// Thumbnail preview
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
                    <img src="${e.target.result}" alt="New thumbnail preview" class="w-48 h-32 object-cover rounded-lg border border-gray-200 shadow-sm">
                    <div class="absolute top-2 right-2 bg-blue-100 text-blue-800 rounded-full px-2 py-1">
                        <i class="fas fa-upload text-xs mr-1"></i>
                        <span class="text-xs font-medium">New</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">New Thumbnail Preview</p>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }
});

// URL validation and preview update
document.getElementById('video_url').addEventListener('input', function(e) {
    const url = e.target.value;
    const videoType = document.getElementById('video_type').value;
    
    // Could add real-time preview updates here similar to create page
    console.log('Video URL updated:', url);
});

// Form submission loading state
document.querySelector('form').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    submitBtn.disabled = true;
});
</script>
@endpush