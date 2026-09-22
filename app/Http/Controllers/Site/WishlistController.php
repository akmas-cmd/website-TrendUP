<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $wishlistData = Wishlist::with('product.category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn ($w) => [
                'id' => $w->product->id,
                'name' => $w->product->name,
                'cat' => $w->product->category->key,
                'price' => $w->product->price,
                'badge' => $w->product->badge ?? '',
                'icon' => $w->product->icon,
                'img' => $w->product->image,
            ])->values();

        return view('site.wishlist', [
            'wishlistData' => $wishlistData,
        ]);
    }

    public function store(Request $request, Product $product): JsonResponse
    {
        Wishlist::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        return response()->json(['status' => 'added']);
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return response()->json(['status' => 'removed']);
    }
}