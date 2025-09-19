@extends('layouts.admin')

@section('title', 'Videos')

@section('page-title', 'Videos Management')

@section('page-description', 'Manage educational video content and media')

@section('content')
<!-- Header with Add Button -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Video Management</h1>
        <p class="text-gray-600 mt-1">Upload and organize video content for learning materials</p>
    </div>
    <a href="{{ route('admin.videos.create') }}" 
       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center shadow-lg">
        <i class="fas fa-plus mr-2"></i>
        Add Video
    </a>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-video text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Videos</p>
                <p class="text-2xl font-bold text-gray-800">{{ $videos->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Published</p>
                <p class="text-2xl font-bold text-gray-800">{{ App\Models\Video::where('is_published', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-edit text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Drafts</p>
                <p class="text-2xl font-bold text-gray-800">{{ App\Models\Video::where('is_published', false)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-lg border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Duration</p>
                <p class="text-2xl font-bold text-gray-800">{{ gmdate('H:i', App\Models\Video::sum('duration_seconds')) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-filter mr-2"></i>
        Filter Videos
    </h2>
    <form method="GET" action="{{ route('admin.videos.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="material_id" class="block text-sm font-medium text-gray-700 mb-2">Filter by Material</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        id="material_id" name="material_id">
                    <option value="">All Materials</option>
                    @foreach(App\Models\Material::with('category')->get() as $material)
                        <option value="{{ $material->id }}" 
                            {{ request('material_id') == $material->id ? 'selected' : '' }}>
                            [{{ $material->category->name }}] {{ $material->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="video_type" class="block text-sm font-medium text-gray-700 mb-2">Filter by Type</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        id="video_type" name="video_type">
                    <option value="">All Types</option>
                    <option value="youtube" {{ request('video_type') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                    <option value="vimeo" {{ request('video_type') == 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                    <option value="local" {{ request('video_type') == 'local' ? 'selected' : '' }}>Local</option>
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        id="status" name="status">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-filter mr-2"></i>
                    Apply Filters
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Videos Table -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas fa-list mr-2"></i>
            All Videos
        </h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thumbnail</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($videos as $video)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                        #{{ $video->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($video->thumbnail)
                            <img src="{{ asset('storage/' . $video->thumbnail) }}" 
                                 alt="{{ $video->title }}" 
                                 class="w-20 h-12 rounded-lg object-cover border border-gray-200 shadow-sm">
                        @else
                            <div class="w-20 h-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-video text-gray-400"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900 max-w-xs">
                            {{ Str::limit($video->title, 40) }}
                        </div>
                        @if($video->description)
                            <div class="text-sm text-gray-500 mt-1">
                                {{ Str::limit($video->description, 50) }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-1">
                                {{ $video->material->category->name }}
                            </span>
                            <div class="font-medium text-gray-900">
                                {{ Str::limit($video->material->title, 25) }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($video->video_type)
                            @case('youtube')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fab fa-youtube mr-1 text-xs"></i>
                                    YouTube
                                </span>
                                @break
                            @case('vimeo')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fab fa-vimeo mr-1 text-xs"></i>
                                    Vimeo
                                </span>
                                @break
                            @case('local')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-server mr-1 text-xs"></i>
                                    Local
                                </span>
                                @break
                            @default
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-question mr-1 text-xs"></i>
                                    Unknown
                                </span>
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($video->duration_seconds > 0)
                            <div class="flex items-center">
                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                {{ gmdate('i:s', $video->duration_seconds) }}
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-sort-numeric-down mr-1 text-xs"></i>
                            {{ $video->order }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($video->is_published)
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $video->created_at->format('d M Y') }}
                        <div class="text-xs text-gray-400">{{ $video->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ $video->video_url }}" target="_blank" 
                               class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 rounded-md hover:bg-purple-200 transition-colors duration-200"
                               title="Watch Video">
                                <i class="fas fa-play text-xs"></i>
                            </a>
                            <a href="{{ route('admin.videos.show', $video) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors duration-200"
                               title="View Details">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('admin.videos.edit', $video) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition-colors duration-200"
                               title="Edit Video">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <button onclick="confirmDelete({{ $video->id }}, '{{ $video->title }}')"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors duration-200"
                                    title="Delete Video">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                            
                            <!-- Hidden delete form -->
                            <form id="delete-form-{{ $video->id }}" 
                                  method="POST" 
                                  action="{{ route('admin.videos.destroy', $video) }}" 
                                  class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-video text-gray-300 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No videos found</h3>
                            <p class="text-gray-500 mb-4">Start building your video library by adding your first video content.</p>
                            <a href="{{ route('admin.videos.create') }}" 
                               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                                <i class="fas fa-plus mr-2"></i>
                                Add Your First Video
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($videos->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing {{ $videos->firstItem() }} to {{ $videos->lastItem() }} of {{ $videos->total() }} results
            </div>
            <div class="flex items-center space-x-2">
                @if ($videos->onFirstPage())
                    <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
                @else
                    <a href="{{ $videos->appends(request()->query())->previousPageUrl() }}" class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Previous</a>
                @endif

                @foreach ($videos->appends(request()->query())->getUrlRange(1, $videos->lastPage()) as $page => $url)
                    @if ($page == $videos->currentPage())
                        <span class="px-3 py-2 text-white bg-blue-600 rounded-md">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($videos->hasMorePages())
                    <a href="{{ $videos->appends(request()->query())->nextPageUrl() }}" class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next</a>
                @else
                    <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(videoId, videoTitle) {
    if (confirm(`Are you sure you want to delete "${videoTitle}"?\n\nThis action cannot be undone.`)) {
        document.getElementById('delete-form-' + videoId).submit();
    }
}

// Enhanced functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to action buttons
    document.querySelectorAll('a[href*="create"], a[href*="edit"]').forEach(link => {
        link.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon && !this.hasAttribute('target')) {
                icon.className = 'fas fa-spinner fa-spin text-xs';
            }
        });
    });
    
    // Auto-submit form on filter change
    const filterSelects = document.querySelectorAll('select[name="material_id"], select[name="video_type"], select[name="status"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Auto-submit form after short delay
            setTimeout(() => {
                this.form.submit();
            }, 100);
        });
    });
    
    // Preview video functionality
    document.querySelectorAll('[title="Watch Video"]').forEach(button => {
        button.addEventListener('click', function(e) {
            // Could add modal preview here instead of opening new tab
            console.log('Opening video preview for:', this.href);
        });
    });
});
</script>
@endpush