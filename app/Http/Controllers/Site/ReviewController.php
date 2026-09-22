<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    private const MAX_PHOTOS = 5;

    /**
     * Simpan ulasan baru, atau perbarui ulasan pelanggan yang sudah ada
     * untuk produk yang sama (satu pelanggan = satu ulasan per produk).
     *
     * Ulasan boleh disertai maksimal 5 foto dan 1 video.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'photos' => ['nullable', 'array', 'max:'.self::MAX_PHOTOS],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:20480'],
            'remove_media' => ['nullable', 'array'],
            'remove_media.*' => ['string'],
        ], [
            'photos.max' => __('site.pd_review_err_photos_max'),
            'photos.*.image' => __('site.pd_review_err_photo_type'),
            'photos.*.mimes' => __('site.pd_review_err_photo_type'),
            'photos.*.max' => __('site.pd_review_err_photo_size'),
            'photos.*.uploaded' => __('site.pd_review_err_upload'),
            'video.mimetypes' => __('site.pd_review_err_video_type'),
            'video.max' => __('site.pd_review_err_video_size'),
            'video.uploaded' => __('site.pd_review_err_upload'),
        ]);

        $review = Review::firstOrNew([
            'product_id' => $product->id,
            'user_id' => $request->user()->id,
        ]);

        // Media yang sudah ada. Hanya path yang benar-benar milik ulasan ini yang bisa dihapus.
        $current = collect($review->media ?? []);
        $remove = $validated['remove_media'] ?? [];
        $kept = $current->reject(fn ($m) => in_array($m['path'], $remove, true))->values();
        $filesToDelete = $current->pluck('path')->diff($kept->pluck('path'))->all();

        $newPhotos = $request->file('photos', []);
        $newVideo = $request->file('video');

        if ($kept->where('type', 'image')->count() + count($newPhotos) > self::MAX_PHOTOS) {
            return redirect(route('product.detail', ['id' => $product->id]).'#tab-review')
                ->withInput()
                ->withErrors(['photos' => __('site.pd_review_err_photos_max')]);
        }

        if ($newVideo) {
            // Hanya 1 video per ulasan: video lama diganti dengan yang baru.
            $filesToDelete = array_merge($filesToDelete, $kept->where('type', 'video')->pluck('path')->all());
            $kept = $kept->reject(fn ($m) => $m['type'] === 'video')->values();
        }

        $media = $kept->all();

        foreach ($newPhotos as $photo) {
            $media[] = ['type' => 'image', 'path' => $this->storeFile($photo, 'image')];
        }

        if ($newVideo) {
            $media[] = ['type' => 'video', 'path' => $this->storeFile($newVideo, 'video')];
        }

        $review->fill([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);
        $review->media = $media ?: null;
        $review->save();

        foreach ($filesToDelete as $path) {
            Review::deleteMediaFile($path);
        }

        return redirect(route('product.detail', ['id' => $product->id]).'#tab-review')
            ->with('review_success', 'Terima kasih! Ulasan kamu berhasil disimpan.');
    }

    /**
     * Simpan file langsung ke public/uploads/reviews (tanpa perlu `storage:link`)
     * dan kembalikan path yang bisa dipakai di <img>/<video>.
     */
    private function storeFile(UploadedFile $file, string $type): string
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
            'video/quicktime' => 'mov',
        ];

        $extension = $extensions[$file->getMimeType()] ?? ($type === 'video' ? 'mp4' : 'jpg');
        $name = Str::uuid()->toString().'.'.$extension;

        $file->move(public_path('uploads/reviews'), $name);

        return '/uploads/reviews/'.$name;
    }
}