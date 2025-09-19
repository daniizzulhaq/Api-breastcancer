@extends('layouts.admin')

@section('title', 'Edit Video')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Video: {{ $video->title }}</h1>
    <div class="btn-group" role="group">
        <a href="{{ $video->video_url }}" target="_blank" class="btn btn-info">
            <i class="fas fa-play me-2"></i>Watch Video
        </a>
        <a href="{{ route('admin.videos.show', $video) }}" class="btn btn-outline-info">
            <i class="fas fa-eye me-2"></i>View Details
        </a>
        <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Videos
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.videos.update', $video) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Material Selection -->
                    <div class="mb-3">
                        <label for="material_id" class="form-label">Material *</label>
                        <select class="form-select @error('material_id') is-invalid @enderror" 
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
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Video Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $video->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Current slug: <code>{{ $video->slug }}</code></div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $video->description) }}</textarea>
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
                            <option value="youtube" {{ old('video_type', $video->video_type) == 'youtube' ? 'selected' : '' }}>YouTube</option>
                            <option value="vimeo" {{ old('video_type', $video->video_type) == 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                            <option value="local" {{ old('video_type', $video->video_type) == 'local' ? 'selected' : '' }}>Local File</option>
                        </select>
                        @error('video_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Video URL -->
                    <div class="mb-3">
                        <label for="video_url" class="form-label">Video URL *</label>
                        <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                               id="video_url" name="video_url" value="{{ old('video_url', $video->video_url) }}" required>
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

                    <!-- Current Thumbnail -->
                    @if($video->thumbnail)
                    <div class="mb-3">
                        <label class="form-label">Current Thumbnail</label>
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $video->thumbnail) }}" 
                                 alt="Current thumbnail" width="200" class="rounded border">
                        </div>
                    </div>
                    @endif

                    <!-- New Thumbnail -->
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">
                            {{ $video->thumbnail ? 'Replace Thumbnail' : 'Add Thumbnail' }}
                        </label>
                        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                               id="thumbnail" name="thumbnail" accept="image/*">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Max size: 2MB. Leave empty to keep current thumbnail.</div>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3">
                        <label for="duration_seconds" class="form-label">Duration (Seconds)</label>
                        <input type="number" class="form-control @error('duration_seconds') is-invalid @enderror" 
                               id="duration_seconds" name="duration_seconds" 
                               value="{{ old('duration_seconds', $video->duration_seconds) }}" min="0">
                        @error('duration_seconds')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Current: {{ gmdate('i:s', $video->duration_seconds) }} ({{ $video->duration_seconds }} seconds)
                        </div>
                    </div>

                    <!-- Order -->
                    <div class="mb-3">
                        <label for="order" class="form-label">Order</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" 
                               id="order" name="order" value="{{ old('order', $video->order) }}" min="0">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Order within the material (0 for automatic ordering)</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select @error('is_active') is-invalid @enderror" 
                                id="is_active" name="is_active">
                            <option value="1" {{ old('is_active', $video->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $video->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Free Preview -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('is_free_preview') is-invalid @enderror" 
                                   type="checkbox" value="1" id="is_free_preview" name="is_free_preview"
                                   {{ old('is_free_preview', $video->is_free_preview) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_free_preview">
                                Free Preview
                            </label>
                            @error('is_free_preview')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Allow non-premium users to watch this video</div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Video
                        </button>
                        <a href="{{ route('admin.videos.show', $video) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar with video info -->
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Video Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Created</small>
                    <div>{{ $video->created_at->format('M d, Y H:i') }}</div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Last Updated</small>
                    <div>{{ $video->updated_at->format('M d, Y H:i') }}</div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Material</small>
                    <div>
                        <a href="{{ route('admin.materials.show', $video->material) }}" class="text-decoration-none">
                            {{ $video->material->title }}
                        </a>
                        <br>
                        <small class="text-muted">{{ $video->material->category->name }}</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Views</small>
                    <div>{{ number_format($video->views_count ?? 0) }}</div>
                </div>

                @if($video->duration_seconds)
                <div class="mb-3">
                    <small class="text-muted">Duration</small>
                    <div>{{ gmdate('H:i:s', $video->duration_seconds) }}</div>
                </div>
                @endif

                <div class="mb-0">
                    <small class="text-muted">Status</small>
                    <div>
                        @if($video->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                        
                        @if($video->is_free_preview)
                            <span class="badge bg-info ms-1">Free Preview</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Preview -->
        @if($video->video_type === 'youtube' || $video->video_type === 'vimeo')
        <div class="card shadow mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-play me-2"></i>Video Preview
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="ratio ratio-16x9">
                    @if($video->video_type === 'youtube')
                        @php
                            $videoId = '';
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $video->video_url, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp
                        @if($videoId)
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
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
                                    frameborder="0" allowfullscreen></iframe>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleVideoUrlHelp() {
    const videoType = document.getElementById('video_type').value;
    
    // Hide all help divs
    document.getElementById('youtube-help').style.display = 'none';
    document.getElementById('vimeo-help').style.display = 'none';
    document.getElementById('local-help').style.display = 'none';
    
    // Show relevant help
    if (videoType === 'youtube') {
        document.getElementById('youtube-help').style.display = 'block';
    } else if (videoType === 'vimeo') {
        document.getElementById('vimeo-help').style.display = 'block';
    } else if (videoType === 'local') {
        document.getElementById('local-help').style.display = 'block';
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
            const formatted = new Date(seconds * 1000).toISOString().substr(11, 8);
            console.log('Duration: ' + formatted + ' (' + seconds + ' seconds)');
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
                preview.innerHTML = '<label class="form-label">New Thumbnail Preview</label><br><img id="preview-img" class="rounded border" width="200">';
                e.target.parentNode.insertAdjacentElement('afterend', preview);
            }
            document.getElementById('preview-img').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection