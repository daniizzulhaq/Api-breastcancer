@extends('layouts.admin')

@section('title', 'Create Material')

@section('page-title', 'Create Material')

@section('page-description', 'Add new learning material to the platform')

@section('content')
<!-- Header with Back Button -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Create New Material</h1>
        <p class="text-gray-600 mt-1">Add new learning material to the platform</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.materials.index') }}" 
           class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Materials
        </a>
    </div>
</div>

<!-- Form Container -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-plus mr-2 text-blue-600"></i>
                    Material Information
                </h2>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('admin.materials.store') }}" enctype="multipart/form-data" id="materialForm">
                    @csrf
                    
                    <!-- Category Selection -->
                    <div class="mb-6">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category *
                        </label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 ring-red-500 @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Material Title *
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 ring-red-500 @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required
                               placeholder="Enter material title">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Slug will be generated automatically from title</p>
                        <div id="slug-preview" class="mt-2 text-sm text-blue-600 hidden">
                            Preview slug: <code class="bg-blue-50 px-2 py-1 rounded"></code>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Short Description
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 ring-red-500 @enderror" 
                                  id="description" name="description" rows="3"
                                  placeholder="Brief description for material overview...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Brief summary that appears in material listings</p>
                    </div>

                    <!-- Content -->
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Content *
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 ring-red-500 @enderror" 
                                  id="content" name="content" rows="12" required
                                  placeholder="Enter the full material content here...">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-sm text-gray-500">You can use HTML tags for formatting</p>
                            <span id="word-counter" class="text-sm text-gray-500">0 words</span>
                        </div>
                    </div>

                    <!-- Thumbnail Upload -->
                    <div class="mb-6">
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">
                            Thumbnail Image
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center" id="thumbnailUploadArea">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload thumbnail</span>
                                        <input id="thumbnail" name="thumbnail" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                        @error('thumbnail')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        
                        <!-- Thumbnail Preview -->
                        <div id="thumbnailPreview" class="mt-4 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Thumbnail Preview:</p>
                            <div class="flex items-center space-x-4">
                                <img id="previewThumbnail" src="" alt="Preview" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                                <button type="button" onclick="removeThumbnail()" class="text-sm text-red-600 hover:text-red-800">
                                    <i class="fas fa-times mr-1"></i>Remove image
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Duration and Difficulty Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Duration -->
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                                Duration (Minutes)
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('duration_minutes') border-red-500 ring-red-500 @enderror" 
                                       id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 0) }}" min="0"
                                       placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                            </div>
                            @error('duration_minutes')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Difficulty -->
                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                                Difficulty Level *
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('difficulty') border-red-500 ring-red-500 @enderror" 
                                    id="difficulty" name="difficulty" required>
                                <option value="">Select Difficulty</option>
                                <option value="beginner" {{ old('difficulty') == 'beginner' ? 'selected' : '' }}>
                                    🟢 Beginner
                                </option>
                                <option value="intermediate" {{ old('difficulty') == 'intermediate' ? 'selected' : '' }}>
                                    🟡 Intermediate
                                </option>
                                <option value="advanced" {{ old('difficulty') == 'advanced' ? 'selected' : '' }}>
                                    🔴 Advanced
                                </option>
                            </select>
                            @error('difficulty')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Order -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Display Order
                        </label>
                        <input type="number" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('order') border-red-500 ring-red-500 @enderror" 
                               id="order" name="order" value="{{ old('order', 0) }}" min="0"
                               placeholder="0">
                        @error('order')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Lower numbers appear first in the list</p>
                    </div>

                    <!-- Published Status -->
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                   id="is_published" name="is_published" {{ old('is_published') ? 'checked' : '' }}>
                            <label for="is_published" class="ml-3 block text-sm font-medium text-gray-700">
                                Publish Material
                            </label>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Only published materials will be visible to users</p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.materials.index') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" name="action" value="draft"
                                class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Save as Draft
                        </button>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Create Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Guidelines -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                    Material Guidelines
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4 text-sm text-gray-600">
                    <div class="flex items-start">
                        <i class="fas fa-heading text-blue-500 mr-3 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-800">Title</p>
                            <p>Be descriptive and clear about the content</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-align-left text-green-500 mr-3 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-800">Description</p>
                            <p>Brief summary that appears in listings</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-file-alt text-purple-500 mr-3 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-800">Content</p>
                            <p>Detailed learning material with HTML formatting</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <i class="fas fa-signal text-orange-500 mr-3 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-800">Difficulty</p>
                            <p>Choose appropriate level for your audience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-tips mr-2 text-green-500"></i>
                    Pro Tips
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4 text-sm">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-save text-blue-600 mr-2 mt-1"></i>
                            <div>
                                <p class="font-medium text-blue-800">Save as Draft</p>
                                <p class="text-blue-600">Use draft mode to review before publishing</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-video text-yellow-600 mr-2 mt-1"></i>
                            <div>
                                <p class="font-medium text-yellow-800">Add Videos Later</p>
                                <p class="text-yellow-600">Create material first, then add videos</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-green-50 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-mobile-alt text-green-600 mr-2 mt-1"></i>
                            <div>
                                <p class="font-medium text-green-800">Mobile Preview</p>
                                <p class="text-green-600">Test content on mobile app before publishing</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Stats -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-purple-600"></i>
                    Content Stats
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Characters</span>
                    <span id="char-count" class="text-lg font-bold text-gray-900">0</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Words</span>
                    <span id="word-count-sidebar" class="text-lg font-bold text-gray-900">0</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Est. Read Time</span>
                    <span id="read-time" class="text-lg font-bold text-gray-900">0 min</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Slug generation
