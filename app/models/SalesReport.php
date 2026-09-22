<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'category',
        'revenue',
        'orders_count',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'revenue' => 'integer',
            'orders_count' => 'integer',
        ];
    }
}