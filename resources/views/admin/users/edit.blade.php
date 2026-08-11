@extends('admin.layout')

@section('title', 'Edit user')
@section('page_heading', 'Edit '.$user->name)

@section('topbar_actions')
    <a class="rw-button rw-button--ghost" href="{{ route('admin.users.index') }}">Back to users</a>
@endsection

@section('content')
    @php
        $initials = collect(preg_split('/\s+/', trim($user->name)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    @endphp

    <form class="rw-user-studio" method="post" action="{{ route('admin.users.update', $user) }}" data-user-form>
        @csrf
        @method('PUT')

        <div class="rw-user-studio__intro">
            <div class="rw-user-studio__person">
                <span class="rw-user-avatar" aria-hidden="true">{{ $initials ?: 'U' }}</span>
                <div>
                    <p class="rw-user-studio__eyebrow">Editing access</p>
                    <h2>{{ $user->name }}</h2>
                    <p>{{ '@'.$user->username }} · {{ $user->email }}</p>
                </div>
            </div>
            <span class="rw-admin-pill @if ($user->isPanelAdmin()) rw-admin-pill--accent @endif">{{ $user->roleLabel() }}</span>
        </div>

        @if ($errors->any())
            <div class="rw-admin-alert rw-admin-alert--error" role="alert">
                Please fix the highlighted fields and try again.
            </div>
        @endif

        <div class="rw-user-studio__grid">
            <section class="rw-user-panel">
                <header class="rw-user-panel__head">
                    <h3>Profile &amp; login</h3>
                    <p>Leave password blank to keep the current one.</p>
                </header>
                <div class="rw-user-panel__body rw-admin-form">
                    <div class="rw-user-fields">
                        <label>
                            <span>Full name</span>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        </label>
                        <label>
                            <span>Username</span>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" required autocomplete="username">
                        </label>
                        <label class="rw-user-fields__full">
                            <span>Work email</span>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </label>
                        <label class="rw-user-fields__full">
                            <span>New password</span>
                            <input type="password" name="password" autocomplete="new-password" placeholder="Leave blank to keep current">
                        </label>
                    </div>
                </div>
            </section>

            <section class="rw-user-panel">
                <header class="rw-user-panel__head">
                    <h3>Role</h3>
                    <p>Change role and access for this person.</p>
                </header>
                <div class="rw-user-panel__body">
                    @include('admin.users.partials.role-cards', [
                        'roles' => $roles,
                        'currentRole' => old('role', $user->role),
                    ])
                </div>
            </section>
        </div>

        <section class="rw-user-panel rw-user-panel--wide" data-staff-permissions>
            <div class="rw-user-panel__body">
                @include('admin.users.partials.permissions', [
                    'catalog' => $catalog,
                    'selectedPermissions' => $selectedPermissions,
                    'adminOnly' => $adminOnly,
                ])
            </div>
        </section>

        <div class="rw-user-studio__footer">
            <a class="rw-button rw-button--ghost" href="{{ route('admin.users.index') }}">Cancel</a>
            <button class="rw-button rw-button--solid" type="submit">Save changes</button>
        </div>
    </form>
@endsection

@push('scripts')
    @include('admin.users.partials.role-toggle-script')
@endpush
