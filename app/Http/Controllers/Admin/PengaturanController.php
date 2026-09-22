<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    private const TOKO_DEFAULTS = [
        'nama_toko' => 'TRENDUP',
        'tagline' => 'Upgrade Your Style.',
        'email_toko' => 'hello@trendup.com',
        'whatsapp' => '081234567890',
        'alamat_toko' => 'Jl. Kawi No. 10, Malang, Jawa Timur',
        'logo' => null,
    ];

    private const PENGIRIMAN_DEFAULTS = [
        'ongkir_default' => 15000,
        'min_gratis_ongkir' => 150000,
        'estimasi_pengiriman' => '1-3 hari kerja',
    ];

    private const NOTIFIKASI_DEFAULTS = [
        'notif_pesanan_baru' => true,
        'notif_stok_menipis' => true,
        'notif_pelanggan_baru' => false,
    ];

    public function index(): View
    {
        return view('admin.pengaturan', [
            'toko' => $this->getToko(),
            'pengiriman' => $this->getPengiriman(),
            'notifikasi' => $this->getNotifikasi(),
            'bankAccountsData' => BankAccount::orderBy('id')->get(),
        ]);
    }

    public function updateToko(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'email_toko' => ['required', 'email', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'alamat_toko' => ['required', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $old = Setting::get('logo');
            if ($old) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $old));
            }
            $validated['logo'] = '/storage/'.$request->file('logo')->store('toko', 'public');
        } else {
            unset($validated['logo']);
        }

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return response()->json([
            'message' => 'Info toko berhasil disimpan.',
            'toko' => $this->getToko(),
        ]);
    }

    public function updatePengiriman(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ongkir_default' => ['required', 'integer', 'min:0'],
            'min_gratis_ongkir' => ['required', 'integer', 'min:0'],
            'estimasi_pengiriman' => ['required', 'string', 'max:255'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return response()->json([
            'message' => 'Pengaturan pengiriman berhasil disimpan.',
            'pengiriman' => $this->getPengiriman(),
        ]);
    }

    public function updateNotifikasi(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'notif_pesanan_baru' => ['required', 'boolean'],
            'notif_stok_menipis' => ['required', 'boolean'],
            'notif_pelanggan_baru' => ['required', 'boolean'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ? '1' : '0');
        }

        return response()->json([
            'message' => 'Pengaturan notifikasi berhasil disimpan.',
            'notifikasi' => $this->getNotifikasi(),
        ]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password saat ini yang kamu masukkan salah.'],
            ]);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return response()->json(['message' => 'Password berhasil diubah.']);
    }

    public function storeBank(Request $request): JsonResponse
    {
        $bank = BankAccount::create($this->validatedBank($request));

        return response()->json([
            'message' => 'Rekening bank berhasil ditambahkan.',
            'bank' => $bank,
        ]);
    }

    public function updateBank(Request $request, BankAccount $bank): JsonResponse
    {
        $bank->update($this->validatedBank($request));

        return response()->json([
            'message' => 'Rekening bank berhasil diperbarui.',
            'bank' => $bank->fresh(),
        ]);
    }

    public function destroyBank(BankAccount $bank): JsonResponse
    {
        $bank->delete();

        return response()->json(['message' => 'Rekening bank berhasil dihapus.']);
    }

    private function validatedBank(Request $request): array
    {
        return $request->validate([
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
        ]);
    }

    private function getToko(): array
    {
        $data = Setting::getMany(array_keys(self::TOKO_DEFAULTS), self::TOKO_DEFAULTS);
        $data['logo'] = $data['logo'] ?: asset('images/logo-toko.png');

        return $data;
    }

    private function getPengiriman(): array
    {
        $data = Setting::getMany(array_keys(self::PENGIRIMAN_DEFAULTS), self::PENGIRIMAN_DEFAULTS);
        $data['ongkir_default'] = (int) $data['ongkir_default'];
        $data['min_gratis_ongkir'] = (int) $data['min_gratis_ongkir'];

        return $data;
    }

    private function getNotifikasi(): array
    {
        $data = Setting::getMany(array_keys(self::NOTIFIKASI_DEFAULTS), self::NOTIFIKASI_DEFAULTS);

        return array_map(fn ($v) => filter_var($v, FILTER_VALIDATE_BOOLEAN), $data);
    }
}