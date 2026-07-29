<?php

namespace Tests\Feature;

use App\Mail\ContactAutoReplyMail;
use App\Mail\ContactEnquiryMail;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_homepage_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response
            ->assertStatus(200)
            ->assertSee('Riskwisdom Loans')
            ->assertSee('Smarter loan guidance')
            ->assertSee('Get free loan review')
            ->assertSee('Book a call');
    }

    public function test_click_to_call_links_are_present_on_key_pages(): void
    {
        $phoneTel = config('riskwisdom.phone_tel');

        $this->get('/')
            ->assertOk()
            ->assertSee('tel:'.$phoneTel, false)
            ->assertSee('header-mobile-phone', false)
            ->assertSee('sticky-mobile-phone', false)
            ->assertSee('footer-phone', false);

        $this->get(route('book'))
            ->assertOk()
            ->assertSee('tel:'.$phoneTel, false)
            ->assertSee('rw-sticky-cta--call-only', false)
            ->assertSee('sticky-mobile-phone', false);

        $this->get(route('rate-review'))
            ->assertOk()
            ->assertSee('tel:'.$phoneTel, false)
            ->assertSee('rw-sticky-cta--call-only', false);
    }

    public function test_the_book_page_shows_calendly_embed(): void
    {
        $response = $this->get(route('book'));

        $response
            ->assertOk()
            ->assertSee('Book a free 15-minute phone call')
            ->assertSee('hide_gdpr_banner=1', false)
            ->assertSee('rw-calendly-mount', false)
            ->assertSee('assets.calendly.com/assets/external/widget.js', false)
            ->assertSee('initInlineWidget', false)
            ->assertSee('options.branding = false', false);
    }

    public function test_the_homepage_does_not_load_calendly_widget_script(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertDontSee('assets.calendly.com/assets/external/widget.js', false);
    }

    public function test_the_contact_form_accepts_a_valid_enquiry(): void
    {
        Mail::fake();

        $response = $this->post('/contact', [
            'first_name' => 'Kal',
            'last_name' => 'Example',
            'phone' => '0400000000',
            'email' => 'info@riskwisdomloans.com.au',
            'loan_type' => 'home_purchase',
            'timeline' => 'ready_now',
            'state' => 'NSW',
            'enquiry' => 'I would like help with a first home loan.',
        ]);

        $response->assertRedirect(route('thank-you'));

        $this->assertDatabaseHas('enquiries', [
            'email' => 'info@riskwisdomloans.com.au',
            'first_name' => 'Kal',
            'loan_type' => 'home_purchase',
        ]);

        Mail::assertSent(ContactEnquiryMail::class);
        Mail::assertSent(ContactAutoReplyMail::class);
    }

    public function test_contact_form_rejects_obvious_spam_submission(): void
    {
        Mail::fake();

        $response = $this->from(route('home'))
            ->post('/contact', [
                'first_name' => 'DavidrekDS',
                'last_name' => 'DavidrekDS',
                'phone' => '89419874553',
                'email' => 'no.reply.JoanAndersen@gmail.com',
                'loan_type' => 'investment',
                'timeline' => '3_6_months',
                'state' => 'TAS',
                'enquiry' => 'Good morning! I noticed your website while browsing the internet. Contact us on Telegram - https://t.me/FeedbackFormEU',
            ]);

        $response
            ->assertRedirect(route('home').'#contact')
            ->assertSessionHasErrors(['email', 'phone', 'enquiry', 'last_name']);

        $this->assertDatabaseCount('enquiries', 0);
        Mail::assertNothingSent();
    }

    public function test_honeypot_submissions_are_silently_accepted(): void
    {
        Mail::fake();

        $response = $this->post('/contact', [
            '_gotcha' => 'http://spam.test',
            'first_name' => 'Bot',
            'last_name' => 'Spam',
            'phone' => '0400000000',
            'email' => 'spam@example.com',
            'loan_type' => 'other',
            'timeline' => 'researching',
            'state' => 'NSW',
            'enquiry' => 'Spam message',
        ]);

        $response->assertRedirect(route('thank-you'));
        $this->assertDatabaseCount('enquiries', 0);
        Mail::assertNothingSent();
    }

    public function test_the_borrowing_power_page_hides_results_until_lead_is_submitted(): void
    {
        $response = $this->get(route('tools.borrowing-power'));

        $response
            ->assertOk()
            ->assertSee('See your estimated range')
            ->assertSee('Continue to my estimate')
            ->assertDontSee('Your estimated guide range');
    }

    public function test_the_borrowing_power_calculator_stores_a_lead_and_shows_results(): void
    {
        Mail::fake();

        $response = $this->post(route('tools.borrowing-power.submit'), [
            'first_name' => 'Alex',
            'last_name' => 'Borrower',
            'phone' => '0400000000',
            'email' => 'alex@example.com',
            'income' => 120000,
            'expenses' => 3500,
            'deposit' => 80000,
            'rate' => 6.2,
            'term' => 30,
        ]);

        $response
            ->assertRedirect(route('tools.borrowing-power').'#bp-result')
            ->assertSessionHas('borrowing_power_result');

        $this->assertDatabaseHas('enquiries', [
            'lead_type' => 'borrowing_power',
            'email' => 'alex@example.com',
            'first_name' => 'Alex',
            'source' => 'borrowing_power_calculator',
        ]);

        Mail::assertSent(ContactEnquiryMail::class);
        Mail::assertSent(ContactAutoReplyMail::class);

        $followUp = $this->get(route('tools.borrowing-power'));
        $followUp->assertSee('Your guide range');
    }

    public function test_the_borrowing_power_calculator_rejects_honeypot_submissions(): void
    {
        Mail::fake();

        $response = $this->post(route('tools.borrowing-power.submit'), [
            '_gotcha' => 'http://spam.test',
            'first_name' => 'Bot',
            'last_name' => 'Spam',
            'phone' => '0400000000',
            'email' => 'spam@example.com',
            'income' => 120000,
            'expenses' => 3500,
            'deposit' => 80000,
            'rate' => 6.2,
            'term' => 30,
        ]);

        $response->assertRedirect(route('tools.borrowing-power'));
        $this->assertDatabaseCount('enquiries', 0);
        Mail::assertNothingSent();
    }

    public function test_the_borrowing_power_calculator_shows_validation_errors(): void
    {
        $response = $this->from(route('tools.borrowing-power'))
            ->post(route('tools.borrowing-power.submit'), [
                'first_name' => '',
                'last_name' => '',
                'phone' => '',
                'email' => 'not-an-email',
                'income' => 120000,
                'expenses' => 3500,
                'deposit' => 80000,
                'rate' => 6.2,
                'term' => 30,
            ]);

        $response
            ->assertRedirect(route('tools.borrowing-power').'#bp-lead-gate')
            ->assertSessionHasErrors(['first_name', 'email']);

        $this->get(route('tools.borrowing-power'))
            ->assertOk()
            ->assertSee('We could not show your estimate yet')
            ->assertSee('Please enter your first name');
    }

    public function test_the_stamp_duty_page_is_accessible(): void
    {
        $response = $this->get(route('tools.stamp-duty'));

        $response
            ->assertOk()
            ->assertSee('Stamp duty estimator')
            ->assertSee('Calculate stamp duty');
    }

    public function test_the_stamp_duty_calculator_returns_an_estimate(): void
    {
        $response = $this->from(route('tools.stamp-duty'))
            ->post(route('tools.stamp-duty.calculate'), [
                'state' => 'NSW',
                'property_value' => 650000,
                'first_home_buyer' => '1',
            ]);

        $response
            ->assertRedirect(route('tools.stamp-duty').'#sd-result')
            ->assertSessionHas('stamp_duty_result');

        $this->get(route('tools.stamp-duty'))
            ->assertOk()
            ->assertSee('Estimated total government charges');
    }

    public function test_the_rate_review_page_is_accessible(): void
    {
        $response = $this->get(route('rate-review'));

        $response
            ->assertOk()
            ->assertSee('Am I on the right rate?')
            ->assertSee('Request my free rate review')
            ->assertSee(config('riskwisdom.rate_review.callback_promise'));
    }

    public function test_the_rate_review_form_stores_a_lead_and_redirects(): void
    {
        Mail::fake();

        $response = $this->from(route('rate-review'))
            ->post(route('rate-review.submit'), [
                'first_name' => 'Sam',
                'last_name' => 'Reviewer',
                'phone' => '0400111222',
                'email' => 'sam@example.com',
                'current_rate' => 6.49,
                'loan_balance' => 420000,
                'lender' => 'CBA',
            ]);

        $response
            ->assertRedirect(route('thank-you'))
            ->assertSessionHas('lead_type', 'rate_review');

        $this->assertDatabaseHas('enquiries', [
            'lead_type' => 'rate_review',
            'email' => 'sam@example.com',
            'first_name' => 'Sam',
            'source' => 'rate_review_form',
            'timeline' => 'ready_now',
        ]);

        Mail::assertSent(ContactEnquiryMail::class);
        Mail::assertSent(ContactAutoReplyMail::class);
    }

    public function test_the_rate_review_form_rejects_honeypot_submissions(): void
    {
        Mail::fake();

        $response = $this->post(route('rate-review.submit'), [
            '_gotcha' => 'spam',
            'first_name' => 'Bot',
            'last_name' => 'Spam',
            'phone' => '0400000000',
            'email' => 'spam@example.com',
            'current_rate' => 6,
        ]);

        $response->assertRedirect(route('rate-review'));
        $this->assertDatabaseCount('enquiries', 0);
        Mail::assertNothingSent();
    }

    public function test_landing_pages_are_accessible(): void
    {
        $this->get(route('pages.refinance'))->assertOk()->assertSee('Refinance');
        $this->get(route('rate-review'))->assertOk()->assertSee('Am I on the right rate?');
        $this->get(route('tools.borrowing-power'))->assertOk()->assertSee('Borrowing power');
        $this->get(route('tools.stamp-duty'))->assertOk()->assertSee('Stamp duty');
        $this->get(route('guides.index'))->assertOk()->assertSee('Guides');
    }

    public function test_admin_can_log_in_with_database_credentials(): void
    {
        User::factory()->create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@riskwisdomloans.com.au',
            'password' => 'SecretPass123',
            'is_admin' => true,
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'username' => 'admin',
            'password' => 'SecretPass123',
        ]);

        $response->assertRedirect(route('admin.enquiries.index'));
        $this->assertAuthenticated();
    }

    public function test_admin_can_convert_enquiry_to_client_file(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => 'SecretPass123',
            'is_admin' => true,
        ]);

        $enquiry = Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Jane',
            'last_name' => 'Borrower',
            'phone' => '0400111222',
            'email' => 'jane@example.com',
            'loan_type' => 'home_purchase',
            'timeline' => 'ready_now',
            'state' => 'NSW',
            'enquiry' => 'Looking for a home loan',
            'status' => 'new',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.enquiries.convert', $enquiry));

        $response
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('clients', [
            'enquiry_id' => $enquiry->id,
            'email' => 'jane@example.com',
            'first_name' => 'Jane',
            'status' => 'active',
        ]);

        $client = \App\Models\Client::query()->where('enquiry_id', $enquiry->id)->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.enquiries.index'))
            ->assertOk()
            ->assertSee('Client file')
            ->assertSee(route('admin.clients.show', $client), false);

        $this->actingAs($admin)
            ->get(route('admin.enquiries.show', $enquiry))
            ->assertOk()
            ->assertSee('Has client file')
            ->assertSee(route('admin.clients.show', $client), false);

        $this->actingAs($admin)
            ->get(route('admin.clients.index'))
            ->assertOk()
            ->assertSee('From lead')
            ->assertSee(route('admin.enquiries.show', $enquiry), false);

        $this->actingAs($admin)
            ->get(route('admin.clients.show', $client))
            ->assertOk()
            ->assertSee('Source lead')
            ->assertSee(route('admin.enquiries.show', $enquiry), false);
    }

    public function test_admin_leads_index_shows_create_client_file_for_unconverted_lead(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => 'SecretPass123',
            'is_admin' => true,
        ]);

        Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Sam',
            'last_name' => 'Leadonly',
            'phone' => '0400999888',
            'email' => 'sam.leadonly@example.com',
            'loan_type' => 'home_purchase',
            'timeline' => 'ready_now',
            'state' => 'NSW',
            'enquiry' => 'Need help',
            'status' => 'new',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.enquiries.index'))
            ->assertOk()
            ->assertSee('Lead only')
            ->assertSee('Create client file', false);
    }

    public function test_admin_manual_client_file_shows_manual_label(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => 'SecretPass123',
            'is_admin' => true,
        ]);

        \App\Models\Client::query()->create([
            'first_name' => 'Manual',
            'last_name' => 'Client',
            'phone' => '0400123456',
            'email' => 'manual.client@example.com',
            'loan_type' => 'refinance',
            'state' => 'VIC',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.clients.index'))
            ->assertOk()
            ->assertSee('Manual Client')
            ->assertSee('Manual file');
    }

    public function test_admin_listing_filters_and_search_work(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => 'SecretPass123',
            'is_admin' => true,
        ]);

        Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Ready',
            'last_name' => 'Buyer',
            'phone' => '0400111001',
            'email' => 'ready.buyer@example.com',
            'loan_type' => 'home_purchase',
            'timeline' => 'ready_now',
            'state' => 'NSW',
            'enquiry' => 'Ready now enquiry',
            'status' => 'new',
        ]);

        Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Later',
            'last_name' => 'Buyer',
            'phone' => '0400111002',
            'email' => 'later.buyer@example.com',
            'loan_type' => 'refinance',
            'timeline' => '1_3_months',
            'state' => 'VIC',
            'enquiry' => 'Later enquiry',
            'status' => 'new',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.enquiries.index'))
            ->assertOk()
            ->assertDontSee('rw-admin-stats', false)
            ->assertSee('rw-admin-filters', false)
            ->assertSee('Ready now')
            ->assertSee('Lead only')
            ->assertSee('Has client file');

        $this->actingAs($admin)
            ->get(route('admin.enquiries.index', ['filter' => 'ready_now']))
            ->assertOk()
            ->assertSee('Ready Buyer')
            ->assertDontSee('Later Buyer');

        $this->actingAs($admin)
            ->get(route('admin.enquiries.index', ['q' => 'later.buyer']))
            ->assertOk()
            ->assertSee('Later Buyer')
            ->assertDontSee('Ready Buyer');

        $this->actingAs($admin)
            ->get(route('admin.clients.index'))
            ->assertOk()
            ->assertDontSee('rw-admin-stats', false)
            ->assertSee('rw-admin-filters', false);

        $this->actingAs($admin)
            ->get(route('admin.tasks.index'))
            ->assertOk()
            ->assertDontSee('rw-admin-stats', false)
            ->assertSee('rw-admin-filters', false);
    }

    public function test_admin_listing_pages_show_pagination_summary_and_pages(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => 'SecretPass123',
            'is_admin' => true,
        ]);

        Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Single',
            'last_name' => 'Page',
            'phone' => '0400111222',
            'email' => 'single.page@example.com',
            'loan_type' => 'home_purchase',
            'timeline' => 'ready_now',
            'state' => 'NSW',
            'enquiry' => 'One page only',
            'status' => 'new',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.enquiries.index'))
            ->assertOk()
            ->assertSee('rw-pager', false)
            ->assertSee('Showing')
            ->assertSee('Previous')
            ->assertSee('Next');

        for ($i = 1; $i <= 16; $i++) {
            Enquiry::query()->create([
                'lead_type' => 'contact',
                'first_name' => 'Lead',
                'last_name' => 'Person'.$i,
                'phone' => '04000000'.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'email' => "lead{$i}@example.com",
                'loan_type' => 'home_purchase',
                'timeline' => 'ready_now',
                'state' => 'NSW',
                'enquiry' => 'Enquiry '.$i,
                'status' => 'new',
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.enquiries.index'))
            ->assertOk()
            ->assertSee('rw-pager', false)
            ->assertSee('Showing')
            ->assertSee('of')
            ->assertSee('Next')
            ->assertSee('page=2', false);

        $client = \App\Models\Client::query()->create([
            'first_name' => 'Paged',
            'last_name' => 'Client',
            'phone' => '0400111000',
            'email' => 'paged.client@example.com',
            'loan_type' => 'refinance',
            'state' => 'NSW',
            'status' => 'active',
        ]);

        for ($i = 1; $i <= 16; $i++) {
            \App\Models\Task::query()->create([
                'client_id' => $client->id,
                'title' => 'Task '.$i,
                'owner' => 'client',
                'status' => 'open',
                'priority' => 'normal',
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.clients.index'))
            ->assertOk()
            ->assertSee('rw-pager', false)
            ->assertSee('Showing')
            ->assertSee('Previous')
            ->assertSee('Next');

        $this->actingAs($admin)
            ->get(route('admin.tasks.index'))
            ->assertOk()
            ->assertSee('rw-pager', false)
            ->assertSee('Previous')
            ->assertSee('Next')
            ->assertSee('page=2', false);
    }

    public function test_admin_can_view_and_delete_enquiry(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => 'SecretPass123',
            'is_admin' => true,
        ]);

        $enquiry = Enquiry::query()->create([
            'lead_type' => 'conversion',
            'first_name' => 'Test',
            'last_name' => 'Lead',
            'phone' => '0400111222',
            'email' => 'test@example.com',
            'loan_type' => 'refinance',
            'timeline' => '1_3_months',
            'state' => 'NSW',
            'enquiry' => 'Refinance enquiry',
            'status' => 'new',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.enquiries.show', $enquiry))
            ->assertOk()
            ->assertSee('Test Lead')
            ->assertSee('Refinance enquiry');

        $this->actingAs($admin)
            ->delete(route('admin.enquiries.destroy', $enquiry))
            ->assertRedirect(route('admin.enquiries.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('enquiries', ['id' => $enquiry->id]);
    }

    public function test_admin_cannot_delete_enquiry_with_client_file(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => 'SecretPass123',
            'is_admin' => true,
        ]);

        $enquiry = Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Jane',
            'last_name' => 'Borrower',
            'phone' => '0400111222',
            'email' => 'jane@example.com',
            'loan_type' => 'home_purchase',
            'timeline' => 'ready_now',
            'state' => 'NSW',
            'enquiry' => 'Looking for a home loan',
            'status' => 'new',
        ]);

        \App\Models\Client::query()->create(\App\Models\Client::fromEnquiry($enquiry));

        $this->actingAs($admin)
            ->delete(route('admin.enquiries.destroy', $enquiry))
            ->assertRedirect(route('admin.enquiries.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('enquiries', ['id' => $enquiry->id]);
    }

    public function test_admin_can_create_client_file_manually(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => 'SecretPass123',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.clients.store'), [
            'first_name' => 'Deepak',
            'last_name' => 'Saini',
            'email' => 'deepaksaini10036@gmail.com',
            'phone' => '8195967310',
            'loan_type' => 'refinance',
            'state' => 'NSW',
            'assigned_user_id' => $admin->id,
            'notes' => 'Test client file',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('clients', [
            'first_name' => 'Deepak',
            'last_name' => 'Saini',
            'email' => 'deepaksaini10036@gmail.com',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_create_tasks_and_close_them(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => 'SecretPass123',
            'is_admin' => true,
        ]);

        $client = \App\Models\Client::query()->create([
            'first_name' => 'Kal',
            'last_name' => 'Client',
            'email' => 'kal@example.com',
            'phone' => '0400333444',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.clients.tasks.store', $client), [
                'title' => 'Upload payslips',
                'description' => 'Last 2 payslips required',
                'owner' => 'client',
                'status' => 'open',
                'priority' => 'high',
                'due_date' => now()->addDays(3)->format('Y-m-d'),
            ])
            ->assertRedirect(route('admin.clients.show', $client));

        $task = \App\Models\Task::query()->first();
        $this->assertNotNull($task);
        $this->assertSame('open', $task->status);

        $this->actingAs($admin)
            ->patch(route('admin.clients.tasks.close', [$client, $task]))
            ->assertRedirect(route('admin.clients.show', $client));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'done',
        ]);

        $this->assertNotNull($task->fresh()->closed_at);
    }

    public function test_admin_tasks_index_requires_authentication(): void
    {
        $this->get(route('admin.tasks.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_store_document_draft_without_signing_provider(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => 'SecretPass123',
            'is_admin' => true,
        ]);

        $client = \App\Models\Client::query()->create([
            'first_name' => 'Jane',
            'last_name' => 'Signer',
            'email' => 'jane@example.com',
            'status' => 'active',
        ]);

        config([
            'signing.provider' => 'annature',
            'annature.enabled' => false,
            'signing.document_types' => [
                'privacy_consent' => 'Privacy consent',
                'credit_guide' => 'Credit guide acknowledgment',
                'authority_to_act' => 'Authority to act',
                'other' => 'Other document',
            ],
        ]);

        $response = $this->actingAs($admin)->post(route('admin.clients.documents.store', $client), [
            'document_type' => 'privacy_consent',
            'title' => 'Privacy consent',
            'signer_name' => 'Jane Signer',
            'signer_email' => 'jane@example.com',
            'pdf' => \Illuminate\Http\UploadedFile::fake()->create('privacy.pdf', 50, 'application/pdf'),
        ]);

        $response
            ->assertRedirect(route('admin.clients.show', $client))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('client_documents', [
            'client_id' => $client->id,
            'title' => 'Privacy consent',
            'status' => 'draft',
            'signer_email' => 'jane@example.com',
        ]);
    }

    public function test_docusign_webhook_marks_document_signed(): void
    {
        Storage::fake('local');

        $client = \App\Models\Client::query()->create([
            'first_name' => 'Jane',
            'last_name' => 'Signer',
            'email' => 'jane@example.com',
            'status' => 'active',
        ]);

        $document = \App\Models\ClientDocument::query()->create([
            'client_id' => $client->id,
            'document_type' => 'privacy_consent',
            'title' => 'Privacy consent',
            'envelope_id' => 'env-test-123',
            'status' => 'sent',
            'signer_name' => 'Jane Signer',
            'signer_email' => 'jane@example.com',
            'original_disk' => 'local',
            'original_path' => 'clients/1/documents/original/test.pdf',
            'sent_at' => now(),
        ]);

        Storage::disk('local')->put('clients/1/documents/original/test.pdf', '%PDF-1.4 test');

        $this->mock(\App\Services\DocuSignService::class, function ($mock) use ($document): void {
            $mock->shouldReceive('verifyWebhookSignature')->once()->andReturn(true);
            $mock->shouldReceive('handleWebhookPayload')->once()->with(\Mockery::type('array'))
                ->andReturnUsing(function () use ($document): void {
                    $disk = 'local';
                    $path = sprintf('clients/%d/documents/%d-signed.pdf', $document->client_id, $document->id);
                    Storage::disk($disk)->put($path, '%PDF-1.4 signed');
                    $document->update([
                        'status' => 'signed',
                        'signed_disk' => $disk,
                        'signed_path' => $path,
                        'signed_at' => now(),
                    ]);
                });
        });

        $this->postJson(route('webhooks.docusign'), [
            'data' => [
                'envelopeId' => 'env-test-123',
                'envelopeSummary' => [
                    'status' => 'completed',
                ],
            ],
        ])->assertOk();

        $document->refresh();

        $this->assertSame('signed', $document->status);
        $this->assertNotNull($document->signed_path);
        Storage::disk('local')->assertExists($document->signed_path);
    }

    public function test_annature_webhook_marks_document_signed(): void
    {
        Storage::fake('local');

        $client = \App\Models\Client::query()->create([
            'first_name' => 'Jane',
            'last_name' => 'Signer',
            'email' => 'jane@example.com',
            'status' => 'active',
        ]);

        $document = \App\Models\ClientDocument::query()->create([
            'client_id' => $client->id,
            'document_type' => 'privacy_consent',
            'title' => 'Privacy consent',
            'envelope_id' => 'env-annature-123',
            'signing_provider' => 'annature',
            'status' => 'sent',
            'signer_name' => 'Jane Signer',
            'signer_email' => 'jane@example.com',
            'original_disk' => 'local',
            'original_path' => 'clients/1/documents/original/test.pdf',
            'sent_at' => now(),
        ]);

        Storage::disk('local')->put('clients/1/documents/original/test.pdf', '%PDF-1.4 test');

        $this->mock(\App\Services\AnnatureService::class, function ($mock) use ($document): void {
            $mock->shouldReceive('verifyWebhookSignature')->once()->andReturn(true);
            $mock->shouldReceive('handleWebhookPayload')->once()->with(\Mockery::type('array'))
                ->andReturnUsing(function () use ($document): void {
                    $disk = 'local';
                    $path = sprintf('clients/%d/documents/%d-signed.pdf', $document->client_id, $document->id);
                    Storage::disk($disk)->put($path, '%PDF-1.4 signed');
                    $document->update([
                        'status' => 'signed',
                        'signed_disk' => $disk,
                        'signed_path' => $path,
                        'signed_at' => now(),
                    ]);
                });
        });

        $this->postJson(route('webhooks.annature'), [
            'event' => 'recipient_completed',
            'envelope_id' => 'env-annature-123',
            'recipient_id' => 'recipient-1',
            'completed' => now()->toIso8601String(),
        ])->assertOk();

        $document->refresh();

        $this->assertSame('signed', $document->status);
        $this->assertNotNull($document->signed_path);
        Storage::disk('local')->assertExists($document->signed_path);
    }

    public function test_seo_refinance_landing_pages_are_accessible(): void
    {
        $this->get(route('pages.refinance-rates'))
            ->assertOk()
            ->assertSee('Refinance home loan rates', false);

        $this->get(route('pages.refinance-calculator'))
            ->assertOk()
            ->assertSee('Refinance home loan calculator', false);

        $this->get(route('pages.refinance-cashback'))
            ->assertOk()
            ->assertSee('cashback', false);
    }

    public function test_sitemap_returns_xml_with_seo_urls(): void
    {
        $response = $this->get(route('sitemap'));

        $response
            ->assertOk()
            ->assertHeader('content-type', 'application/xml')
            ->assertSee('refinance-home-loan-rates', false)
            ->assertSee('home-loans', false)
            ->assertSee('/refinance</loc>', false);
    }

    public function test_ad_landing_url_builds_utm_query_string(): void
    {
        $url = ad_landing_url('refinance_rates', [
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => 'refinance_rates',
        ]);

        $this->assertStringContainsString('/refinance-home-loan-rates', $url);
        $this->assertStringContainsString('utm_source=google', $url);
        $this->assertStringContainsString('utm_medium=cpc', $url);
    }

    public function test_conversion_landing_url_builds_campaign_urls(): void
    {
        $url = conversion_landing_url('refinance', [
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => 'refinance',
        ]);

        $this->assertStringContainsString('/enquire/refinance', $url);
        $this->assertStringContainsString('utm_medium=cpc', $url);
    }

    public function test_conversion_landing_pages_are_accessible(): void
    {
        $this->get(route('enquire.show'))
            ->assertOk()
            ->assertSee('rw-conversion', false)
            ->assertSee('Loan type')
            ->assertDontSee('rw-footer__grid', false);

        $this->get(route('enquire.campaign', ['campaign' => 'refinance']))
            ->assertOk()
            ->assertSee('Refinance Mortgage Broker')
            ->assertSee('Get in touch')
            ->assertSee('An expert will call you back')
            ->assertSee('data-track-form="conversion"', false);
    }

    public function test_conversion_form_stores_qualified_lead(): void
    {
        Mail::fake();

        $response = $this->from(route('enquire.campaign', ['campaign' => 'refinance']))
            ->post(route('enquire.campaign.submit', ['campaign' => 'refinance']), [
                'first_name' => 'Jane',
                'last_name' => 'Borrower',
                'phone' => '0400000000',
                'email' => 'jane@example.com',
                'loan_type' => 'refinance',
                'timeline' => 'ready_now',
                'state' => 'NSW',
                'enquiry' => 'Want to lower my rate on a $500k loan.',
                'utm_source' => 'google',
                'utm_medium' => 'cpc',
                'utm_campaign' => 'refinance',
            ]);

        $response
            ->assertRedirect(route('thank-you'))
            ->assertSessionHas('lead_type', 'conversion');

        $this->assertDatabaseHas('enquiries', [
            'lead_type' => 'conversion',
            'email' => 'jane@example.com',
            'loan_type' => 'refinance',
            'timeline' => 'ready_now',
            'state' => 'NSW',
            'source' => 'conversion_refinance',
            'utm_medium' => 'cpc',
        ]);
    }

    public function test_conversion_form_rejects_fake_email(): void
    {
        $response = $this->from(route('enquire.campaign', ['campaign' => 'refinance']))
            ->post(route('enquire.campaign.submit', ['campaign' => 'refinance']), [
                'first_name' => 'Test',
                'last_name' => 'User',
                'phone' => '0400000000',
                'email' => 'test@gmail.com',
                'loan_type' => 'refinance',
                'timeline' => 'ready_now',
                'state' => 'NSW',
                'enquiry' => 'Refinance enquiry',
            ]);

        $response
            ->assertRedirect(route('enquire.campaign', ['campaign' => 'refinance']).'#enquiry-form')
            ->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('enquiries', [
            'email' => 'test@gmail.com',
        ]);
    }

    public function test_unknown_conversion_campaign_returns_not_found(): void
    {
        $this->get('/enquire/unknown-campaign')->assertNotFound();
    }

    public function test_thank_you_page_fires_generate_lead_script(): void
    {
        $response = $this->withSession([
            'lead_type' => 'rate_review',
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => 'refinance_rates',
        ])->get(route('thank-you'));

        $response
            ->assertOk()
            ->assertSee('generate_lead', false)
            ->assertSee('Book a call', false);
    }

    public function test_contact_form_syncs_to_mailchimp_when_marketing_opt_in(): void
    {
        Mail::fake();

        config([
            'mailchimp.enabled' => true,
            'mailchimp.api_key' => 'test-key-us11',
            'mailchimp.server_prefix' => 'us11',
            'mailchimp.audience_id' => 'list123',
        ]);

        Http::fake([
            'https://us11.api.mailchimp.com/3.0/*' => Http::response(['id' => 'member-1'], 200),
        ]);

        $response = $this->post('/contact', [
            'first_name' => 'Kal',
            'last_name' => 'Example',
            'phone' => '0400000000',
            'email' => 'lead@example.com',
            'loan_type' => 'refinance',
            'timeline' => 'ready_now',
            'state' => 'NSW',
            'enquiry' => 'I want to refinance.',
            'marketing_consent' => '1',
        ]);

        $response->assertRedirect(route('thank-you'));

        $this->assertDatabaseHas('enquiries', [
            'email' => 'lead@example.com',
            'marketing_consent' => true,
        ]);

        Http::assertSentCount(2);

        $enquiry = Enquiry::query()->where('email', 'lead@example.com')->first();
        $this->assertNotNull($enquiry?->mailchimp_synced_at);
        $this->assertNull($enquiry->mailchimp_sync_error);
    }

    public function test_contact_form_skips_mailchimp_without_marketing_opt_in(): void
    {
        Mail::fake();

        config([
            'mailchimp.enabled' => true,
            'mailchimp.api_key' => 'test-key-us11',
            'mailchimp.server_prefix' => 'us11',
            'mailchimp.audience_id' => 'list123',
        ]);

        Http::fake();

        $this->post('/contact', [
            'first_name' => 'Kal',
            'last_name' => 'Example',
            'phone' => '0400000000',
            'email' => 'noope@example.com',
            'loan_type' => 'refinance',
            'timeline' => 'ready_now',
            'state' => 'NSW',
            'enquiry' => 'No marketing please.',
        ])->assertRedirect(route('thank-you'));

        Http::assertNothingSent();

        $this->assertDatabaseHas('enquiries', [
            'email' => 'noope@example.com',
            'marketing_consent' => false,
            'mailchimp_synced_at' => null,
        ]);
    }

    public function test_contact_form_still_saves_when_mailchimp_fails(): void
    {
        Mail::fake();

        config([
            'mailchimp.enabled' => true,
            'mailchimp.api_key' => 'test-key-us11',
            'mailchimp.server_prefix' => 'us11',
            'mailchimp.audience_id' => 'list123',
        ]);

        Http::fake([
            'https://us11.api.mailchimp.com/3.0/*' => Http::response(['detail' => 'error'], 500),
        ]);

        $this->post('/contact', [
            'first_name' => 'Kal',
            'last_name' => 'Example',
            'phone' => '0400000000',
            'email' => 'fail@example.com',
            'loan_type' => 'refinance',
            'timeline' => 'ready_now',
            'state' => 'NSW',
            'enquiry' => 'Please still save me.',
            'marketing_consent' => '1',
        ])->assertRedirect(route('thank-you'));

        $enquiry = Enquiry::query()->where('email', 'fail@example.com')->first();
        $this->assertNotNull($enquiry);
        $this->assertNull($enquiry->mailchimp_synced_at);
        $this->assertNotNull($enquiry->mailchimp_sync_error);
    }

    public function test_mailchimp_sync_enquiries_command_dry_run_lists_pending_opt_ins(): void
    {
        Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'A',
            'last_name' => 'B',
            'phone' => '0400000000',
            'email' => 'pending@example.com',
            'loan_type' => 'refinance',
            'timeline' => 'ready_now',
            'state' => 'NSW',
            'enquiry' => 'Test',
            'marketing_consent' => true,
        ]);

        $this->artisan('mailchimp:sync-enquiries', ['--dry-run' => true])
            ->expectsOutputToContain('pending@example.com')
            ->assertSuccessful();
    }

    public function test_homepage_includes_reviews_and_download_guides(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Google Reviews')
            ->assertSee('How it works')
            ->assertSee('Contact us to get started!')
            ->assertSee('images/process/step-01-contact.svg', false)
            ->assertSee('First Home Buyer&#039;s Guide', false)
            ->assertSee('After-hours help');
    }

    public function test_home_loans_page_uses_full_width_landing_layout(): void
    {
        $this->get(route('pages.home-loans'))
            ->assertOk()
            ->assertSee('rw-landing-hero', false)
            ->assertSee('rw-landing-why', false)
            ->assertSee('Why choose us?')
            ->assertSee('images/landing/home-loans-advisor.jpg', false)
            ->assertSee('Home loans Australia')
            ->assertSee('rw-landing-faq', false);
    }

    public function test_partners_page_uses_full_width_landing_layout(): void
    {
        $this->get(route('pages.partners'))
            ->assertOk()
            ->assertSee('rw-landing-hero', false)
            ->assertSee('Partner with Riskwisdom Loans')
            ->assertSee('images/landing/partners-referral.jpg', false)
            ->assertSee('rw-landing-faq', false)
            ->assertSee('Discuss partnership');
    }

    public function test_service_landing_pages_use_full_width_layout_and_footer_faq(): void
    {
        $pages = [
            ['route' => 'pages.refinance', 'heading' => 'Refinance home loan Australia', 'image' => 'refinance-advisor.jpg'],
            ['route' => 'pages.commercial', 'heading' => 'Commercial finance Australia', 'image' => 'commercial-finance-advisor.jpg'],
            ['route' => 'pages.investment', 'heading' => 'Investment property loans Australia', 'image' => 'investment-property-advisor.jpg'],
        ];

        foreach ($pages as $page) {
            $this->get(route($page['route']))
                ->assertOk()
                ->assertSee('rw-landing-hero', false)
                ->assertSee('rw-landing-why', false)
                ->assertSee($page['heading'])
                ->assertSee('images/landing/'.$page['image'], false)
                ->assertSee('rw-landing-faq', false)
                ->assertSee('rw-faq__toggle', false);
        }
    }

    public function test_landing_pages_show_lender_panel_on_light_surface(): void
    {
        $landingRoutes = [
            'pages.first-home-buyer',
            'pages.home-loans',
            'pages.refinance',
            'pages.investment',
            'pages.commercial',
        ];

        foreach ($landingRoutes as $routeName) {
            $this->get(route($routeName))
                ->assertOk()
                ->assertSee('Lender Panel')
                ->assertSee('Macquarie')
                ->assertSee('Bankwest')
                ->assertSee('rw-lender-strip__item', false);
        }
    }

    public function test_about_page_is_accessible(): void
    {
        $this->get(route('pages.about'))
            ->assertOk()
            ->assertDontSee('rw-landing-hero', false)
            ->assertSee('rw-about-broker', false)
            ->assertSee('Kaltaran Bhinder')
            ->assertSee('Mortgage Broker')
            ->assertSee('Get in touch')
            ->assertSee('Take the next step in under a minute')
            ->assertSee('Book a free call')
            ->assertSee('images/landing/about-broker-team.jpg', false)
            ->assertSee('images/landing/kaltaran-bhinder.png', false)
            ->assertSee('rw-landing-faq', false);
    }

    public function test_download_guide_page_is_accessible(): void
    {
        $this->get(route('guides.download.show', 'first-home-buyers-guide'))
            ->assertOk()
            ->assertSee('Download the First Home Buyer&#039;s Guide', false)
            ->assertSee('Get the guide');
    }

    public function test_guide_download_creates_enquiry_and_shows_download_button(): void
    {
        Mail::fake();

        config([
            'mailchimp.enabled' => true,
            'mailchimp.api_key' => 'test-key-us11',
            'mailchimp.server_prefix' => 'us11',
            'mailchimp.audience_id' => 'list123',
        ]);

        Http::fake([
            'https://us11.api.mailchimp.com/3.0/*' => Http::response(['id' => 'member-1'], 200),
        ]);

        $response = $this->post(route('guides.download.store', 'first-home-buyers-guide'), [
            'first_name' => 'Guide',
            'last_name' => 'User',
            'email' => 'guide@example.com',
            'phone' => '0400000000',
            'state' => 'NSW',
        ]);

        $response
            ->assertRedirect(route('thank-you'))
            ->assertSessionHas('lead_type', 'guide_download');

        $this->assertDatabaseHas('enquiries', [
            'lead_type' => 'guide_download',
            'email' => 'guide@example.com',
            'source' => 'guide_download',
            'marketing_consent' => true,
        ]);

        $this->get(route('thank-you'))
            ->assertOk()
            ->assertSee('Download First Home Buyer&#039;s Guide', false);

        Mail::assertSent(ContactAutoReplyMail::class);
        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/members/')
                && $request->method() === 'PUT';
        });
        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/tags')
                && $request->method() === 'POST';
        });
    }

    public function test_newsletter_signup_stores_contact_and_syncs_to_mailchimp(): void
    {
        config([
            'mailchimp.enabled' => true,
            'mailchimp.api_key' => 'test-key-us11',
            'mailchimp.server_prefix' => 'us11',
            'mailchimp.audience_id' => 'list123',
        ]);

        Http::fake([
            'https://us11.api.mailchimp.com/3.0/*' => Http::response(['id' => 'member-1'], 200),
        ]);

        $this->post(route('newsletter.signup'), [
            'first_name' => 'Newsletter',
            'email' => 'newsletter@example.com',
        ])->assertRedirect();

        $this->assertDatabaseHas('newsletter_signups', [
            'email' => 'newsletter@example.com',
            'first_name' => 'Newsletter',
        ]);

        Http::assertSentCount(2);
    }

    public function test_after_hours_chat_creates_enquiry(): void
    {
        Mail::fake();

        $this->post(route('chat.capture'), [
            'first_name' => 'Night',
            'last_name' => 'Visitor',
            'email' => 'night@example.com',
            'phone' => '0400999888',
            'loan_type' => 'refinance',
            'enquiry' => 'Need help after hours.',
            'marketing_consent' => '1',
        ])->assertRedirect(route('thank-you'));

        $this->assertDatabaseHas('enquiries', [
            'lead_type' => 'chat_widget',
            'email' => 'night@example.com',
            'source' => 'after_hours_chat',
        ]);
    }
}
