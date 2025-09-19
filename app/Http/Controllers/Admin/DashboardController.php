<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Models\Video;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'categories' => Category::count(),
            'materials' => Material::count(),
            'videos' => Video::count(),
            'published_materials' => Material::where('is_published', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}