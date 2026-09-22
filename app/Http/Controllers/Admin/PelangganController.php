<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PelangganController extends Controller
{
    public function index(): View
    {
        $customers = User::where('role', 'user')
            ->withCount('orders')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (User $u) => $this->mapCustomer($u))
            ->values();

        return view('admin.pelanggan', [
            'customersData' => $customers,
        ]);
    }

    public function toggleStatus(User $user): JsonResponse
    {
        abort_if($user->role === 'admin', 403);

        $user->update(['is_active' => ! $user->is_active]);

        return response()->json([
            'message' => $user->is_active
                ? 'Pelanggan berhasil diaktifkan.'
                : 'Pelanggan berhasil dinonaktifkan.',
            'customer' => $this->mapCustomer($user->fresh()->loadCount('orders')),
        ]);
    }

    private function mapCustomer(User $u): array
    {
        return [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'phone' => $u->phone,
            'joined' => $u->created_at->format('Y-m-d'),
            'orders' => $u->orders_count,
            'status' => $u->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }
}