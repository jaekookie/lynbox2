<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Beauté',
                'slug' => 'beaute',
                'description' => 'Produits de beauté et de soin premium',
                'icon' => '💄',
            ],
            [
                'name' => 'Alimentation',
                'slug' => 'alimentation',
                'description' => 'Produits gastronomiques et spécialités culinaires',
                'icon' => '🍕',
            ],
            [
                'name' => 'Livres',
                'slug' => 'livres',
                'description' => 'Sélection de livres jeunesse et adulte',
                'icon' => '📚',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
