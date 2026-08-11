<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\AdminClientDocumentController;
use App\Http\Controllers\AdminEnquiryController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\BorrowingPowerController;
use App\Http\Controllers\CalendlyWebhookController;
use App\Http\Controllers\ChatLeadController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ConversionLandingController;
use App\Http\Controllers\AnnatureWebhookController;
use App\Http\Controllers\DocuSignWebhookController;
use App\Http\Controllers\GuideDownloadController;
use App\Http\Controllers\RateReviewController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StampDutyController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\NewsletterSignupController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::view('/thank-you', 'thank-you')->name('thank-you');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.submit');

Route::view('/book', 'pages.book')->name('book');
Route::view('/about', 'pages.about')->name('pages.about');

Route::get('/enquire', [ConversionLandingController::class, 'show'])->name('enquire.show');
Route::post('/enquire', [ConversionLandingController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('enquire.submit');
Route::get('/enquire/{campaign}', [ConversionLandingController::class, 'show'])
    ->where('campaign', 'refinance|home-loans|first-home-buyer|investment|commercial')
    ->name('enquire.campaign');
Route::post('/enquire/{campaign}', [ConversionLandingController::class, 'store'])
    ->where('campaign', 'refinance|home-loans|first-home-buyer|investment|commercial')
    ->middleware('throttle:5,1')
    ->name('enquire.campaign.submit');

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
Route::get('/download-guides/{slug}', [GuideDownloadController::class, 'show'])->name('guides.download.show');
Route::post('/download-guides/{slug}', [GuideDownloadController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('guides.download.store');
Route::post('/newsletter-signup', NewsletterSignupController::class)
    ->middleware('throttle:5,1')
    ->name('newsletter.signup');
Route::post('/after-hours-chat', ChatLeadController::class)
    ->middleware('throttle:5,1')
    ->name('chat.capture');

Route::view('/privacy-policy', 'pages.privacy-policy')->name('pages.privacy');
Route::view('/credit-guide', 'pages.credit-guide')->name('pages.credit-guide');
Route::view('/partners', 'pages.partners')->name('pages.partners');

Route::post('/webhooks/docusign', DocuSignWebhookController::class)->name('webhooks.docusign');
Route::post('/webhooks/annature', AnnatureWebhookController::class)->name('webhooks.annature');
Route::post('/webhooks/calendly', CalendlyWebhookController::class)->name('webhooks.calendly');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/enquiries', [AdminEnquiryController::class, 'index'])
        ->middleware('admin.can:enquiries.view')
        ->name('enquiries.index');
    Route::get('/enquiries/export', [AdminEnquiryController::class, 'export'])
        ->middleware('admin.can:enquiries.export')
        ->name('enquiries.export');
    Route::get('/enquiries/{enquiry}', [AdminEnquiryController::class, 'show'])
        ->middleware('admin.can:enquiries.view')
        ->name('enquiries.show');
    Route::delete('/enquiries/{enquiry}', [AdminEnquiryController::class, 'destroy'])
        ->middleware('admin.can:enquiries.delete')
        ->name('enquiries.destroy');
    Route::post('/enquiries/{enquiry}/convert', [AdminEnquiryController::class, 'convert'])
        ->middleware('admin.can:enquiries.convert')
        ->name('enquiries.convert');

    Route::get('/clients', [AdminClientController::class, 'index'])
        ->middleware('admin.can:clients.view')
        ->name('clients.index');
    Route::get('/clients/create', [AdminClientController::class, 'create'])
        ->middleware('admin.can:clients.create')
        ->name('clients.create');
    Route::post('/clients', [AdminClientController::class, 'store'])
        ->middleware('admin.can:clients.create')
        ->name('clients.store');
    Route::get('/clients/{client}', [AdminClientController::class, 'show'])
        ->middleware('admin.can:clients.view')
        ->name('clients.show');
    Route::get('/clients/{client}/edit', [AdminClientController::class, 'edit'])
        ->middleware('admin.can:clients.update')
        ->name('clients.edit');
    Route::put('/clients/{client}', [AdminClientController::class, 'update'])
        ->middleware('admin.can:clients.update')
        ->name('clients.update');
    Route::patch('/clients/{client}/archive', [AdminClientController::class, 'archive'])
        ->middleware('admin.can:clients.archive')
        ->name('clients.archive');
    Route::patch('/clients/{client}/restore', [AdminClientController::class, 'restore'])
        ->middleware('admin.can:clients.archive')
        ->name('clients.restore');

    Route::get('/tasks', [AdminTaskController::class, 'index'])
        ->middleware('admin.can:tasks.view')
        ->name('tasks.index');
    Route::post('/clients/{client}/tasks', [AdminTaskController::class, 'store'])
        ->middleware('admin.can:tasks.manage')
        ->name('clients.tasks.store');
    Route::put('/clients/{client}/tasks/{task}', [AdminTaskController::class, 'update'])
        ->middleware('admin.can:tasks.manage')
        ->name('clients.tasks.update');
    Route::patch('/clients/{client}/tasks/{task}/close', [AdminTaskController::class, 'close'])
        ->middleware('admin.can:tasks.manage')
        ->name('clients.tasks.close');
    Route::delete('/clients/{client}/tasks/{task}', [AdminTaskController::class, 'destroy'])
        ->middleware('admin.can:tasks.delete')
        ->name('clients.tasks.destroy');

    Route::post('/clients/{client}/documents', [AdminClientDocumentController::class, 'store'])
        ->middleware('admin.can:documents.manage')
        ->name('clients.documents.store');
    Route::post('/clients/{client}/documents/{document}/sync', [AdminClientDocumentController::class, 'sync'])
        ->middleware('admin.can:documents.manage')
        ->name('clients.documents.sync');
    Route::get('/clients/{client}/documents/{document}/download', [AdminClientDocumentController::class, 'download'])
        ->middleware('admin.can:documents.view')
        ->name('clients.documents.download');
    Route::delete('/clients/{client}/documents/{document}', [AdminClientDocumentController::class, 'destroy'])
        ->middleware('admin.can:documents.delete')
        ->name('clients.documents.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])
        ->middleware('admin.can:users.manage')
        ->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])
        ->middleware('admin.can:users.manage')
        ->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])
        ->middleware('admin.can:users.manage')
        ->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])
        ->middleware('admin.can:users.manage')
        ->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])
        ->middleware('admin.can:users.manage')
        ->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
        ->middleware('admin.can:users.manage')
        ->name('users.destroy');
});

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
