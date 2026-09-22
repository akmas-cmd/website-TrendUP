<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    private function shippingFee(): int
    {
        return (int) Setting::get('ongkir_default', 15000);
    }

    public function index(Request $request): View
    {
        return view('site.checkout', [
            'buyer' => $request->user(),
            'shippingFee' => $this->shippingFee(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'phone' => ['required', 'string', 'min:10', 'max:14'],
            'email' => ['required', 'email'],
            'address' => ['required', 'string', 'min:10'],
            'city' => ['required', 'string', 'min:2'],
            'zip' => ['required', 'string', 'min:4'],
            'payment_method' => ['required', 'string', 'in:Transfer Bank,E-Wallet,COD'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer'],
            'items.*.name' => ['required', 'string'],
            'items.*.price' => ['required', 'integer', 'min:0'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.icon' => ['nullable', 'string'],
            'items.*.img' => ['nullable', 'string'],
        ]);

        $subtotal = collect($validated['items'])->sum(fn ($i) => $i['price'] * $i['qty']);
        $shippingFee = $this->shippingFee();
        $total = $subtotal + $shippingFee;

        $order = DB::transaction(function () use ($request, $validated, $subtotal, $shippingFee, $total) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => $this->generateOrderNumber(),
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'zip' => $validated['zip'],
                'payment_method' => $validated['payment_method'],
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'icon' => $item['icon'] ?? null,
                    'image' => $item['img'] ?? null,
                ]);
            }

            return $order;
        });

        return response()->json([
            'redirect' => route('checkout.success', $order->order_number),
        ]);
    }

    public function success(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return view('site.order-success', [
            'order' => $order->load('items'),
        ]);
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'TRD-'.now()->format('Ymd').'-'.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}