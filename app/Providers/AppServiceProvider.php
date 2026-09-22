<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Badge jumlah pesanan "Menunggu Pembayaran" di sidebar admin —
        // dihitung ulang tiap request, jadi selalu sesuai data asli di database.
        View::composer('admin.*', function ($view): void {
            $view->with('pendingOrdersBadge', Order::where('status', 'pending')->count());
        });
    }
}