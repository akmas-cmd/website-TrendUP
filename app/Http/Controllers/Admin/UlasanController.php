<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UlasanController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with(['product', 'user'])
            ->latest()
            ->get()
            ->map(fn (Review $r) => $this->mapReview($r))
            ->values();

        return view('admin.ulasan', [
            'reviewsData' => $reviews,
        ]);
    }

    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json(['message' => 'Ulasan berhasil dihapus.']);
    }

    private function mapReview(Review $r): array
    {
        return [
            'id' => $r->id,
            'product' => $r->product->name ?? '(produk dihapus)',
            'product_id' => $r->product_id,
            'customer' => $r->user->name ?? '(akun dihapus)',
            'email' => $r->user->email ?? '-',
            'rating' => $r->rating,
            'comment' => $r->comment,
            'photos' => $r->photo_paths,
            'video' => $r->video_path,
            'date' => $r->created_at->format('Y-m-d'),
        ];
    }
}