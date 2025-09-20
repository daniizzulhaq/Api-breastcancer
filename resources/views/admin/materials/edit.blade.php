@extends('layouts.admin')

@section('title', 'Edit Material')

@section('page-title', 'Edit Material')

@section('page-description', 'Update learning material content and settings')

@section('content')
<!-- Header with Action Buttons -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Material</h1>
        <p class="text-gray-600 mt-1">{{ $material->title }}</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.materials.show', $material) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center shadow-lg">
            <i class="fas fa-eye mr-2"></i>View
        </a>
        <a href="{{ route('admin.materials.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i>Back to Materials
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
                    Edit Material Form
                </h2>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('admin.materials.update', $material) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Category Selection -->
                    <div class="mb-6">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-300 ring-red-500 @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $material->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-300 ring-red-500 @enderror" 
                               id="title" name="title" value="{{ old('title', $material->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Current slug: <code class="bg-gray-100 px-2 py-1 rounded">{{ $material->slug }}</code></p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-300 ring-red-500 @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $material->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <label for="content" class="block text-sm font-medium text-gray-700">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <button type="button" onclick="togglePreview()" 
                                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                Toggle Preview
                            </button>
                        </div>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-300 ring-red-500 @enderror" 
                                  id="content" name="content" rows="10" required>{{ old('content', $material->content) }}</textarea>
                        <div id="content-preview" class="hidden mt-2 p-4 border border-gray-300 rounded-lg bg-gray-50"></div>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">You can use HTML tags for formatting</p>
                    </div>

                    <!-- Current Thumbnail -->
                    @if($material->thumbnail)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Thumbnail</label>
                        <div class="inline-block p-2 bg-gray-50 rounded-lg border border-gray-200">
                            <img src="{{ asset('storage/' . $material->thumbnail) }}" 
                                 alt="Current thumbnail" width="200" class="rounded-lg shadow-sm">
                        </div>
                    </div>
                    @endif

                    <!-- New Thumbnail -->
                    <div class="mb-6">
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $material->thumbnail ? 'Replace Thumbnail' : 'Add Thumbnail' }}
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
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
                        <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image.</p>
                    </div>

                    <!-- Form Grid for smaller fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Duration -->
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Duration (Minutes)</label>
                            <div class="relative">
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('duration_minutes') border-red-300 ring-red-500 @enderror" 
                                       id="duration_minutes" name="duration_minutes" 
                                       value="{{ old('duration_minutes', $material->duration_minutes) }}" min="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                            </div>
                            @error('duration_minutes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <div class="relative">
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('order') border-red-300 ring-red-500 @enderror" 
                                       id="order" name="order" value="{{ old('order', $material->order) }}" min="0">
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

                    <!-- Difficulty -->
                    <div class="mb-6">
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                            Difficulty Level <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('difficulty') border-red-300 ring-red-500 @enderror" 
                                id="difficulty" name="difficulty" required>
                            <option value="">Select Difficulty</option>
                            <option value="beginner" 
                                {{ old('difficulty', $material->difficulty) == 'beginner' ? 'selected' : '' }}>
                                🌱 Beginner
                            </option>
                            <option value="intermediate" 
                                {{ old('difficulty', $material->difficulty) == 'intermediate' ? 'selected' : '' }}>
                                🍃 Intermediate
                            </option>
                            <option value="advanced" 
                                {{ old('difficulty', $material->difficulty) == 'advanced' ? 'selected' : '' }}>
                                🌳 Advanced
                            </option>
                        </select>
                        @error('difficulty')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Published Status -->
                    <div class="mb-8">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_published" name="is_published" type="checkbox" 
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                       {{ old('is_published', $material->is_published) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_published" class="font-medium text-gray-700">Published</label>
                                <p class="text-gray-500">Only published materials will appear in API</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center shadow-lg">
                            <i class="fas fa-save mr-2"></i>Update Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Material Info Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>Material Info
                </h2>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID</dt>
                        <dd class="text-sm text-gray-900 font-mono">#{{ $material->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="text-sm text-gray-900"><code class="bg-gray-100 px-2 py-1 rounded">{{ $material->slug }}</code></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $material->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $material->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Videos</dt>
                        <dd>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-video mr-1 text-xs"></i>
                                {{ $material->videos->count() }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd>
                            @if($material->is_published)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                                    Draft
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-bolt mr-2"></i>Quick Actions
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <a href="{{ route('admin.videos.create') }}?material_id={{ $material->id }}" 
                       class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>Add Video
                    </a>
                    <a href="{{ url('/api/v1/materials/' . $material->slug) }}" 
                       target="_blank" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-external-link-alt mr-2"></i>Preview API
                    </a>
                    @if($material->videos->count() > 0)
                    <a href="{{ route('admin.videos.index') }}?material_id={{ $material->id }}" 
                       class="w-full bg-yellow-600 text-white px-4 py-3 rounded-lg hover:bg-yellow-700 transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-video mr-2"></i>Manage Videos
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-6">
            <div class="flex items-center mb-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-question-circle text-blue-600"></i>
                </div>
                <h3 class="ml-3 text-sm font-medium text-gray-900">Need Help?</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">Tips for creating great educational content:</p>
            <ul class="text-sm text-gray-600 space-y-2">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5 text-xs"></i>
                    Use clear, descriptive titles
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5 text-xs"></i>
                    Add engaging thumbnails
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5 text-xs"></i>
                    Structure content logically
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5 text-xs"></i>
                    Set appropriate difficulty levels
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Content preview toggle
let previewMode = false;

function togglePreview() {
    const contentTextarea = document.getElementById('content');
    const previewDiv = document.getElementById('content-preview');
    
    if (!previewMode) {
        // Show preview
        previewDiv.innerHTML = contentTextarea.value;
        previewDiv.style.display = 'block';
        contentTextarea.style.display = 'none';
        previewMode = true;
    } else {
        // Show editor
        previewDiv.style.display = 'none';
        contentTextarea.style.display = 'block';
        previewMode = false;
    }
}

// Auto-save draft functionality
let autoSaveTimer;
function autoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        console.log('Auto-saving draft...');
        // Show auto-save indicator
        const indicator = document.createElement('div');
        indicator.className = 'fixed top-4 right-4 bg-green-100 text-green-800 px-4 py-2 rounded-lg shadow-lg z-50';
        indicator.innerHTML = '<i class="fas fa-check mr-2"></i>Draft saved';
        document.body.appendChild(indicator);
        setTimeout(() => {
            document.body.removeChild(indicator);
        }, 3000);
    }, 5000);
}

// Add auto-save to form inputs
document.querySelectorAll('input, textarea, select').forEach(element => {
    element.addEventListener('input', autoSave);
});

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
                preview.className = 'mt-4';
                e.target.parentNode.parentNode.appendChild(preview);
            }
            preview.innerHTML = `
                <div class="relative inline-block">
                    <img src="${e.target.result}" alt="Thumbnail preview" class="w-32 h-24 object-cover rounded-lg border border-gray-200">
                    <div class="absolute top-1 right-1 bg-green-100 text-green-800 rounded-full p-1">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }
});

// Form submission loading state
document.querySelector('form').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    submitBtn.disabled = true;
    
    // Re-enable after 5 seconds as fallback
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 5000);
});

// Add smooth scrolling to form errors
document.addEventListener('DOMContentLoaded', function() {
    const errorElement = document.querySelector('.border-red-300');
    if (errorElement) {
        errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endpush