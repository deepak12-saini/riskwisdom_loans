<?php

use App\Mail\ContactEnquiryMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'first_name' => ['required', 'string', 'max:120'],
        'last_name' => ['required', 'string', 'max:120'],
        'phone' => ['required', 'string', 'max:50'],
        'email' => ['required', 'email', 'max:255'],
        'enquiry' => ['required', 'string', 'max:2000'],
    ]);

    try {
        Mail::to((string) env('CONTACT_TO_ADDRESS', (string) config('mail.from.address')))
            ->send(new ContactEnquiryMail($validated));
    } catch (\Throwable $exception) {
        report($exception);

        return back()
            ->withInput()
            ->withErrors([
                'enquiry' => 'Your message could not be sent right now. Please try again shortly.',
            ]);
    }

    return redirect()
        ->route('home')
        ->with('status', 'Thanks for your enquiry. We have received your message and will get back to you shortly.');
})->name('contact.submit');
