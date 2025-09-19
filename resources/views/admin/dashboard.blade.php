@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('page-description', 'Overview of your learning management system')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Categories Card -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-lg text-white shadow-lg card-hover">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-tags text-3xl opacity-80"></i>
            </div>
            <div class="ml-4">
                <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Categories</p>
                <p class="text-3xl font-bold">{{ $stats['categories'] }}</p>
                <p class="text-blue-100 text-xs mt-1">
                    <i class="fas fa-folder mr-1"></i>Content categories
                </p>
            </div>
        </div>
    </div>

    <!-- Materials Card -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-lg text-white shadow-lg card-hover">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-book text-3xl opacity-80"></i>
            </div>
            <div class="ml-4">
                <p class="text-green-100 text-sm font-medium uppercase tracking-wide">Materials</p>
                <p class="text-3xl font-bold">{{ $stats['materials'] }}</p>
                <p class="text-green-100 text-xs mt-1">
                    <i class="fas fa-file-alt mr-1"></i>Learning materials
                </p>
            </div>
        </div>
    </div>

    <!-- Videos Card -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-lg text-white shadow-lg card-hover">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-video text-3xl opacity-80"></i>
            </div>
            <div class="ml-4">
                <p class="text-purple-100 text-sm font-medium uppercase tracking-wide">Videos</p>
                <p class="text-3xl font-bold">{{ $stats['videos'] }}</p>
                <p class="text-purple-100 text-xs mt-1">
                    <i class="fas fa-play mr-1"></i>Video content
                </p>
            </div>
        </div>
    </div>

    <!-- Published Materials Card -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 rounded-lg text-white shadow-lg card-hover">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-3xl opacity-80"></i>
            </div>
            <div class="ml-4">
                <p class="text-yellow-100 text-sm font-medium uppercase tracking-wide">Published</p>
                <p class="text-3xl font-bold">{{ $stats['published_materials'] }}</p>
                <p class="text-yellow-100 text-xs mt-1">
                    <i class="fas fa-globe mr-1"></i>Live content
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="bg-white rounded-lg shadow-lg mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            Quick Actions
        </h2>
        <p class="text-sm text-gray-600 mt-1">Create new content quickly</p>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.categories.create') }}" 
               class="group bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-6 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-tags text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-800">Add Category</h3>
                        <p class="text-sm text-gray-600">Create new content category</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.materials.create') }}" 
               class="group bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-6 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center group-hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-book text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-800">Add Material</h3>
                        <p class="text-sm text-gray-600">Create learning material</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.videos.create') }}" 
               class="group bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg p-6 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center group-hover:bg-purple-700 transition-colors duration-200">
                        <i class="fas fa-video text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-800">Add Video</h3>
                        <p class="text-sm text-gray-600">Upload or link video content</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity and System Info -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-clock text-blue-500 mr-2"></i>
                Recent Activity
            </h2>
        </div>
        <div class="p-6">
            @if(isset($recentActivities) && count($recentActivities) > 0)
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-{{ $activity['icon'] }} text-gray-500 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $activity['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity['description'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-history text-gray-300 text-3xl mb-3"></i>
                    <p class="text-gray-500">No recent activities</p>
                    <p class="text-sm text-gray-400 mt-1">Start creating content to see activities here</p>
                </div>
            @endif
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-info-circle text-green-500 mr-2"></i>
                System Information
            </h2>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Laravel Version</span>
                <span class="text-sm text-gray-800 font-mono">{{ app()->version() }}</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">PHP Version</span>
                <span class="text-sm text-gray-800 font-mono">{{ phpversion() }}</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Environment</span>
                <span class="text-sm px-2 py-1 rounded-full {{ app()->environment() === 'production' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ ucfirst(app()->environment()) }}
                </span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-600">Debug Mode</span>
                <span class="text-sm px-2 py-1 rounded-full {{ config('app.debug') ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                    {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                </span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-sm font-medium text-gray-600">Server Time</span>
                <span class="text-sm text-gray-800 font-mono" id="serverTime">{{ now()->format('Y-m-d H:i:s') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Usage Statistics Chart (Optional) -->
@if(isset($chartData))
<div class="mt-8 bg-white rounded-lg shadow-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas fa-chart-line text-indigo-500 mr-2"></i>
            Content Growth
        </h2>
    </div>
    <div class="p-6">
        <div class="h-64 flex items-center justify-center text-gray-400">
            <div class="text-center">
                <i class="fas fa-chart-bar text-4xl mb-3"></i>
                <p>Chart visualization would go here</p>
                <p class="text-sm mt-1">Integration with Chart.js or similar library</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Update server time every second
    function updateServerTime() {
        const timeElement = document.getElementById('serverTime');
        if (timeElement) {
            const now = new Date();
            timeElement.textContent = now.toISOString().slice(0, 19).replace('T', ' ');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Update server time every second
        setInterval(updateServerTime, 1000);
        
        // Add some animation delays to cards
        const cards = document.querySelectorAll('.card-hover');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
@endpush