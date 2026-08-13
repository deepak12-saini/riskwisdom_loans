<?php

namespace Tests\Feature;

use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_dashboard_with_today_queue(): void
    {
        $admin = User::factory()->panelAdmin()->create();

        Enquiry::query()->create([
            'lead_type' => 'rate_review',
            'first_name' => 'Priya',
            'last_name' => 'New',
            'phone' => '+61400000010',
            'email' => 'priya@example.com',
            'enquiry' => 'New rate review',
            'call_status' => 'new',
        ]);

        Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Sam',
            'last_name' => 'Callback',
            'phone' => '+61400000011',
            'email' => 'sam@example.com',
            'enquiry' => 'Needs callback',
            'call_status' => 'callback',
            'callback_at' => now()->setTime(14, 0),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('My leads')
            ->assertSee('New leads to call')
            ->assertSee('My callbacks today')
            ->assertSee('Priya New')
            ->assertSee('Sam Callback');
    }

    public function test_staff_with_lead_access_can_open_dashboard(): void
    {
        $staff = User::factory()->panelStaff(['enquiries.view'])->create();

        $this->actingAs($staff)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('New leads')
            ->assertSee('Callbacks today');
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }
}
