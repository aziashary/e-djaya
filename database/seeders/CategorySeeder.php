<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama' => 'Roti', 'deskripsi' => 'Makanan', 'is_active' => true],
            ['nama' => 'Kopi', 'deskripsi' => 'Minuman', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
