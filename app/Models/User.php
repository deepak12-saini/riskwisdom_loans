<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_STAFF = 'staff';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'is_admin',
        'role',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, bool|string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'permissions' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user): void {
            if ($user->role === null || $user->role === '') {
                $user->role = $user->is_admin ? self::ROLE_ADMIN : self::ROLE_STAFF;
            }
        });
    }

    public function isPanelAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function roleLabel(): string
    {
        return (string) (config('admin_permissions.roles')[$this->role] ?? ucfirst((string) $this->role));
    }

    public function displayName(): string
    {
        $name = trim((string) ($this->name ?: $this->username ?: $this->email));

        return $name !== '' ? $name : 'Staff';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public static function panelUsers()
    {
        return static::query()
            ->where('is_admin', true)
            ->orderBy('username')
            ->get();
    }

    /**
     * @return list<string>
     */
    public function effectivePermissions(): array
    {
        if ($this->isPanelAdmin()) {
            return ['*'];
        }

        $permissions = is_array($this->permissions) ? $this->permissions : [];

        return array_values(array_unique(array_filter(array_map('strval', $permissions))));
    }

    public function canAdmin(string $permission): bool
    {
        if (! $this->is_admin) {
            return false;
        }

        if ($this->isPanelAdmin()) {
            return true;
        }

        if (in_array($permission, config('admin_permissions.admin_only', []), true)) {
            return false;
        }

        return in_array($permission, $this->effectivePermissions(), true);
    }

    /**
     * Normalize requested staff permissions against the catalog.
     *
     * @param  array<int, string>|null  $permissions
     * @return list<string>
     */
    public static function normalizeStaffPermissions(?array $permissions): array
    {
        $catalog = array_keys(config('admin_permissions.catalog', []));
        $adminOnly = config('admin_permissions.admin_only', []);
        $requested = is_array($permissions) ? $permissions : [];

        $normalized = [];

        foreach ($requested as $permission) {
            $permission = (string) $permission;

            if (! in_array($permission, $catalog, true)) {
                continue;
            }

            if (in_array($permission, $adminOnly, true)) {
                continue;
            }

            $normalized[] = $permission;
        }

        return array_values(array_unique($normalized));
    }

    /**
     * @return list<string>
     */
    public static function staffPresetPermissions(): array
    {
        return self::normalizeStaffPermissions(config('admin_permissions.presets.staff', []));
    }
}
