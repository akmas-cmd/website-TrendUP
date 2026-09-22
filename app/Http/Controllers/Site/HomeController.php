<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $categoriesData = Category::orderBy('order')->get()->map(fn ($c) => [
            'key' => $c->key,
            'icon' => $c->icon,
        ])->values();

        $productsData = Product::with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderBy('id')
            ->take(8)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'cat' => $p->category->key,
                'price' => $p->price,
                'badge' => $p->badge ?? '',
                'icon' => $p->icon,
                'img' => $p->image,
                'rating' => $p->reviews_avg_rating ? round($p->reviews_avg_rating, 1) : 0,
                'reviewCount' => $p->reviews_count,
            ])->values();

        $bestsellersData = Product::with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('is_bestseller', true)
            ->orderBy('id')
            ->take(4)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'cat' => $p->category->key,
                'price' => $p->price,
                'badge' => $p->badge ?? '',
                'icon' => $p->icon,
                'img' => $p->image,
                'rating' => $p->reviews_avg_rating ? round($p->reviews_avg_rating, 1) : 0,
                'reviewCount' => $p->reviews_count,
            ])->values();

        $wishlistIds = $request->user()
            ? $request->user()->wishlists()->pluck('product_id')
            : collect();

        return view('site.beranda', [
            'categoriesData' => $categoriesData,
            'productsData' => $productsData,
            'bestsellersData' => $bestsellersData,
            'wishlistIds' => $wishlistIds,
        ]);
    }
}