document.getElementById('title').addEventListener('input', function(e) {
    const title = e.target.value;
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    
    const slugPreview = document.getElementById('slug-preview');
    if (slug) {
        slugPreview.classList.remove('hidden');
        slugPreview.querySelector('code').textContent = slug;
    } else {
        slugPreview.classList.add('hidden');
    }
});

// Content analysis
document.getElementById('content').addEventListener('input', function(e) {
    const content = e.target.value;
    const words = content.split(/\s+/).filter(word => word.length > 0);
    const wordCount = words.length;
    const charCount = content.length;
    const readTime = Math.max(1, Math.ceil(wordCount / 200)); // 200 words per minute
    
    // Update counters
    document.getElementById('word-counter').textContent = `${wordCount} words`;
    document.getElementById('char-count').textContent = charCount.toLocaleString();
    document.getElementById('word-count-sidebar').textContent = wordCount.toLocaleString();
    document.getElementById('read-time').textContent = `${readTime} min`;
});

// Thumbnail upload preview
document.getElementById('thumbnail').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewThumbnail').src = e.target.result;
            document.getElementById('thumbnailPreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Remove thumbnail function
function removeThumbnail() {
    document.getElementById('thumbnail').value = '';
    document.getElementById('thumbnailPreview').classList.add('hidden');
    document.getElementById('previewThumbnail').src = '';
}

// Form validation
document.getElementById('materialForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    const categoryId = document.getElementById('category_id').value;
    const difficulty = document.getElementById('difficulty').value;
    
    if (!title) {
        e.preventDefault();
        alert('Material title is required.');
        document.getElementById('title').focus();
        return false;
    }
    
    if (!content) {
        e.preventDefault();
        alert('Content is required.');
        document.getElementById('content').focus();
        return false;
    }
    
    if (!categoryId) {
        e.preventDefault();
        alert('Please select a category.');
        document.getElementById('category_id').focus();
        return false;
    }
    
    if (!difficulty) {
        e.preventDefault();
        alert('Please select difficulty level.');
        document.getElementById('difficulty').focus();
        return false;
    }
    
    // Show loading state
    const submitBtns = document.querySelectorAll('button[type="submit"]');
    submitBtns.forEach(btn => {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
        btn.disabled = true;
        
        // Re-enable after 10 seconds
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 10000);
    });
});

// Drag and drop functionality for thumbnail
const uploadArea = document.getElementById('thumbnailUploadArea').parentElement;
const fileInput = document.getElementById('thumbnail');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    uploadArea.classList.add('border-blue-400', 'bg-blue-50');
}

function unhighlight(e) {
    uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
}

uploadArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        fileInput.files = files;
        fileInput.dispatchEvent(new Event('change'));
    }
}

// Auto-resize content textarea
const contentTextarea = document.getElementById('content');
contentTextarea.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.max(this.scrollHeight, 200) + 'px';
});

// Initialize content analysis
document.getElementById('content').dispatchEvent(new Event('input'));

// Category-based suggestions (placeholder for future enhancement)
document.getElementById('category_id').addEventListener('change', function() {
    const categoryId = this.value;
    if (categoryId) {
        // Future: Load category-specific templates or suggestions
        console.log('Category selected:', categoryId);
    }
});
</script>
@endpush