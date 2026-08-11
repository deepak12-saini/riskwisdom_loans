<?php

namespace Tests\Feature;

use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_users_and_create_staff(): void
    {
        $admin = User::factory()->panelAdmin()->create([
            'username' => 'boss',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Add staff');

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Caller One',
                'username' => 'caller1',
                'email' => 'caller1@example.com',
                'password' => 'Password123!',
                'role' => User::ROLE_STAFF,
                'permissions' => User::staffPresetPermissions(),
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'username' => 'caller1',
            'role' => User::ROLE_STAFF,
            'is_admin' => 1,
        ]);
    }

    public function test_staff_cannot_open_users_or_delete_enquiry_by_default(): void
    {
        $staff = User::factory()->panelStaff()->create([
            'username' => 'staffer',
        ]);

        $enquiry = Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Test',
            'last_name' => 'Lead',
            'phone' => '0412345678',
            'email' => 'lead@example.com',
            'enquiry' => 'Need help',
            'source' => 'contact',
        ]);

        $this->actingAs($staff)
            ->get(route('admin.enquiries.index'))
            ->assertOk();

        $this->actingAs($staff)
            ->get(route('admin.users.index'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->delete(route('admin.enquiries.destroy', $enquiry))
            ->assertForbidden();
    }

    public function test_staff_with_delete_permission_can_delete_enquiry(): void
    {
        $permissions = User::staffPresetPermissions();
        $permissions[] = 'enquiries.delete';

        $staff = User::factory()->panelStaff($permissions)->create([
            'username' => 'seniorstaff',
        ]);

        $enquiry = Enquiry::query()->create([
            'lead_type' => 'contact',
            'first_name' => 'Delete',
            'last_name' => 'Me',
            'phone' => '0412000000',
            'email' => 'delete@example.com',
            'enquiry' => 'Remove me',
            'source' => 'contact',
        ]);

        $this->actingAs($staff)
            ->delete(route('admin.enquiries.destroy', $enquiry))
            ->assertRedirect(route('admin.enquiries.index'));

        $this->assertDatabaseMissing('enquiries', ['id' => $enquiry->id]);
    }

    public function test_staff_without_clients_view_cannot_open_clients(): void
    {
        $staff = User::factory()->panelStaff(['enquiries.view'])->create([
            'username' => 'leadsonly',
        ]);

        $this->actingAs($staff)
            ->get(route('admin.clients.index'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->get(route('admin.enquiries.index'))
            ->assertOk();
    }

    public function test_users_manage_cannot_be_assigned_to_staff(): void
    {
        $admin = User::factory()->panelAdmin()->create(['username' => 'boss2']);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Bad Staff',
                'username' => 'badstaff',
                'email' => 'badstaff@example.com',
                'password' => 'Password123!',
                'role' => User::ROLE_STAFF,
                'permissions' => array_merge(User::staffPresetPermissions(), ['users.manage']),
            ])
            ->assertRedirect(route('admin.users.index'));

        $staff = User::query()->where('username', 'badstaff')->first();

        $this->assertNotNull($staff);
        $this->assertFalse($staff->canAdmin('users.manage'));
        $this->assertNotContains('users.manage', $staff->effectivePermissions());
    }
}
