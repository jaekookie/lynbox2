<?php

namespace Database\Seeders;

use App\Models\Box;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BoxSeeder extends Seeder
{
    public function run(): void
    {
        $beautyCategory = Category::where('slug', 'beaute')->first();
        $foodCategory = Category::where('slug', 'alimentation')->first();
        $bookCategory = Category::where('slug', 'livres')->first();

        $boxes = [
            [
                'category_id' => $beautyCategory->id,
                'title' => 'Summer Glow Beauty Box',
                'description' => 'Une sélection exclusive de produits de beauté pour illuminer votre peau cet été. Crèmes hydratantes, masques détox, et sérums innovants de marques premium.',
                'price' => 29.99,
                'stock_quantity' => 100,
                'is_active' => true,
                'billing_cycle' => 'monthly',
                'image_url' => '/storage/boxes/summer-vibes.jpg',
                'emoji' => '💄',
            ],
            [
                'category_id' => $beautyCategory->id,
                'title' => 'Skincare Essentials',
                'description' => 'Routine complète de soin de la peau avec nettoyant, tonique, sérum et crème hydratante. Formules naturelles et testées dermatologiquement.',
                'price' => 39.99,
                'stock_quantity' => 75,
                'is_active' => true,
                'billing_cycle' => 'monthly',
                'image_url' => '/storage/boxes/skincare-essentials.jpg',
                'emoji' => '✨',
            ],
            [
                'category_id' => $foodCategory->id,
                'title' => 'Gourmet Food Box',
                'description' => 'Découvrez chaque mois une sélection de produits gastronomiques du monde. Chocolats, fromages, charcuteries et spécialités régionales.',
                'price' => 49.99,
                'stock_quantity' => 60,
                'is_active' => true,
                'billing_cycle' => 'monthly',
                'image_url' => '/storage/boxes/gourmet-food-box.jpg',
                'emoji' => '🍕',
            ],
            [
                'category_id' => $foodCategory->id,
                'title' => 'Healthy Snacks Bundle',
                'description' => 'Box de snacks sains et gourmands. Fruits secs, barres protéinées, fruits lyophilisés et bien d\'autres délices nutritifs.',
                'price' => 24.99,
                'stock_quantity' => 120,
                'is_active' => true,
                'billing_cycle' => 'monthly',
                'image_url' => 'https://images.unsplash.com/photo-1585257385519-991dcf2c475f?w=500&h=500&fit=crop',
                'emoji' => '🥗',
            ],
            [
                'category_id' => $bookCategory->id,
                'title' => 'Bibliophile Express',
                'description' => 'Trois romans jeunesse ou adulte soigneusement sélectionnés chaque trimestre. Tous les genres: fantasy, romance, thriller, littérature générale.',
                'price' => 34.99,
                'stock_quantity' => 80,
                'is_active' => true,
                'billing_cycle' => 'quarterly',
                'image_url' => 'https://images.unsplash.com/photo-1507842217343-583f20270319?w=500&h=500&fit=crop',
                'emoji' => '📚',
            ],
            [
                'category_id' => $bookCategory->id,
                'title' => 'Children\'s Discovery',
                'description' => 'Coffret de livres jeunesse avec histoires captivantes et illustrations magnifiques. Parfait pour les enfants de 3 à 12 ans.',
                'price' => 27.99,
                'stock_quantity' => 90,
                'is_active' => true,
                'billing_cycle' => 'monthly',
                'image_url' => 'https://images.unsplash.com/photo-1503235930437-8c6cea9583dc?w=500&h=500&fit=crop',
                'emoji' => '👶',
            ],
        ];

        foreach ($boxes as $box) {
            Box::create($box);
        }
    }
}
