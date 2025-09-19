@extends('layouts.admin')

@section('title', 'Create Video')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Video</h1>
    <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Videos
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Material Selection -->
                    <div class="mb-3">
                        <label for="material_id" class="form-label">Material *</label>
                        <select class="form-select @error('material_id') is-invalid @enderror" 
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
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Video Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Be specific and descriptive</div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Video Type -->
                    <div class="mb-3">
                        <label for="video_type" class="form-label">Video Type *</label>
                        <select class="form-select @error('video_type') is-invalid @enderror" 
                                id="video_type" name="video_type" required onchange="toggleVideoUrlHelp()">
                            <option value="">Select Type</option>
                            <option value="youtube" {{ old('video_type') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                            <option value="vimeo" {{ old('video_type') == 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                            <option value="local" {{ old('video_type') == 'local' ? 'selected' : '' }}>Local File</option>
                        </select>
                        @error('video_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Video URL -->
                    <div class="mb-3">
                        <label for="video_url" class="form-label">Video URL *</label>
                        <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                               id="video_url" name="video_url" value="{{ old('video_url') }}" required>
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="url-help">
                            <div id="youtube-help" style="display: none;">
                                <i class="fab fa-youtube text-danger"></i> 
                                Example: https://www.youtube.com/watch?v=VIDEO_ID
                            </div>
                            <div id="vimeo-help" style="display: none;">
                                <i class="fab fa-vimeo text-info"></i> 
                                Example: https://vimeo.com/VIDEO_ID
                            </div>
                            <div id="local-help" style="display: none;">
                                <i class="fas fa-server text-success"></i> 
                                Example: /storage/videos/video.mp4 or full URL
                            </div>
                        </div>
                    </div>

                    <!-- Thumbnail -->
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail Image</label>
                        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                               id="thumbnail" name="thumbnail" accept="image/*">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Max size: 2MB. Leave empty to auto-generate from video</div>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3">
                        <label for="duration_seconds" class="form-label">Duration (Seconds)</label>
                        <input type="number" class="form-control @error('duration_seconds') is-invalid @enderror" 
                               id="duration_seconds" name="duration_seconds" value="{{ old('duration_seconds', 0) }}" min="0">
                        @error('duration_seconds')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Will be auto-detected for YouTube/Vimeo videos</div>
                    </div>

                    <!-- Order -->
                    <div class="mb-3">
                        <label for="order" class="form-label">Order</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" 
                               id="order" name="order" value="{{ old('order', 1) }}" min="0">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Lower numbers appear first in the material</div>
                    </div>

                    <!-- Published Status -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_published" name="is_published" 
                               {{ old('is_published') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">
                            <strong>Published</strong>
                        </label>
                        <div class="form-text">Only published videos will appear in API</div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Create Video
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Help Card -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Video Guidelines</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Best Practices:</h6>
                    <ul class="mb-0 small">
                        <li><strong>Title:</strong> Clear and specific</li>
                        <li><strong>Description:</strong> Brief overview</li>
                        <li><strong>Order:</strong> Logical sequence</li>
                        <li><strong>Quality:</strong> HD resolution preferred</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Video Types:</h6>
                    <ul class="mb-0 small">
                        <li><strong>YouTube:</strong> Free, good for public content</li>
                        <li><strong>Vimeo:</strong> Better quality, privacy options</li>
                        <li><strong>Local:</strong> Full control, requires storage</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Preview</h5>
            </div>
            <div class="card-body">
                <div id="video-preview" class="text-center text-muted">
                    <i class="fas fa-video fa-3x mb-3"></i>
                    <p>Video preview will appear here</p>
                </div>
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

// URL validation and preview
document.getElementById('video_url').addEventListener('input', function(e) {
    const url = e.target.value;
    const videoType = document.getElementById('video_type').value;
    const preview = document.getElementById('video-preview');
    
    if (url && videoType === 'youtube') {
        const videoId = extractYouTubeId(url);
        if (videoId) {
            preview.innerHTML = `<iframe width="100%" height="200" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>`;
        }
    }
});

function extractYouTubeId(url) {
    const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

// Auto-fill duration for YouTube videos (would need API key in production)
document.getElementById('video_type').addEventListener('change', function() {
    if (this.value === 'youtube') {
        // Could implement YouTube API integration here
        console.log('YouTube selected - could auto-fetch duration');
    }
});
</script>
@endpush
```
