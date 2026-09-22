<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Default info toko, sama seperti yang dipakai di halaman Pengaturan Admin
     * (PengaturanController::TOKO_DEFAULTS) supaya konsisten.
     */
    private const TOKO_DEFAULTS = [
        'email_toko' => 'hello@trendup.com',
        'whatsapp' => '081234567890',
        'alamat_toko' => 'Jl. Kawi No. 10, Malang, Jawa Timur',
    ];

    public function index(): View
    {
        $toko = Setting::getMany(array_keys(self::TOKO_DEFAULTS), self::TOKO_DEFAULTS);

        return view('site.contact', [
            'toko' => $toko,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create($validated);

        return back()->with('contact_success', __('site.contact_success'));
    }
}
