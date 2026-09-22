<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PesananController extends Controller
{
    /**
     * Status disimpan di database sebagai kode (pending, processing, dst),
     * tapi tampilan admin pakai label Indonesia — jadi dipetakan dua arah
     * di sini.
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
        $orders = Order::with('items')->orderByDesc('created_at')->get()
            ->map(fn (Order $o) => $this->mapOrder($o))
            ->values();

        return view('admin.pesanan', [
            'ordersData' => $orders,
        ]);
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_values(self::STATUS_MAP))],
        ]);

        $code = array_search($validated['status'], self::STATUS_MAP, true);

        $order->update(['status' => $code]);

        return response()->json([
            'message' => 'Status pesanan berhasil diperbarui.',
            'order' => $this->mapOrder($order->fresh('items')),
        ]);
    }

    private function mapOrder(Order $o): array
    {
        return [
            'id' => $o->order_number,
            'customer' => $o->name,
            'phone' => $o->phone,
            'address' => $o->address.', '.$o->city.' '.$o->zip,
            'date' => $o->created_at->translatedFormat('j M Y'),
            'payment' => $o->payment_method,
            'total' => $o->total,
            'status' => self::STATUS_MAP[$o->status] ?? $o->status,
            'items' => $o->items->map(fn ($item) => [
                'name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
            ])->values(),
        ];
    }
}