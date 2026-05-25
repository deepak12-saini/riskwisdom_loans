<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::post('/contact', function (Request $request) {
    $request->validate([
        'first_name' => ['required', 'string', 'max:120'],
        'last_name' => ['required', 'string', 'max:120'],
        'phone' => ['required', 'string', 'max:50'],
        'email' => ['required', 'email', 'max:255'],
        'enquiry' => ['required', 'string', 'max:2000'],
    ]);

    return redirect()
        ->route('home')
        ->with('status', 'Thanks for your enquiry. This Laravel demo captures the form flow; connect email delivery before launch.');
})->name('contact.submit');
