<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'name' => 'Programming',
            'description' => 'Learn programming languages and concepts',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Web Development',
            'description' => 'Frontend and backend web development',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Mobile Development',
            'description' => 'iOS and Android app development',
            'is_active' => true,
        ]);
    }
}