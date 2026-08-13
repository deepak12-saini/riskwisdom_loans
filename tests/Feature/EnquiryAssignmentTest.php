<?php

namespace Tests\Feature;

use App\Models\Enquiry;
use App\Models\EnquiryActivity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnquiryAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_an_enquiry_writes_a_created_activity(): void
    {
        $enquiry = $this->makeEnquiry();

        $this->assertDatabaseHas('enquiry_activities', [
            'enquiry_id' => $enquiry->id,
            'type' => 'created',
        ]);
    }

    public function test_admin_can_assign_a_lead_to_staff(): void
    {
        $admin = User::factory()->panelAdmin()->create(['name' => 'Kal Admin']);
        $staff = User::factory()->panelStaff(['enquiries.view'])->create(['name' => 'Sam Caller']);
        $enquiry = $this->makeEnquiry();

        $this->actingAs($admin)
            ->patch(route('admin.enquiries.assignment.update', $enquiry), [
                'assigned_user_id' => $staff->id,
            ])
            ->assertRedirect(route('admin.enquiries.show', $enquiry))
            ->assertSessionHas('success');

        $enquiry->refresh();

        $this->assertSame($staff->id, $enquiry->assigned_user_id);
        $this->assertNotNull($enquiry->assigned_at);
        $this->assertDatabaseHas('enquiry_activities', [
            'enquiry_id' => $enquiry->id,
            'type' => 'assigned',
            'user_id' => $admin->id,
        ]);
    }

    public function test_staff_can_take_an_unassigned_lead(): void
    {
        $staff = User::factory()->panelStaff(['enquiries.view'])->create(['name' => 'Sam Caller']);
        $enquiry = $this->makeEnquiry();

        $this->actingAs($staff)
            ->patch(route('admin.enquiries.assignment.update', $enquiry), [
                'assigned_user_id' => $staff->id,
            ])
            ->assertRedirect(route('admin.enquiries.show', $enquiry));

        $this->assertSame($staff->id, $enquiry->fresh()->assigned_user_id);
    }

    public function test_staff_cannot_assign_a_lead_to_someone_else(): void
    {
        $staff = User::factory()->panelStaff(['enquiries.view'])->create();
        $other = User::factory()->panelStaff(['enquiries.view'])->create();
        $enquiry = $this->makeEnquiry();

        $this->actingAs($staff)
            ->from(route('admin.enquiries.show', $enquiry))
            ->patch(route('admin.enquiries.assignment.update', $enquiry), [
                'assigned_user_id' => $other->id,
            ])
            ->assertRedirect(route('admin.enquiries.show', $enquiry))
            ->assertSessionHas('error');

        $this->assertNull($enquiry->fresh()->assigned_user_id);
    }

    public function test_staff_cannot_take_a_lead_assigned_to_someone_else(): void
    {
        $owner = User::factory()->panelStaff(['enquiries.view'])->create();
        $staff = User::factory()->panelStaff(['enquiries.view'])->create();
        $enquiry = $this->makeEnquiry([
            'assigned_user_id' => $owner->id,
            'assigned_at' => now(),
        ]);

        $this->actingAs($staff)
            ->from(route('admin.enquiries.show', $enquiry))
            ->patch(route('admin.enquiries.assignment.update', $enquiry), [
                'assigned_user_id' => $staff->id,
            ])
            ->assertSessionHas('error');

        $this->assertSame($owner->id, $enquiry->fresh()->assigned_user_id);
    }

    public function test_updating_call_tracking_auto_assigns_unassigned_lead(): void
    {
        $staff = User::factory()->panelStaff(['enquiries.view'])->create(['name' => 'Sam Caller']);
        $enquiry = $this->makeEnquiry();

        $this->actingAs($staff)
            ->patch(route('admin.enquiries.call-tracking.update', $enquiry), [
                'call_status' => 'called',
                'call_notes' => 'Spoke with the client.',
            ])
            ->assertRedirect(route('admin.enquiries.show', $enquiry));

        $enquiry->refresh();

        $this->assertSame($staff->id, $enquiry->assigned_user_id);
        $this->assertSame('called', $enquiry->call_status);
        $this->assertTrue(
            EnquiryActivity::query()
                ->where('enquiry_id', $enquiry->id)
                ->where('type', 'assigned')
                ->exists()
        );
        $this->assertTrue(
            EnquiryActivity::query()
                ->where('enquiry_id', $enquiry->id)
                ->where('type', 'call_status')
                ->exists()
        );
    }

    public function test_my_leads_filter_shows_only_assigned_leads(): void
    {
        $staff = User::factory()->panelStaff(['enquiries.view'])->create();
        $mine = $this->makeEnquiry([
            'first_name' => 'Mine',
            'last_name' => 'Lead',
            'email' => 'mine@example.com',
            'assigned_user_id' => $staff->id,
        ]);
        $this->makeEnquiry([
            'first_name' => 'Other',
            'last_name' => 'Lead',
            'email' => 'other@example.com',
        ]);

        $this->actingAs($staff)
            ->get(route('admin.enquiries.index', ['filter' => 'mine']))
            ->assertOk()
            ->assertSee('Mine Lead')
            ->assertDontSee('Other Lead');

        $this->assertSame($staff->id, $mine->assigned_user_id);
    }

    public function test_converting_a_lead_copies_assignment_and_logs_activity(): void
    {
        $admin = User::factory()->panelAdmin()->create();
        $staff = User::factory()->panelStaff(['enquiries.view'])->create();
        $enquiry = $this->makeEnquiry([
            'assigned_user_id' => $staff->id,
            'assigned_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.enquiries.convert', $enquiry))
            ->assertRedirect();

        $this->assertDatabaseHas('clients', [
            'enquiry_id' => $enquiry->id,
            'assigned_user_id' => $staff->id,
        ]);
        $this->assertDatabaseHas('enquiry_activities', [
            'enquiry_id' => $enquiry->id,
            'type' => 'converted',
            'user_id' => $admin->id,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makeEnquiry(array $overrides = []): Enquiry
    {
        return Enquiry::query()->create(array_merge([
            'lead_type' => 'contact',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '+61400000000',
            'email' => 'jane@example.com',
            'enquiry' => 'Rate review request',
            'call_status' => 'new',
        ], $overrides));
    }
}
