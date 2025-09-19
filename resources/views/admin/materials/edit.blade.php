@extends('layouts.admin')

@section('title', 'Edit Material')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Material: {{ $material->title }}</h1>
    <div class="btn-group" role="group">
        <a href="{{ route('admin.materials.show', $material) }}" class="btn btn-info">
            <i class="fas fa-eye me-2"></i>View
        </a>
        <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Materials
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.materials.update', $material) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Category Selection -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
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
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $material->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Current slug: <code>{{ $material->slug }}</code></div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $material->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Content *</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="10" required>{{ old('content', $material->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">You can use HTML tags for formatting</div>
                    </div>

                    <!-- Current Thumbnail -->
                    @if($material->thumbnail)
                    <div class="mb-3">
                        <label class="form-label">Current Thumbnail</label>
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $material->thumbnail) }}" 
                                 alt="Current thumbnail" width="200" class="rounded border">
                        </div>
                    </div>
                    @endif

                    <!-- New Thumbnail -->
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">
                            {{ $material->thumbnail ? 'Replace Thumbnail' : 'Add Thumbnail' }}
                        </label>
                        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                               id="thumbnail" name="thumbnail" accept="image/*">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Max size: 2MB. Leave empty to keep current image.</div>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3">
                        <label for="duration_minutes" class="form-label">Duration (Minutes)</label>
                        <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" 
                               id="duration_minutes" name="duration_minutes" 
                               value="{{ old('duration_minutes', $material->duration_minutes) }}" min="0">
                        @error('duration_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Difficulty -->
                    <div class="mb-3">
                        <label for="difficulty" class="form-label">Difficulty Level *</label>
                        <select class="form-select @error('difficulty') is-invalid @enderror" 
                                id="difficulty" name="difficulty" required>
                            <option value="">Select Difficulty</option>
                            <option value="beginner" 
                                {{ old('difficulty', $material->difficulty) == 'beginner' ? 'selected' : '' }}>
                                Beginner
                            </option>
                            <option value="intermediate" 
                                {{ old('difficulty', $material->difficulty) == 'intermediate' ? 'selected' : '' }}>
                                Intermediate
                            </option>
                            <option value="advanced" 
                                {{ old('difficulty', $material->difficulty) == 'advanced' ? 'selected' : '' }}>
                                Advanced
                            </option>
                        </select>
                        @error('difficulty')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="mb-3">
                        <label for="order" class="form-label">Order</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" 
                               id="order" name="order" value="{{ old('order', $material->order) }}" min="0">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Lower numbers appear first</div>
                    </div>

                    <!-- Published Status -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_published" name="is_published" 
                               {{ old('is_published', $material->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">
                            <strong>Published</strong>
                        </label>
                        <div class="form-text">Only published materials will appear in API</div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Update Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Material Info</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{ $material->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Slug:</strong></td>
                        <td><code>{{ $material->slug }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $material->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td>
                        <td>{{ $material->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Videos:</strong></td>
                        <td>
                            <span class="badge bg-info">{{ $material->videos->count() }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($material->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.videos.create') }}?material_id={{ $material->id }}" 
                       class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-2"></i>Add Video
                    </a>
                    <a href="{{ url('/api/v1/materials/' . $material->slug) }}" 
                       target="_blank" class="btn btn-info btn-sm">
                        <i class="fas fa-external-link-alt me-2"></i>Preview API
                    </a>
                    @if($material->videos->count() > 0)
                    <a href="{{ route('admin.videos.index') }}?material_id={{ $material->id }}" 
                       class="btn btn-warning btn-sm">
                        <i class="fas fa-video me-2"></i>Manage Videos
                    </a>
                    @endif
                </div>
            </div>
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

// Auto-save draft functionality (optional)
let autoSaveTimer;
function autoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        // Could implement auto-save to localStorage or server
        console.log('Auto-saving draft...');
    }, 5000);
}

// Add auto-save to form inputs
document.querySelectorAll('input, textarea, select').forEach(element => {
    element.addEventListener('input', autoSave);
});
</script>
@endpush