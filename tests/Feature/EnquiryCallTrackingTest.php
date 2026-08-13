<?php

namespace Tests\Feature;

use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnquiryCallTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_update_call_status_and_notes(): void
    {
        $staff = User::factory()->panelStaff(['enquiries.view'])->create();

        $enquiry = Enquiry::query()->create([
            'lead_type' => 'rate_review',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '+61400000000',
            'email' => 'jane@example.com',
            'enquiry' => 'Rate review request',
            'call_status' => 'new',
        ]);

        $response = $this->actingAs($staff)->patch(route('admin.enquiries.call-tracking.update', $enquiry), [
            'call_status' => 'called',
            'call_notes' => 'Spoke at 2pm — wants broker callback tomorrow.',
        ]);

        $response
            ->assertRedirect(route('admin.enquiries.show', $enquiry))
            ->assertSessionHas('success');

        $enquiry->refresh();

        $this->assertSame('called', $enquiry->call_status);
        $this->assertSame('Spoke at 2pm — wants broker callback tomorrow.', $enquiry->call_notes);
        $this->assertNotNull($enquiry->last_called_at);
        $this->assertSame($staff->id, $enquiry->assigned_user_id);
    }

    public function test_callback_status_requires_callback_date(): void
    {
        $staff = User::factory()->panelStaff(['enquiries.view'])->create();

        $enquiry = Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Tom',
            'last_name' => 'Lee',
            'phone' => '+61400000001',
            'email' => 'tom@example.com',
            'enquiry' => 'General enquiry',
        ]);

        $response = $this->actingAs($staff)->from(route('admin.enquiries.show', $enquiry))
            ->patch(route('admin.enquiries.call-tracking.update', $enquiry), [
                'call_status' => 'callback',
                'call_notes' => 'Call back Friday.',
            ]);

        $response->assertRedirect(route('admin.enquiries.show', $enquiry));
        $response->assertSessionHasErrors('callback_at');
    }

    public function test_callbacks_due_filter_shows_due_leads(): void
    {
        $admin = User::factory()->panelAdmin()->create();

        Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Due',
            'last_name' => 'Today',
            'phone' => '+61400000002',
            'email' => 'due@example.com',
            'enquiry' => 'Needs callback',
            'call_status' => 'callback',
            'callback_at' => now()->setTime(15, 0),
        ]);

        Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Future',
            'last_name' => 'Lead',
            'phone' => '+61400000003',
            'email' => 'future@example.com',
            'enquiry' => 'Later callback',
            'call_status' => 'callback',
            'callback_at' => now()->addDays(3),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.enquiries.index', ['filter' => 'callbacks_due']));

        $response
            ->assertOk()
            ->assertSee('Due Today')
            ->assertDontSee('Future Lead');
    }
}
