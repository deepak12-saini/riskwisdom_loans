@php
    $user = auth()->user();
@endphp

@if ($user)
    <div class="rw-admin-account">
        <div class="rw-admin-account__user" title="{{ $user->email }}">
            <span class="rw-admin-account__avatar">{{ strtoupper(substr((string) ($user->username ?: $user->name), 0, 1)) }}</span>
            <span class="rw-admin-account__copy">
                <strong>{{ $user->username ?: $user->name }}</strong>
                <small>{{ $user->roleLabel() }}</small>
            </span>
        </div>
        <form method="post" action="{{ route('admin.logout') }}">
            @csrf
            <button class="rw-admin-account__logout" type="submit" title="Log out">
                @include('admin.partials.sidebar-icon', ['icon' => 'logout'])
                <span>Log out</span>
            </button>
        </form>
    </div>
@endif
