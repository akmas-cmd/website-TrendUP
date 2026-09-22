<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'name',
        'phone',
        'email',
        'address',
        'city',
        'zip',
        'payment_method',
        'subtotal',
        'shipping_fee',
        'total',
        'status',
    ];

    /**
     * Supaya route model binding ({order}) otomatis cari berdasarkan
     * order_number, bukan id — dipakai di halaman order-success & tracking.
     */
    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}