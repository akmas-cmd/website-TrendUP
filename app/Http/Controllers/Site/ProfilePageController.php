<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfilePageController extends Controller
{
    /**
     * Tampilkan halaman profil: data akun + riwayat pesanan milik user yang login.
     * Khusus role "user" — admin sudah punya profilnya sendiri di menu Pengaturan Admin.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.pengaturan');
        }

        $orders = $user->orders()
            ->withCount('items')
            ->latest()
            ->get();

        return view('site.profile', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    /**
     * Update nama, email, dan nomor HP.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_if($user->isAdmin(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('status', 'profile-updated');
    }

    /**
     * Ubah password. Wajib memasukkan password lama untuk verifikasi.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_if($user->isAdmin(), 403);

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama yang kamu masukkan salah.',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
