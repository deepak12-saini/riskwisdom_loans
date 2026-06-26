<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminEnquiryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GuideController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::view('/thank-you', 'thank-you')->name('thank-you');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.submit');

Route::view('/home-loans', 'pages.home-loans')->name('pages.home-loans');
Route::view('/refinance', 'pages.refinance')->name('pages.refinance');
Route::view('/investment-property-loans', 'pages.investment-property-loans')->name('pages.investment');
Route::view('/first-home-buyer', 'pages.first-home-buyer')->name('pages.first-home-buyer');
Route::view('/commercial-finance', 'pages.commercial-finance')->name('pages.commercial');

Route::view('/tools/borrowing-power', 'tools.borrowing-power')->name('tools.borrowing-power');
Route::view('/tools/repayment-calculator', 'tools.repayment-calculator')->name('tools.repayment-calculator');

Route::get('/guides', [GuideController::class, 'index'])->name('guides.index');
Route::get('/guides/{slug}', [GuideController::class, 'show'])->name('guides.show');

Route::view('/privacy-policy', 'pages.privacy-policy')->name('pages.privacy');
Route::view('/credit-guide', 'pages.credit-guide')->name('pages.credit-guide');
Route::view('/partners', 'pages.partners')->name('pages.partners');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/enquiries', [AdminEnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('/enquiries/export', [AdminEnquiryController::class, 'export'])->name('enquiries.export');
});

Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => route('home'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'weekly', 'priority' => '1.0'],
        ['loc' => route('pages.home-loans'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['loc' => route('pages.refinance'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['loc' => route('pages.investment'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['loc' => route('pages.first-home-buyer'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['loc' => route('pages.commercial'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => route('tools.borrowing-power'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => route('tools.repayment-calculator'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => route('guides.index'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.8'],
        ['loc' => route('pages.partners'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.7'],
        ['loc' => route('pages.privacy'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'yearly', 'priority' => '0.3'],
        ['loc' => route('pages.credit-guide'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'yearly', 'priority' => '0.3'],
        ['loc' => route('thank-you'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'yearly', 'priority' => '0.2'],
    ];

    $guideSlugs = [
        'when-to-refinance-home-loan-australia',
        'first-home-buyer-checklist-australia',
        'fixed-vs-variable-home-loans-australia',
        'how-much-can-i-borrow-australia',
        'refinance-readiness-checklist',
        'investment-property-loan-basics-australia',
    ];

    foreach ($guideSlugs as $slug) {
        $urls[] = [
            'loc' => route('guides.show', $slug),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.7',
        ];
    }

    return response()
        ->view('sitemap', compact('urls'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');
