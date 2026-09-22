<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'media',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'media' => 'array',
        ];
    }

    protected static function booted(): void
    {
        // Saat ulasan dihapus, file foto/video-nya ikut dihapus dari folder public/uploads/reviews.
        static::deleting(function (Review $review) {
            foreach ($review->media ?? [] as $item) {
                self::deleteMediaFile($item['path'] ?? null);
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Daftar path foto (array of string). */
    public function getPhotoPathsAttribute(): array
    {
        return collect($this->media ?? [])
            ->where('type', 'image')
            ->pluck('path')
            ->values()
            ->all();
    }

    /** Path video (maksimal 1 per ulasan) atau null. */
    public function getVideoPathAttribute(): ?string
    {
        $video = collect($this->media ?? [])->firstWhere('type', 'video');

        return $video['path'] ?? null;
    }

    /** Hapus 1 file hasil upload ulasan (hanya di dalam public/uploads/reviews). */
    public static function deleteMediaFile(?string $path): void
    {
        if (! $path || ! str_starts_with($path, '/uploads/reviews/') || str_contains($path, '..')) {
            return;
        }

        $file = public_path(ltrim($path, '/'));

        if (is_file($file)) {
            @unlink($file);
        }
    }
}