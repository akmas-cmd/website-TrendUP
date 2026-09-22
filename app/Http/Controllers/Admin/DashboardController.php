<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Sama seperti STATUS_MAP di PesananController — kode di DB vs label Indonesia.
     */
    private const STATUS_MAP = [
        'pending' => 'Menunggu Pembayaran',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    public function index(): View
    {
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $endOfLastMonth = $startOfThisMonth->copy()->subSecond();

        $totalProducts = Product::count();
        $newProductsThisWeek = Product::where('created_at', '>=', $now->copy()->subDays(7))->count();

        $totalOrders = Order::count();
        $ordersThisMonth = Order::where('created_at', '>=', $startOfThisMonth)->count();
        $ordersLastMonth = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        $pendingOrders = Order::where('status', 'pending')->count();

        $revenueThisMonth = (int) Order::where('status', 'completed')
            ->where('created_at', '>=', $startOfThisMonth)
            ->sum('total');
        $revenueLastMonth = (int) Order::where('status', 'completed')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total');

        return view('admin.dashboard', [
            'statProduk' => [
                'total' => $totalProducts,
                'trendText' => $newProductsThisWeek.' produk baru minggu ini',
            ],
            'statPesanan' => [
                'total' => $totalOrders,
                'trend' => $this->trendLabel($ordersThisMonth, $ordersLastMonth),
            ],
            'statPending' => [
                'total' => $pendingOrders,
            ],
            'statRevenue' => [
                'formatted' => $this->compactRupiah($revenueThisMonth),
                'trend' => $this->trendLabel($revenueThisMonth, $revenueLastMonth),
            ],
            'salesChartData' => $this->buildSalesChart($now),
            'lowStockData' => $this->buildLowStock(),
            'recentOrdersData' => $this->buildRecentOrders(),
            'topProductsData' => $this->buildTopProducts(),
        ]);
    }

    /**
     * Bandingkan angka bulan ini vs bulan lalu, hasilkan arah (up/down) + teks persentase.
     */
    private function trendLabel(int $current, int $previous): array
    {
        if ($previous === 0) {
            return [
                'direction' => $current > 0 ? 'up' : 'flat',
                'text' => $current > 0 ? 'Baru mulai tercatat bulan ini' : 'Belum ada data bulan lalu',
            ];
        }

        $percent = round((($current - $previous) / $previous) * 100);

        return [
            'direction' => $percent >= 0 ? 'up' : 'down',
            'text' => abs($percent).'% dari bulan lalu',
        ];
    }

    private function buildSalesChart(Carbon $now): Collection
    {
        $days = collect(range(6, 0))->map(fn ($i) => $now->copy()->subDays($i)->startOfDay());

        $totalsByDate = Order::where('status', 'completed')
            ->where('created_at', '>=', $days->first())
            ->get()
            ->groupBy(fn (Order $o) => $o->created_at->format('Y-m-d'))
            ->map(fn ($group) => (int) $group->sum('total'));

        return $days->map(fn (Carbon $d) => [
            'day' => $d->translatedFormat('D'),
            'value' => $totalsByDate[$d->format('Y-m-d')] ?? 0,
        ])->values();
    }

    private function buildLowStock(): Collection
    {
        return Product::where('stock', '<', 5)
            ->orderBy('stock')
            ->limit(4)
            ->get(['name', 'stock'])
            ->map(fn (Product $p) => [
                'name' => $p->name,
                'stock' => $p->stock,
            ])->values();
    }

    private function buildRecentOrders(): Collection
    {
        return Order::latest()->limit(5)->get()
            ->map(fn (Order $o) => [
                'id' => $o->order_number,
                'customer' => $o->name,
                'date' => $o->created_at->translatedFormat('j M Y'),
                'total' => $o->total,
                'status' => self::STATUS_MAP[$o->status] ?? $o->status,
            ])->values();
    }

    private function buildTopProducts(): Collection
    {
        return OrderItem::query()
            ->whereNotNull('product_id')
            ->selectRaw('product_id, SUM(qty) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(4)
            ->with('product.category')
            ->get()
            ->filter(fn ($row) => $row->product)
            ->map(fn ($row) => [
                'name' => $row->product->name,
                'cat' => $row->product->category ? $this->categoryLabel($row->product->category) : '-',
                'sold' => (int) $row->total_sold,
                'icon' => $row->product->icon ?: 'fa-solid fa-box',
            ])->values();
    }

    private function categoryLabel(Category $c): string
    {
        return Str::title(str_replace('-', ' ', $c->key));
    }

    /**
     * Format singkat ala "Rp 42,8jt" untuk angka >= 1 juta, selain itu format normal.
     */
    private function compactRupiah(int $value): string
    {
        if ($value >= 1_000_000) {
            $formatted = rtrim(rtrim(number_format($value / 1_000_000, 1, ',', '.'), '0'), ',');

            return 'Rp '.$formatted.'jt';
        }

        return 'Rp '.number_format($value, 0, ',', '.');
    }
}