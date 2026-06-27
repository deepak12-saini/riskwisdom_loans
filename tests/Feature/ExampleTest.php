<?php

namespace Tests\Feature;

use App\Mail\ContactAutoReplyMail;
use App\Mail\ContactEnquiryMail;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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
}
