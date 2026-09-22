<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('order')->get()
            ->map(fn (Category $c) => $this->mapCategory($c))
            ->values();

        return view('admin.kategori', [
            'categoriesData' => $categories,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $key = $this->validatedKey($request);

        $category = Category::create([
            'key' => $key,
            'icon' => 'fa-solid fa-tag',
            'order' => (Category::max('order') ?? 0) + 1,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan.',
            'category' => $this->mapCategory($category->loadCount('products')),
        ]);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $key = $this->validatedKey($request, $category->id);

        $category->update([
            'key' => $key,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'category' => $this->mapCategory($category->fresh()->loadCount('products')),
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }

    private function validatedKey(Request $request, ?int $ignoreId = null): string
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $key = Str::slug($request->input('name'));

        $request->validate([
            'name' => [
                Rule::unique('categories', 'key')
                    ->ignore($ignoreId)
                    ->where(fn ($query) => $query->where('key', $key)),
            ],
        ], [
            'name.unique' => 'Nama/slug kategori ini sudah dipakai.',
        ]);

        if ($key === '') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'name' => 'Nama kategori tidak valid.',
            ]);
        }

        return $key;
    }

    private function mapCategory(Category $c): array
    {
        return [
            'id' => $c->id,
            'name' => Str::title(str_replace('-', ' ', $c->key)),
            'slug' => $c->key,
            'icon' => $c->icon,
            'count' => $c->products_count ?? 0,
        ];
    }
}