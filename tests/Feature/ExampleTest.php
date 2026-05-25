<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_homepage_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response
            ->assertStatus(200)
            ->assertSee('Riskwisdom Loans')
            ->assertSee('Smarter loan guidance for every stage of life.');
    }

    public function test_the_contact_form_accepts_a_valid_enquiry(): void
    {
        $response = $this->post('/contact', [
            'first_name' => 'Kal',
            'last_name' => 'Example',
            'phone' => '0400000000',
            'email' => 'info@riskwisdomloans.com.au',
            'enquiry' => 'I would like help with a first home loan.',
        ]);

        $response
            ->assertRedirect('/')
            ->assertSessionHas('status');
    }
}
