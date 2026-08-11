@extends('admin.layout')

@section('title', 'Users')
@section('page_heading', 'Team access')

@section('topbar_actions')
    <a class="rw-button rw-button--solid" href="{{ route('admin.users.create') }}">Add staff</a>
@endsection

@section('content')
    <section class="rw-user-directory">
        <div class="rw-user-directory__intro">
            <div>
                <p class="rw-user-studio__eyebrow">Admin · Users</p>
                <h2>People with panel access</h2>
                <p>Manage who can log in, call leads, and work client files.</p>
            </div>
            <div class="rw-user-directory__stats">
                <div>
                    <strong>{{ $users->where('role', 'admin')->count() }}</strong>
                    <span>Admins</span>
                </div>
                <div>
                    <strong>{{ $users->where('role', 'staff')->count() }}</strong>
                    <span>Staff</span>
                </div>
                <div>
                    <strong>{{ $users->count() }}</strong>
                    <span>Total</span>
                </div>
            </div>
        </div>

        <div class="rw-user-directory__list">
            @forelse ($users as $user)
                @php
                    $initials = collect(preg_split('/\s+/', trim($user->name)))
                        ->filter()
                        ->take(2)
                        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                        ->implode('');
                    $permCount = $user->isPanelAdmin() ? null : count($user->effectivePermissions());
                @endphp
                <article class="rw-user-card">
                    <div class="rw-user-card__main">
                        <span class="rw-user-avatar @if ($user->isPanelAdmin()) is-admin @endif" aria-hidden="true">{{ $initials ?: 'U' }}</span>
                        <div class="rw-user-card__info">
                            <div class="rw-user-card__title">
                                <h3>{{ $user->name }}</h3>
                                <span class="rw-admin-pill @if ($user->isPanelAdmin()) rw-admin-pill--accent @endif">{{ $user->roleLabel() }}</span>
                                @if ($user->id === auth()->id())
                                    <span class="rw-admin-pill rw-admin-pill--muted">You</span>
                                @endif
                            </div>
                            <p class="rw-user-card__meta">
                                <span>{{ '@'.$user->username }}</span>
                                <span>{{ $user->email }}</span>
                            </p>
                            <p class="rw-user-card__access">
                                @if ($user->isPanelAdmin())
                                    Full panel access
                                @else
                                    {{ $permCount }} permission{{ $permCount === 1 ? '' : 's' }} assigned
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="rw-user-card__actions">
                        <a class="rw-button rw-button--solid rw-button--sm" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                        @if ($user->id !== auth()->id())
                            <form
                                method="post"
                                action="{{ route('admin.users.destroy', $user) }}"
                                onsubmit="return confirm('Delete this user?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button class="rw-button rw-button--ghost rw-button--sm rw-button--danger" type="submit">Remove</button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rw-user-empty">
                    <strong>No team members yet</strong>
                    <p>Create the first staff login so your caller can work from the panel.</p>
                    <a class="rw-button rw-button--solid" href="{{ route('admin.users.create') }}">Add staff</a>
                </div>
            @endforelse
        </div>
    </section>
@endsection
