<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('order')->get();

        $products = Product::with('category')->orderByDesc('id')->get()
            ->map(fn (Product $p) => $this->mapProduct($p))
            ->values();

        return view('admin.produk', [
            'productsData' => $products,
            'categoriesData' => $categories->map(fn (Category $c) => [
                'id' => $c->id,
                'label' => $this->categoryLabel($c),
            ])->values(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $validated['image'] = '/storage/'.$request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan.',
            'product' => $this->mapProduct($product->load('category')),
        ]);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
            }
            $validated['image'] = '/storage/'.$request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return response()->json([
            'message' => 'Produk berhasil diperbarui.',
            'product' => $this->mapProduct($product->fresh('category')),
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
        }

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'badge' => ['nullable', 'in:NONE,NEW,SALE'],
            'sizes' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['badge'] = ($data['badge'] ?? 'NONE') === 'NONE' ? null : $data['badge'];

        return $data;
    }

    private function mapProduct(Product $p): array
    {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'category_id' => $p->category_id,
            'cat' => $this->categoryLabel($p->category),
            'price' => $p->price,
            'stock' => $p->stock,
            'badge' => $p->badge ?? 'NONE',
            'icon' => $p->icon ?? 'fa-solid fa-box',
            'image' => $p->image,
            'description' => $p->description,
            'sizes' => $p->sizes,
        ];
    }

    private function categoryLabel(Category $c): string
    {
        return Str::title(str_replace('-', ' ', $c->key));
    }
}