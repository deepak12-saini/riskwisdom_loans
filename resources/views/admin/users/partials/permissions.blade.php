@php
    $selected = collect(old('permissions', $selectedPermissions ?? $preset ?? []))->map(fn ($p) => (string) $p)->all();
    $adminOnly = $adminOnly ?? config('admin_permissions.admin_only', []);
    $groups = config('admin_permissions.groups', []);
    $catalog = $catalog ?? config('admin_permissions.catalog', []);

    $grouped = [];
    foreach ($catalog as $key => $label) {
        $groupKey = strstr($key, '.', true) ?: 'other';
        $grouped[$groupKey][$key] = $label;
    }
@endphp

<div class="rw-user-perms" data-role-permissions>
    <div class="rw-user-perms__head">
        <div>
            <h3>Access permissions</h3>
            <p>Staff preset is selected. Adjust what this person can see and change.</p>
        </div>
        <div class="rw-user-perms__bulk">
            <button type="button" class="rw-user-perms__bulk-btn" data-perms-preset>Use staff preset</button>
            <button type="button" class="rw-user-perms__bulk-btn" data-perms-clear>Clear all</button>
        </div>
    </div>

    <div class="rw-user-perms__groups">
        @foreach ($grouped as $groupKey => $items)
            <section class="rw-user-perms__group">
                <header class="rw-user-perms__group-head">
                    <h4>{{ $groups[$groupKey] ?? ucfirst($groupKey) }}</h4>
                    <span>{{ count($items) }}</span>
                </header>
                <div class="rw-user-perms__list">
                    @foreach ($items as $key => $label)
                        @php $locked = in_array($key, $adminOnly, true); @endphp
                        <label class="rw-user-perm @if ($locked) is-locked @endif @if (in_array($key, $selected, true) && ! $locked) is-on @endif">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $key }}"
                                @checked(in_array($key, $selected, true) && ! $locked)
                                @disabled($locked)
                                data-permission-checkbox
                                data-preset="{{ in_array($key, config('admin_permissions.presets.staff', []), true) ? '1' : '0' }}"
                            >
                            <span class="rw-user-perm__check" aria-hidden="true"></span>
                            <span class="rw-user-perm__text">
                                <strong>{{ $label }}</strong>
                                @if ($locked)
                                    <small>Admin only</small>
                                @endif
                            </span>
                        </label>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</div>
