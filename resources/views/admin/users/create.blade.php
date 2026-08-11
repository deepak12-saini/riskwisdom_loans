@extends('admin.layout')

@section('title', 'Add staff')
@section('page_heading', 'Add staff')

@section('topbar_actions')
    <a class="rw-button rw-button--ghost" href="{{ route('admin.users.index') }}">Back to users</a>
@endsection

@section('content')
    <form class="rw-user-studio" method="post" action="{{ route('admin.users.store') }}" data-user-form>
        @csrf

        <div class="rw-user-studio__intro">
            <div>
                <p class="rw-user-studio__eyebrow">Team access</p>
                <h2>Create a panel login</h2>
                <p>Invite an employee, pick their role, and set exactly what they can use.</p>
            </div>
            <div class="rw-user-studio__steps" aria-hidden="true">
                <span class="is-active">1 Profile</span>
                <span>2 Role</span>
                <span>3 Permissions</span>
            </div>
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
                    <p>Details used for `/admin` sign-in.</p>
                </header>

                <div class="rw-user-panel__body rw-admin-form">
                    <div class="rw-user-fields">
                        <label>
                            <span>Full name</span>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Sarah Khan">
                        </label>
                        <label>
                            <span>Username</span>
                            <input type="text" name="username" value="{{ old('username') }}" required autocomplete="username" placeholder="e.g. sarah">
                        </label>
                        <label class="rw-user-fields__full">
                            <span>Work email</span>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@company.com">
                        </label>
                        <label class="rw-user-fields__full">
                            <span>Temporary password</span>
                            <input type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters">
                        </label>
                    </div>
                </div>
            </section>

            <section class="rw-user-panel">
                <header class="rw-user-panel__head">
                    <h3>Role</h3>
                    <p>Admin has full access. Staff uses custom permissions.</p>
                </header>
                <div class="rw-user-panel__body">
                    @include('admin.users.partials.role-cards', [
                        'roles' => $roles,
                        'currentRole' => old('role', 'staff'),
                    ])
                </div>
            </section>
        </div>

        <section class="rw-user-panel rw-user-panel--wide" data-staff-permissions>
            <div class="rw-user-panel__body">
                @include('admin.users.partials.permissions', [
                    'catalog' => $catalog,
                    'preset' => old('permissions', $preset),
                    'adminOnly' => $adminOnly,
                ])
            </div>
        </section>

        <div class="rw-user-studio__footer">
            <a class="rw-button rw-button--ghost" href="{{ route('admin.users.index') }}">Cancel</a>
            <button class="rw-button rw-button--solid" type="submit">Create user</button>
        </div>
    </form>
@endsection

@push('scripts')
    @include('admin.users.partials.role-toggle-script')
@endpush
