<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\AdminClientDocumentController;
use App\Http\Controllers\AdminEnquiryController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\BorrowingPowerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocuSignWebhookController;
use App\Http\Controllers\RateReviewController;
use App\Http\Controllers\StampDutyController;
use App\Http\Controllers\GuideController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::view('/thank-you', 'thank-you')->name('thank-you');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.submit');

Route::view('/book', 'pages.book')->name('book');

Route::view('/rate-review', 'pages.rate-review')->name('rate-review');
Route::post('/rate-review', [RateReviewController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('rate-review.submit');

Route::view('/home-loans', 'pages.home-loans')->name('pages.home-loans');
Route::view('/refinance', 'pages.refinance')->name('pages.refinance');
Route::view('/refinance-home-loan-rates', 'pages.refinance-home-loan-rates')->name('pages.refinance-rates');
Route::view('/refinance-home-loan-calculator', 'pages.refinance-home-loan-calculator')->name('pages.refinance-calculator');
Route::view('/refinance-cashback-offers', 'pages.refinance-cashback-offers')->name('pages.refinance-cashback');
Route::view('/investment-property-loans', 'pages.investment-property-loans')->name('pages.investment');
Route::view('/first-home-buyer', 'pages.first-home-buyer')->name('pages.first-home-buyer');
Route::view('/commercial-finance', 'pages.commercial-finance')->name('pages.commercial');

Route::view('/tools/borrowing-power', 'tools.borrowing-power')->name('tools.borrowing-power');
Route::post('/tools/borrowing-power', [BorrowingPowerController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('tools.borrowing-power.submit');
Route::view('/tools/repayment-calculator', 'tools.repayment-calculator')->name('tools.repayment-calculator');
Route::view('/tools/stamp-duty', 'tools.stamp-duty')->name('tools.stamp-duty');
Route::post('/tools/stamp-duty', [StampDutyController::class, 'calculate'])
    ->middleware('throttle:20,1')
    ->name('tools.stamp-duty.calculate');

Route::get('/guides', [GuideController::class, 'index'])->name('guides.index');
Route::get('/guides/{slug}', [GuideController::class, 'show'])->name('guides.show');

Route::view('/privacy-policy', 'pages.privacy-policy')->name('pages.privacy');
Route::view('/credit-guide', 'pages.credit-guide')->name('pages.credit-guide');
Route::view('/partners', 'pages.partners')->name('pages.partners');

Route::post('/webhooks/docusign', DocuSignWebhookController::class)->name('webhooks.docusign');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/enquiries', [AdminEnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('/enquiries/export', [AdminEnquiryController::class, 'export'])->name('enquiries.export');
    Route::post('/enquiries/{enquiry}/convert', [AdminEnquiryController::class, 'convert'])->name('enquiries.convert');

    Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [AdminClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [AdminClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [AdminClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/edit', [AdminClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [AdminClientController::class, 'update'])->name('clients.update');
    Route::patch('/clients/{client}/archive', [AdminClientController::class, 'archive'])->name('clients.archive');
    Route::patch('/clients/{client}/restore', [AdminClientController::class, 'restore'])->name('clients.restore');

    Route::get('/tasks', [AdminTaskController::class, 'index'])->name('tasks.index');
    Route::post('/clients/{client}/tasks', [AdminTaskController::class, 'store'])->name('clients.tasks.store');
    Route::put('/clients/{client}/tasks/{task}', [AdminTaskController::class, 'update'])->name('clients.tasks.update');
    Route::patch('/clients/{client}/tasks/{task}/close', [AdminTaskController::class, 'close'])->name('clients.tasks.close');
    Route::delete('/clients/{client}/tasks/{task}', [AdminTaskController::class, 'destroy'])->name('clients.tasks.destroy');

    Route::post('/clients/{client}/documents', [AdminClientDocumentController::class, 'store'])->name('clients.documents.store');
    Route::post('/clients/{client}/documents/{document}/sync', [AdminClientDocumentController::class, 'sync'])->name('clients.documents.sync');
    Route::get('/clients/{client}/documents/{document}/download', [AdminClientDocumentController::class, 'download'])->name('clients.documents.download');
    Route::delete('/clients/{client}/documents/{document}', [AdminClientDocumentController::class, 'destroy'])->name('clients.documents.destroy');
});

Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => route('home'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'weekly', 'priority' => '1.0'],
        ['loc' => route('book'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['loc' => route('rate-review'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['loc' => route('pages.home-loans'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['loc' => route('pages.refinance'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['loc' => route('pages.refinance-rates'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.85'],
        ['loc' => route('pages.refinance-calculator'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.85'],
        ['loc' => route('pages.refinance-cashback'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.85'],
        ['loc' => route('pages.investment'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['loc' => route('pages.first-home-buyer'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['loc' => route('pages.commercial'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => route('tools.borrowing-power'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => route('tools.repayment-calculator'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ['loc' => route('tools.stamp-duty'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
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
