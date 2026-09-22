<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Halaman detail produk.
     * Produk diambil dari database (tabel products) berdasarkan ?id=... di query string.
     * Kalau id tidak dikirim / produknya tidak ada, tampilkan halaman 404.
     */
    public function show(Request $request): View
    {
        $product = Product::with(['category', 'reviews' => function ($query) {
            $query->with('user')->latest();
        }])->findOrFail($request->query('id'));

        $reviews = $product->reviews;
        $reviewCount = $reviews->count();
        $avgRating = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : 0;

        $myReview = $request->user()
            ? $reviews->firstWhere('user_id', $request->user()->id)
            : null;

        return view('site.rolex', [
            'product' => $product,
            'reviews' => $reviews,
            'reviewCount' => $reviewCount,
            'avgRating' => $avgRating,
            'myReview' => $myReview,
        ]);
    }
}