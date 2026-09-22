<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['key' => 'jam-tangan', 'icon' => 'fa-solid fa-clock', 'order' => 1],
            ['key' => 'jam-dinding', 'icon' => 'fa-regular fa-clock', 'order' => 2],
            ['key' => 'jam-beker', 'icon' => 'fa-solid fa-bell', 'order' => 3],
            ['key' => 'baju', 'icon' => 'fa-solid fa-shirt', 'order' => 4],
            ['key' => 'sepatu', 'icon' => 'fa-solid fa-shoe-prints', 'order' => 5],
            ['key' => 'celana', 'icon' => 'fa-solid fa-user-tie', 'order' => 6],
            ['key' => 'jaket', 'icon' => 'fa-solid fa-vest', 'order' => 7],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['key' => $cat['key']], $cat);
        }

        $catIds = Category::pluck('id', 'key');

        $products = [
            ['id' => 1, 'name' => 'ROLEX', 'cat' => 'jam-tangan', 'price' => 150000000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-clock', 'image' => '/images/rolex.png', 'is_bestseller' => true],
            ['id' => 2, 'name' => 'RICHARD MILLE', 'cat' => 'jam-tangan', 'price' => 275000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-clock', 'image' => '/images/richard.png', 'is_bestseller' => false],
            ['id' => 3, 'name' => 'SEIKO', 'cat' => 'jam-dinding', 'price' => 180000, 'badge' => null, 'icon' => 'fa-regular fa-clock', 'image' => '/images/jddseiko.png', 'is_bestseller' => false],
            ['id' => 4, 'name' => 'TRENDUP Oversize Tee Black', 'cat' => 'baju', 'price' => 150000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-shirt', 'image' => '/images/fredperry.png', 'is_bestseller' => true],
            ['id' => 5, 'name' => 'ADIDAS LONDON', 'cat' => 'sepatu', 'price' => 425000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/london.png', 'is_bestseller' => true],
            ['id' => 6, 'name' => 'TRENDUP Street Mono', 'cat' => 'sepatu', 'price' => 399000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/p-6000.png', 'is_bestseller' => false],
            ['id' => 7, 'name' => 'TRENDUP Cargo Black', 'cat' => 'celana', 'price' => 210000, 'badge' => null, 'icon' => 'fa-solid fa-user-tie', 'image' => '/images/produk7.jpg', 'is_bestseller' => false],
            ['id' => 8, 'name' => 'TRENDUP Alarm Lime Edition', 'cat' => 'jam-beker', 'price' => 120000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-bell', 'image' => '/images/produk8.jpg', 'is_bestseller' => true],
            ['id' => 13, 'name' => 'TRENDUP Bomber Jacket Black', 'cat' => 'jaket', 'price' => 375000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-bomber.jpg', 'is_bestseller' => false],
            ['id' => 14, 'name' => 'TRENDUP Windbreaker Lime', 'cat' => 'jaket', 'price' => 340000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-windbreaker.jpg', 'is_bestseller' => false],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['id' => $p['id']],
                [
                    'name' => $p['name'],
                    'category_id' => $catIds[$p['cat']],
                    'price' => $p['price'],
                    'badge' => $p['badge'],
                    'icon' => $p['icon'],
                    'image' => $p['image'],
                    'is_bestseller' => $p['is_bestseller'],
                ]
            );
        }

        // Produk tambahan yang sebelumnya hardcode di shop.blade.php.
        $this->call(ShopProductSeeder::class);
    }
}