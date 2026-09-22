<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackingController extends Controller
{
    public function index(): View
    {
        return view('site.tracking');
    }

    /**
     * Endpoint pencarian pesanan (dipanggil lewat AJAX dari halaman tracking).
     * Sengaja dibuat publik (tidak wajib login) supaya siapa pun yang tahu
     * nomor pesanannya bisa melacak — sama seperti tracking resi kurir pada
     * umumnya.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'order_number' => ['required', 'string'],
        ]);

        $orderNumber = strtoupper(trim($request->query('order_number')));

        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->first();

        if (! $order) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'order' => [
                'order_number' => $order->order_number,
                'date' => $order->created_at->translatedFormat('d F Y'),
                'payment' => $this->paymentCode($order->payment_method),
                'status' => $order->status,
                'name' => $order->name,
                'phone' => $order->phone,
                'address' => $order->address.', '.$order->city.' '.$order->zip,
                'items' => $order->items->map(fn ($item) => [
                    'name' => $item->name,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'icon' => $item->icon ?? 'fa-solid fa-box',
                ]),
            ],
        ]);
    }

    /**
     * Data checkout menyimpan payment_method sebagai teks ("Transfer Bank",
     * "E-Wallet", "COD"). Halaman tracking butuh kode kanonis supaya labelnya
     * ikut berubah sesuai bahasa aktif (lihat objek I18N.payment di Blade).
     */
    private function paymentCode(string $method): string
    {
        return match ($method) {
            'Transfer Bank' => 'bank_transfer',
            'E-Wallet' => 'ewallet',
            'COD' => 'cod',
            default => 'bank_transfer',
        };
    }
}