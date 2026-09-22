<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ShopProductSeeder extends Seeder
{
    public function run(): void
    {
        $catIds = Category::pluck('id', 'key');

        $products = [
            ['name' => 'TRENDUP Chrono Black', 'cat' => 'jam-tangan', 'price' => 350000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-clock', 'image' => '/images/produk-chrono-black.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Classic Lime', 'cat' => 'jam-tangan', 'price' => 275000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-clock', 'image' => '/images/produk-classic-lime.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Sport Digital', 'cat' => 'jam-tangan', 'price' => 310000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-clock', 'image' => '/images/produk-sport-digital.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Vintage Brown', 'cat' => 'jam-tangan', 'price' => 295000, 'badge' => null, 'icon' => 'fa-solid fa-clock', 'image' => '/images/produk-vintage-brown.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Steel Silver', 'cat' => 'jam-tangan', 'price' => 420000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-clock', 'image' => '/images/produk-steel-silver.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Lime Neon', 'cat' => 'jam-tangan', 'price' => 265000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-clock', 'image' => '/images/produk-lime-neon.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Midnight Black', 'cat' => 'jam-tangan', 'price' => 380000, 'badge' => null, 'icon' => 'fa-solid fa-clock', 'image' => '/images/produk-midnight-black.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Retro White', 'cat' => 'jam-tangan', 'price' => 330000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-clock', 'image' => '/images/produk-retro-white.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Carbon Grey', 'cat' => 'jam-tangan', 'price' => 410000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-clock', 'image' => '/images/produk-carbon-grey.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Aqua Blue', 'cat' => 'jam-tangan', 'price' => 290000, 'badge' => null, 'icon' => 'fa-solid fa-clock', 'image' => '/images/produk-aqua-blue.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Wall Mono', 'cat' => 'jam-dinding', 'price' => 180000, 'badge' => null, 'icon' => 'fa-regular fa-clock', 'image' => '/images/produk-wall-mono.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Wall Grid', 'cat' => 'jam-dinding', 'price' => 195000, 'badge' => 'NEW', 'icon' => 'fa-regular fa-clock', 'image' => '/images/produk-wall-grid.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Wall Round Black', 'cat' => 'jam-dinding', 'price' => 165000, 'badge' => 'SALE', 'icon' => 'fa-regular fa-clock', 'image' => '/images/produk-wall-round-black.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Wall Square White', 'cat' => 'jam-dinding', 'price' => 175000, 'badge' => null, 'icon' => 'fa-regular fa-clock', 'image' => '/images/produk-wall-square-white.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Wall Minimal Lime', 'cat' => 'jam-dinding', 'price' => 210000, 'badge' => 'NEW', 'icon' => 'fa-regular fa-clock', 'image' => '/images/produk-wall-minimal-lime.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Wall Numeric', 'cat' => 'jam-dinding', 'price' => 185000, 'badge' => null, 'icon' => 'fa-regular fa-clock', 'image' => '/images/produk-wall-numeric.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Wall Silent', 'cat' => 'jam-dinding', 'price' => 220000, 'badge' => 'SALE', 'icon' => 'fa-regular fa-clock', 'image' => '/images/produk-wall-silent.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Wall Retro', 'cat' => 'jam-dinding', 'price' => 190000, 'badge' => 'NEW', 'icon' => 'fa-regular fa-clock', 'image' => '/images/produk-wall-retro.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Wall Industrial', 'cat' => 'jam-dinding', 'price' => 230000, 'badge' => null, 'icon' => 'fa-regular fa-clock', 'image' => '/images/produk-wall-industrial.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Wall Pastel', 'cat' => 'jam-dinding', 'price' => 175000, 'badge' => 'SALE', 'icon' => 'fa-regular fa-clock', 'image' => '/images/produk-wall-pastel.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Alarm Basic', 'cat' => 'jam-beker', 'price' => 95000, 'badge' => null, 'icon' => 'fa-solid fa-bell', 'image' => '/images/produk-alarm-basic.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Alarm Lime Edition', 'cat' => 'jam-beker', 'price' => 120000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-bell', 'image' => '/images/produk-alarm-lime-edition.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Alarm Retro', 'cat' => 'jam-beker', 'price' => 110000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-bell', 'image' => '/images/produk-alarm-retro.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Alarm Digital', 'cat' => 'jam-beker', 'price' => 130000, 'badge' => null, 'icon' => 'fa-solid fa-bell', 'image' => '/images/produk-alarm-digital.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Alarm Mini', 'cat' => 'jam-beker', 'price' => 89000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-bell', 'image' => '/images/produk-alarm-mini.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Alarm Twin Bell', 'cat' => 'jam-beker', 'price' => 105000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-bell', 'image' => '/images/produk-alarm-twin-bell.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Alarm Glow', 'cat' => 'jam-beker', 'price' => 140000, 'badge' => null, 'icon' => 'fa-solid fa-bell', 'image' => '/images/produk-alarm-glow.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Alarm Travel', 'cat' => 'jam-beker', 'price' => 115000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-bell', 'image' => '/images/produk-alarm-travel.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Alarm Wood', 'cat' => 'jam-beker', 'price' => 150000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-bell', 'image' => '/images/produk-alarm-wood.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Alarm Silent Sweep', 'cat' => 'jam-beker', 'price' => 125000, 'badge' => null, 'icon' => 'fa-solid fa-bell', 'image' => '/images/produk-alarm-silent-sweep.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Oversize Tee Black', 'cat' => 'baju', 'price' => 150000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-shirt', 'image' => '/images/produk-oversize-tee-black.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Hoodie Lime Stripe', 'cat' => 'baju', 'price' => 320000, 'badge' => null, 'icon' => 'fa-solid fa-shirt', 'image' => '/images/produk-hoodie-lime-stripe.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Basic Tee White', 'cat' => 'baju', 'price' => 120000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-shirt', 'image' => '/images/produk-basic-tee-white.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Crewneck Grey', 'cat' => 'baju', 'price' => 210000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-shirt', 'image' => '/images/produk-crewneck-grey.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Longsleeve Black', 'cat' => 'baju', 'price' => 175000, 'badge' => null, 'icon' => 'fa-solid fa-shirt', 'image' => '/images/produk-longsleeve-black.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Polo Mono', 'cat' => 'baju', 'price' => 195000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-shirt', 'image' => '/images/produk-polo-mono.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Flannel Shirt', 'cat' => 'baju', 'price' => 230000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-shirt', 'image' => '/images/produk-flannel-shirt.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Tank Top Lime', 'cat' => 'baju', 'price' => 110000, 'badge' => null, 'icon' => 'fa-solid fa-shirt', 'image' => '/images/produk-tank-top-lime.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Varsity Jacket Tee', 'cat' => 'baju', 'price' => 260000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-shirt', 'image' => '/images/produk-varsity-jacket-tee.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Graphic Tee Bold', 'cat' => 'baju', 'price' => 165000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-shirt', 'image' => '/images/produk-graphic-tee-bold.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Runner Lime', 'cat' => 'sepatu', 'price' => 425000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/produk-runner-lime.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Street Mono', 'cat' => 'sepatu', 'price' => 399000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/produk-street-mono.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Chunky White', 'cat' => 'sepatu', 'price' => 450000, 'badge' => null, 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/produk-chunky-white.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Low Top Black', 'cat' => 'sepatu', 'price' => 375000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/produk-low-top-black.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP High Top Lime', 'cat' => 'sepatu', 'price' => 410000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/produk-high-top-lime.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Slip On Grey', 'cat' => 'sepatu', 'price' => 350000, 'badge' => null, 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/produk-slip-on-grey.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Canvas Classic', 'cat' => 'sepatu', 'price' => 365000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/produk-canvas-classic.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Trail Runner', 'cat' => 'sepatu', 'price' => 480000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/produk-trail-runner.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Retro Court', 'cat' => 'sepatu', 'price' => 395000, 'badge' => null, 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/produk-retro-court.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Knit Sneaker', 'cat' => 'sepatu', 'price' => 440000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-shoe-prints', 'image' => '/images/produk-knit-sneaker.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Cargo Black', 'cat' => 'celana', 'price' => 210000, 'badge' => null, 'icon' => 'fa-solid fa-user-tie', 'image' => '/images/produk-cargo-black.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Jogger Mono', 'cat' => 'celana', 'price' => 185000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-user-tie', 'image' => '/images/produk-jogger-mono.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Chino Grey', 'cat' => 'celana', 'price' => 195000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-user-tie', 'image' => '/images/produk-chino-grey.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Wide Leg Black', 'cat' => 'celana', 'price' => 220000, 'badge' => null, 'icon' => 'fa-solid fa-user-tie', 'image' => '/images/produk-wide-leg-black.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Track Pants Lime', 'cat' => 'celana', 'price' => 175000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-user-tie', 'image' => '/images/produk-track-pants-lime.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Denim Straight', 'cat' => 'celana', 'price' => 240000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-user-tie', 'image' => '/images/produk-denim-straight.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Shorts Cargo', 'cat' => 'celana', 'price' => 160000, 'badge' => null, 'icon' => 'fa-solid fa-user-tie', 'image' => '/images/produk-shorts-cargo.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Culottes Black', 'cat' => 'celana', 'price' => 205000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-user-tie', 'image' => '/images/produk-culottes-black.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Sweatpants Grey', 'cat' => 'celana', 'price' => 190000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-user-tie', 'image' => '/images/produk-sweatpants-grey.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Tapered Fit', 'cat' => 'celana', 'price' => 215000, 'badge' => null, 'icon' => 'fa-solid fa-user-tie', 'image' => '/images/produk-tapered-fit.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Bomber Jacket Black', 'cat' => 'jaket', 'price' => 375000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-bomber-jacket-black.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Windbreaker Lime', 'cat' => 'jaket', 'price' => 340000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-windbreaker-lime.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Denim Jacket', 'cat' => 'jaket', 'price' => 395000, 'badge' => null, 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-denim-jacket.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Varsity Jacket', 'cat' => 'jaket', 'price' => 420000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-varsity-jacket.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Puffer Jacket Black', 'cat' => 'jaket', 'price' => 450000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-puffer-jacket-black.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Track Jacket Lime', 'cat' => 'jaket', 'price' => 310000, 'badge' => null, 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-track-jacket-lime.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Parka Green', 'cat' => 'jaket', 'price' => 430000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-parka-green.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Coach Jacket', 'cat' => 'jaket', 'price' => 360000, 'badge' => 'SALE', 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-coach-jacket.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Vest Jacket', 'cat' => 'jaket', 'price' => 280000, 'badge' => null, 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-vest-jacket.jpg', 'is_bestseller' => false],
            ['name' => 'TRENDUP Fleece Jacket', 'cat' => 'jaket', 'price' => 330000, 'badge' => 'NEW', 'icon' => 'fa-solid fa-vest', 'image' => '/images/produk-fleece-jacket.jpg', 'is_bestseller' => false],        ];

        foreach ($products as $p) {
            // firstOrCreate (bukan updateOrCreate) supaya kalau ada produk dengan
            // nama+kategori yang sama persis dengan yang sudah di-seed sebelumnya
            // (mis. "TRENDUP Oversize Tee Black" & "TRENDUP Alarm Lime Edition"
            // yang juga ada di data beranda), datanya TIDAK ditimpa/di-reset.
            Product::firstOrCreate(
                [
                    'name' => $p['name'],
                    'category_id' => $catIds[$p['cat']],
                ],
                [
                    'price' => $p['price'],
                    'badge' => $p['badge'],
                    'icon' => $p['icon'],
                    'image' => $p['image'],
                    'is_bestseller' => $p['is_bestseller'],
                ]
            );
        }
    }
}