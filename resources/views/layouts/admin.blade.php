<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Breast Cancer Education')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-active { 
            background-color: #1e40af !important; 
            color: white !important; 
        }
        .sidebar-active i { 
            color: white !important; 
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card-hover:hover {
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-30">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-ribbon text-pink-600 mr-2"></i>
                    BC Education Admin
                </h1>
            </div>
            
            <nav class="mt-6">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 rounded-r-lg mr-4 transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-tachometer-alt mr-3 text-gray-400"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}" 
                           class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 rounded-r-lg mr-4 transition-colors duration-200 {{ request()->routeIs('admin.categories.*') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-tags mr-3 text-gray-400"></i>
                            Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.materials.index') }}" 
                           class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 rounded-r-lg mr-4 transition-colors duration-200 {{ request()->routeIs('admin.materials.*') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-book mr-3 text-gray-400"></i>
                            Materials
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.videos.index') }}" 
                           class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 rounded-r-lg mr-4 transition-colors duration-200 {{ request()->routeIs('admin.videos.*') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-video mr-3 text-gray-400"></i>
                            Videos
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- User info -->
            <div class="absolute bottom-0 w-full p-6 border-t border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-shield text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">Admin Panel</p>
                        <p class="text-xs text-gray-500" id="currentDateTime"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="ml-64 flex-1">
            <!-- Top Navigation Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">
                                @yield('page-title', 'Dashboard')
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">
                                @yield('page-description', 'Welcome to the admin panel')
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Quick Actions Dropdown -->
                            <div class="relative">
                                <button onclick="toggleQuickAdd()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                                    <i class="fas fa-plus mr-2"></i>
                                    Quick Add
                                    <i class="fas fa-chevron-down ml-2"></i>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden" id="quickAddDropdown">
                                    <a href="{{ route('admin.categories.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tags mr-2"></i>Add Category
                                    </a>
                                    <a href="{{ route('admin.materials.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-book mr-2"></i>Add Material
                                    </a>
                                    <a href="{{ route('admin.videos.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-video mr-2"></i>Add Video
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="p-8">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg fade-in">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                        <div>
                            <p class="font-medium">Please fix the following errors:</p>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Page Content -->
                <div class="fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // Update current date time
        function updateDateTime() {
            const now = new Date();
            const element = document.getElementById('currentDateTime');
            if (element) {
                element.textContent = now.toLocaleString('id-ID', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        }

        // Toggle quick add dropdown
        function toggleQuickAdd() {
            const dropdown = document.getElementById('quickAddDropdown');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateDateTime();
            setInterval(updateDateTime, 60000); // Update every minute

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const quickAddBtn = event.target.closest('button');
                const quickAddDropdown = document.getElementById('quickAddDropdown');
                
                if (quickAddDropdown && !quickAddBtn?.onclick && !quickAddDropdown.contains(event.target)) {
                    quickAddDropdown.classList.add('hidden');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>