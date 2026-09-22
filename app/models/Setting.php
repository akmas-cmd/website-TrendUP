<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $row = static::where('key', $key)->first();

        return $row ? $row->value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Ambil beberapa key sekaligus, isi default kalau belum ada di DB.
     */
    public static function getMany(array $keys, array $defaults = []): array
    {
        $rows = static::whereIn('key', $keys)->pluck('value', 'key');

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $rows->has($key) ? $rows[$key] : ($defaults[$key] ?? null);
        }

        return $result;
    }
}