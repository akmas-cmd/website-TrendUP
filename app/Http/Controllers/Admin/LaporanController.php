<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\SalesReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('order')->get();

        $savedReports = SalesReport::orderByDesc('created_at')->get()
            ->map(fn (SalesReport $r) => $this->mapReport($r))
            ->values();

        return view('admin.laporan', [
            'transactionsData' => $this->buildTransactions()->values(),
            'categoriesData' => $categories->map(fn (Category $c) => $this->categoryLabel($c))->values(),
            'savedReportsData' => $savedReports,
        ]);
    }

    /**
     * Simpan hasil "Buat Laporan" (rentang tanggal + kategori) ke riwayat.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'category' => ['nullable', 'string'],
        ]);

        $category = $validated['category'] ?? null;

        $transactions = $this->buildTransactions()->filter(function (array $t) use ($validated, $category) {
            $inRange = $t['date'] >= $validated['start_date'] && $t['date'] <= $validated['end_date'];
            $matchCat = ! $category || $t['cat'] === $category;

            return $inRange && $matchCat;
        });

        $report = SalesReport::create([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'category' => $category,
            'revenue' => $transactions->sum('total'),
            'orders_count' => $transactions->count(),
        ]);

        return response()->json([
            'message' => 'Laporan berhasil dibuat.',
            'report' => $this->mapReport($report),
        ]);
    }

    public function destroy(SalesReport $report): JsonResponse
    {
        $report->delete();

        return response()->json(['message' => 'Laporan berhasil dihapus.']);
    }

    /**
     * Ambil semua order "completed" dan ubah jadi baris transaksi
     * (format sama seperti allTransactions yang dulu di-hardcode di JS).
     */
    private function buildTransactions(): Collection
    {
        return Order::with('items.product.category')
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Order $o) => $this->mapTransaction($o));
    }

    private function mapTransaction(Order $o): array
    {
        // Satu order bisa berisi produk dari beberapa kategori berbeda,
        // jadi kategori yang dipakai untuk filter/tag adalah kategori
        // dengan jumlah item terbanyak di order tsb.
        $catCounts = [];
        foreach ($o->items as $item) {
            $label = ($item->product && $item->product->category)
                ? $this->categoryLabel($item->product->category)
                : 'Lainnya';
            $catCounts[$label] = ($catCounts[$label] ?? 0) + $item->qty;
        }
        arsort($catCounts);
        $cat = array_key_first($catCounts) ?? 'Lainnya';

        $items = $o->items->map(fn ($item) => $item->qty > 1
            ? "{$item->name} x{$item->qty}"
            : $item->name
        )->implode(', ');

        return [
            'id' => $o->order_number,
            'date' => $o->created_at->format('Y-m-d'),
            'cat' => $cat,
            'items' => $items,
            'total' => $o->total,
        ];
    }

    private function mapReport(SalesReport $r): array
    {
        return [
            'id' => $r->id,
            'period' => $r->start_date->translatedFormat('j M').' - '.$r->end_date->translatedFormat('j M Y'),
            'cat' => $r->category ?: 'Semua Kategori',
            'revenue' => $r->revenue,
            'orders' => $r->orders_count,
            'createdAt' => $r->created_at->translatedFormat('j M Y'),
        ];
    }

    private function categoryLabel(Category $c): string
    {
        return Str::title(str_replace('-', ' ', $c->key));
    }
}