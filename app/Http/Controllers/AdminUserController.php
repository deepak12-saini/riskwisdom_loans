<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->where('is_admin', true)
            ->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
            ->orderBy('username')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'catalog' => config('admin_permissions.catalog', []),
            'roles' => config('admin_permissions.roles', []),
            'preset' => User::staffPresetPermissions(),
            'adminOnly' => config('admin_permissions.admin_only', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateUser($request);

        $role = $validated['role'];
        $permissions = $role === User::ROLE_STAFF
            ? User::normalizeStaffPermissions($request->input('permissions', []))
            : null;

        User::query()->create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'is_admin' => true,
            'role' => $role,
            'permissions' => $permissions,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created.');
    }

    public function edit(User $user): View|RedirectResponse
    {
        if (! $user->is_admin) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'That user is not a panel account.');
        }

        return view('admin.users.edit', [
            'user' => $user,
            'catalog' => config('admin_permissions.catalog', []),
            'roles' => config('admin_permissions.roles', []),
            'selectedPermissions' => $user->isStaff()
                ? $user->effectivePermissions()
                : User::staffPresetPermissions(),
            'adminOnly' => config('admin_permissions.admin_only', []),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if (! $user->is_admin) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'That user is not a panel account.');
        }

        $validated = $this->validateUser($request, $user);

        $role = $validated['role'];
        $wasLastAdmin = $user->isPanelAdmin()
            && User::query()->where('is_admin', true)->where('role', User::ROLE_ADMIN)->count() === 1;

        if ($wasLastAdmin && $role !== User::ROLE_ADMIN) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'You cannot demote the last admin account.');
        }

        $permissions = $role === User::ROLE_STAFF
            ? User::normalizeStaffPermissions($request->input('permissions', []))
            : null;

        $payload = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $role,
            'permissions' => $permissions,
            'is_admin' => true,
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (! $user->is_admin) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'That user is not a panel account.');
        }

        if ($user->id === Auth::id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot delete your own account while logged in.');
        }

        if ($user->isPanelAdmin()
            && User::query()->where('is_admin', true)->where('role', User::ROLE_ADMIN)->count() === 1
        ) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot delete the last admin account.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateUser(Request $request, ?User $user = null): array
    {
        $isCreate = $user === null;

        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'username' => [
                'required',
                'string',
                'max:120',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'password' => [$isCreate ? 'required' : 'nullable', 'string', 'min:8', 'max:120'],
            'role' => ['required', 'string', Rule::in([User::ROLE_ADMIN, User::ROLE_STAFF])],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ], [
            'password.required' => 'Please enter a password.',
            'role.in' => 'Please choose Admin or Staff.',
        ]);
    }
}
