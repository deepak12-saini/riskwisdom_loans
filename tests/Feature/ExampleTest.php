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
            ->assertSee('Get free loan review');
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

    public function test_landing_pages_are_accessible(): void
    {
        $this->get(route('pages.refinance'))->assertOk()->assertSee('Refinance');
        $this->get(route('tools.borrowing-power'))->assertOk()->assertSee('Borrowing power');
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
