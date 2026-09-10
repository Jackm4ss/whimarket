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
                'name' => 'Fashion',
                'slug' => 'fashion',
                'image' => '/assets/categories/card-fashion.png',
                'bg_color' => '#4F18C8',
                'is_active' => true,
            ],
            [
                'name' => 'Tas & Aksesoris',
                'slug' => 'tas',
                'image' => '/assets/categories/card-tas.png',
                'bg_color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Hobi & Koleksi',
                'slug' => 'hobi',
                'image' => '/assets/categories/card-hobi.png',
                'bg_color' => '#4F18C8',
                'is_active' => true,
            ],
            [
                'name' => 'Merchandise',
                'slug' => 'merch',
                'image' => '/assets/categories/card-merch.png',
                'bg_color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Elektronik',
                'slug' => 'elektronik',
                'image' => '/assets/categories/card-elektronik.png',
                'bg_color' => '#4F18C8',
                'is_active' => true,
            ],
            [
                'name' => 'Kecantikan',
                'slug' => 'kecantikan',
                'image' => '/assets/categories/card-kecantikan.png',
                'bg_color' => '#F59E0B',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }
    }
}
