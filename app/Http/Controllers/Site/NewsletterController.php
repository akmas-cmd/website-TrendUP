<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        NewsletterSubscriber::firstOrCreate(['email' => $validated['email']]);

        return back()->with('newsletter_success', 'Terima kasih sudah berlangganan!');
    }
}