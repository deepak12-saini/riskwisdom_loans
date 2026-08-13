<section class="rw-lead-panel rw-lead-panel--activity" id="activity">
    <div class="rw-lead-panel__intro">
        <div>
            <h2>Activity</h2>
            <p>Timeline of assignment, calls, bookings, and conversion.</p>
        </div>
    </div>

    @if ($enquiry->activities->isEmpty())
        <div class="rw-client-empty">
            <strong>No activity yet</strong>
            <p>Updates appear here when the lead is created, assigned, called, booked, or converted.</p>
        </div>
    @else
        <ol class="rw-activity-list">
            @foreach ($enquiry->activities as $activity)
                <li class="rw-activity-item rw-activity-item--{{ $activity->type }}">
                    <span class="rw-activity-item__dot" aria-hidden="true"></span>
                    <div>
                        <p class="rw-activity-item__message">{{ $activity->message }}</p>
                        <p class="rw-activity-item__meta">
                            {{ $activity->actorLabel() }}
                            · {{ $activity->created_at?->format('d M Y g:ia') }}
                        </p>
                    </div>
                </li>
            @endforeach
        </ol>
    @endif
</section>
