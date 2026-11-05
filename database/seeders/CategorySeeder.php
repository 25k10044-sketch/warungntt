<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Daging', 'slug' => 'daging'],
            ['name' => 'Ikan & Seafood', 'slug' => 'ikan-seafood'],
            ['name' => 'Sayur & Nasi', 'slug' => 'sayur-nasi'],
            ['name' => 'Jajanan', 'slug' => 'jajanan'],
            ['name' => 'Minuman', 'slug' => 'minuman'],
        ] as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
