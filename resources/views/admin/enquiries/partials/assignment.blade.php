@php
    $user = auth()->user();
    $isAdmin = (bool) $user?->isPanelAdmin();
    $isMine = $enquiry->assigned_user_id && (int) $enquiry->assigned_user_id === (int) $user?->id;
    $isUnassigned = $enquiry->assigned_user_id === null;
    $canReassign = $isAdmin || $isUnassigned || $isMine;
@endphp

<section class="rw-lead-panel rw-lead-panel--assignment" id="assignment">
    <div class="rw-lead-panel__intro">
        <div>
            <h2>Assigned to</h2>
            <p>Who owns follow-up on this lead.</p>
        </div>
        <span class="rw-admin-pill @if ($isUnassigned) rw-admin-pill--muted @else rw-admin-pill--accent @endif">
            {{ $enquiry->assigneeLabel() }}
        </span>
    </div>

    @if ($canReassign)
        <form method="post" action="{{ route('admin.enquiries.assignment.update', $enquiry) }}" class="rw-assignment-form">
            @csrf
            @method('PATCH')

            @if ($isAdmin)
                <label class="rw-assignment-form__select">
                    <span>Staff member</span>
                    <select name="assigned_user_id">
                        <option value="">— Unassigned —</option>
                        @foreach ($panelUsers as $panelUser)
                            <option value="{{ $panelUser->id }}" @selected((int) ($enquiry->assigned_user_id ?? 0) === (int) $panelUser->id)>
                                {{ $panelUser->displayName() }}
                                @if ($panelUser->isPanelAdmin())
                                    (Admin)
                                @endif
                            </option>
                        @endforeach
                    </select>
                </label>
                <button class="rw-button rw-button--solid rw-button--sm" type="submit">Save assignment</button>
            @elseif ($isUnassigned)
                <input type="hidden" name="assigned_user_id" value="{{ $user->id }}">
                <p class="rw-assignment-form__hint">Take this lead so the rest of the team knows you are calling them.</p>
                <button class="rw-button rw-button--solid rw-button--sm" type="submit">Take this lead</button>
            @else
                <input type="hidden" name="assigned_user_id" value="">
                <p class="rw-assignment-form__hint">This lead is assigned to you. Unassign if someone else should take over.</p>
                <button class="rw-button rw-button--ghost rw-button--sm" type="submit">Unassign</button>
            @endif
        </form>
    @else
        <p class="rw-assignment-form__hint">Assigned to {{ $enquiry->assigneeLabel() }}. Ask an admin if you need it moved.</p>
    @endif

    @if ($enquiry->assigned_at)
        <p class="rw-assignment-form__meta">Assigned {{ $enquiry->assigned_at->format('d M Y g:ia') }}</p>
    @endif
</section>
