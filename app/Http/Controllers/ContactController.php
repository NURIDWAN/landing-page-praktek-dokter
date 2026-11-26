<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\NewContactMessageMail;
use App\Models\ContactMessage;
use App\Services\PracticeDataService;
use App\Services\StructuredDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(PracticeDataService $practiceData, StructuredDataService $structuredData): View
    {
        return view('pages.contact', [
            'openingHours' => $practiceData->openingHours(),
            'breadcrumbJsonLd' => $structuredData->forBreadcrumbs([
                ['name' => 'Beranda', 'url' => route('home')],
                ['name' => 'Kontak', 'url' => route('contact.index')],
            ]),
        ]);
    }

    public function store(StoreContactMessageRequest $request, PracticeDataService $practiceData): RedirectResponse
    {
        $validated = $request->validated();

        $contactMessage = ContactMessage::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'ip_hash' => hash_hmac('sha256', (string) $request->ip(), (string) config('app.key')),
            'privacy_agreed_at' => now(),
        ]);

        $staffEmail = $practiceData->settings()->email;
        if (filled($staffEmail)) {
            Mail::to($staffEmail)->queue(new NewContactMessageMail($contactMessage));
        }

        return back()->with(
            'contact_success',
            'Terima kasih, '.$validated['name'].'. Pesan Anda telah tersimpan dan akan ditindaklanjuti oleh staf praktek.'
        );
    }
}
