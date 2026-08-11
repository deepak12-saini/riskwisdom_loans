@php
    $currentRole = old('role', $currentRole ?? 'staff');
    $roleDescriptions = config('admin_permissions.role_descriptions', []);
@endphp

<div class="rw-user-roles" data-role-cards>
    <input type="hidden" name="role" value="{{ $currentRole }}" data-role-select>

    @foreach ($roles as $value => $label)
        <button
            type="button"
            class="rw-user-role @if ($currentRole === $value) is-active @endif"
            data-role-option="{{ $value }}"
            aria-pressed="{{ $currentRole === $value ? 'true' : 'false' }}"
        >
            <span class="rw-user-role__badge">{{ $value === 'admin' ? 'A' : 'S' }}</span>
            <span class="rw-user-role__copy">
                <strong>{{ $label }}</strong>
                <small>{{ $roleDescriptions[$value] ?? '' }}</small>
            </span>
        </button>
    @endforeach
</div>
