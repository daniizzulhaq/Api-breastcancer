@extends('layouts.admin')

@section('title', 'Edit Category')

@section('page-title', 'Edit Category')

@section('page-description', 'Update category information and settings')

@section('content')
<!-- Header with Back Button -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Edit Category: {{ $category->name }}</h1>
        <p class="text-gray-600 mt-1">Update category information and settings</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.categories.show', $category) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
            <i class="fas fa-eye mr-2"></i>
            View
        </a>
        <a href="{{ route('admin.categories.index') }}" 
           class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Categories
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
                    <i class="fas fa-edit mr-2 text-blue-600"></i>
                    Update Category Information
                </h2>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" id="categoryForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Name Field -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name *
                        </label>
                        <input type="text" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 ring-red-500 @enderror" 
                               id="name" name="name" value="{{ old('name', $category->name) }}" required
                               placeholder="Enter category name">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        @if($category->slug)
                            <p class="mt-2 text-sm text-gray-500">Current slug: <code class="bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</code></p>
                        @endif
                    </div>

                    <!-- Description Field -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 ring-red-500 @enderror" 
                                  id="description" name="description" rows="4"
                                  placeholder="Describe what this category contains...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Optional description to help users understand the category content</p>
                    </div>

                    <!-- Current Image Display -->
                    @if($category->image)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Current Image
                        </label>
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('storage/' . $category->image) }}" 
                                 alt="Current category image" 
                                 class="w-24 h-24 object-cover rounded-lg border border-gray-200 shadow-sm">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ basename($category->image) }}</p>
                                <p class="text-sm text-gray-500">Uploaded {{ $category->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Image Upload -->
                    <div class="mb-6">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $category->image ? 'Replace Image' : 'Upload Image' }}
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center" id="imageUploadArea">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>{{ $category->image ? 'Upload new image' : 'Upload an image' }}</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                @if($category->image)
                                    <p class="text-xs text-blue-600">Leave empty to keep current image</p>
                                @endif
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        
                        <!-- New Image Preview -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">New Image Preview:</p>
                            <img id="previewImg" src="" alt="Preview" class="max-w-xs h-32 object-cover rounded-lg border border-gray-200">
                            <button type="button" onclick="removeImage()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                <i class="fas fa-times mr-1"></i>Remove new image
                            </button>
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                   id="is_active" name="is_active" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-3 block text-sm font-medium text-gray-700">
                                Active Category
                            </label>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Active categories will be visible to users</p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.categories.show', $category) }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Category Stats -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
                    Category Statistics
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Materials</span>
                    <span class="text-lg font-bold text-gray-900">{{ $category->materials_count ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Videos</span>
                    <span class="text-lg font-bold text-gray-900">{{ $category->videos_count ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Status</span>
                    @if($category->is_active)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1"></div>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1"></div>
                            Inactive
                        </span>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Created</span>
                    <span class="text-sm text-gray-900">{{ $category->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Last Updated</span>
                    <span class="text-sm text-gray-900">{{ $category->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Guidelines -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                    Update Tips
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4 text-sm text-gray-600">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-800">Name Changes</p>
                            <p>Changing the name will update the URL slug automatically</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-image text-blue-500 mr-3 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-800">Image Updates</p>
                            <p>New images will replace the current one. Leave empty to keep current image</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <i class="fas fa-eye-slash text-red-500 mr-3 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-800">Deactivating</p>
                            <p>Inactive categories won't appear in user listings but materials remain accessible</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-bolt mr-2 text-purple-600"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('admin.categories.show', $category) }}" 
                   class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-eye mr-3"></i>
                    View Category Details
                </a>
                @if($category->materials_count > 0)
                <a href="{{ route('admin.materials.index', ['category' => $category->id]) }}" 
                   class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-book mr-3"></i>
                    View Category Materials
                </a>
                @endif
                <button onclick="confirmDelete()" 
                        class="flex items-center w-full px-4 py-3 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-200">
                    <i class="fas fa-trash mr-3"></i>
                    Delete Category
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="delete-form" method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
// Image upload preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

// Remove image function
function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('previewImg').src = '';
}

// Delete confirmation
function confirmDelete() {
    const categoryName = '{{ $category->name }}';
    const materialsCount = {{ $category->materials_count ?? 0 }};
    
    let message = `Are you sure you want to delete the category "${categoryName}"?`;
    
    if (materialsCount > 0) {
        message += `\n\nThis category contains ${materialsCount} material(s). All materials will become uncategorized.`;
    }
    
    message += '\n\nThis action cannot be undone.';
    
    if (confirm(message)) {
        document.getElementById('delete-form').submit();
    }
}

// Form validation
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    
    if (!name) {
        e.preventDefault();
        alert('Category name is required.');
        document.getElementById('name').focus();
        return false;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    submitBtn.disabled = true;
    
    // Re-enable button after 5 seconds (in case of slow response)
    setTimeout(function() {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 5000);
});

// Drag and drop functionality
const uploadArea = document.getElementById('imageUploadArea').parentElement;
const fileInput = document.getElementById('image');

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

// Auto-resize textarea
const textarea = document.getElementById('description');
textarea.addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 200) + 'px';
});

// Character counter for description (optional)
const maxDescriptionLength = 500;
textarea.addEventListener('input', function() {
    const remaining = maxDescriptionLength - this.value.length;
    let counter = document.getElementById('description-counter');
    
    if (!counter) {
        counter = document.createElement('p');
        counter.id = 'description-counter';
        counter.className = 'mt-2 text-sm text-gray-500';
        textarea.parentNode.appendChild(counter);
    }
    
    counter.textContent = `${remaining} characters remaining`;
    
    if (remaining < 0) {
        counter.className = 'mt-2 text-sm text-red-600';
        counter.textContent = `${Math.abs(remaining)} characters over limit`;
    } else if (remaining < 50) {
        counter.className = 'mt-2 text-sm text-yellow-600';
    } else {
        counter.className = 'mt-2 text-sm text-gray-500';
    }
});

// Initialize character counter
textarea.dispatchEvent(new Event('input'));
</script>
@endpush