@extends('admin.layout')

@section('title', $enquiry->full_name)
@section('page_heading', $enquiry->full_name)

@section('topbar_actions')
    <a class="rw-button rw-button--ghost" href="{{ route('admin.enquiries.index') }}">All leads</a>
    @if ($enquiry->client)
        @if (auth()->user()?->canAdmin('clients.view'))
            <a class="rw-button rw-button--solid" href="{{ route('admin.clients.show', $enquiry->client) }}">View client file</a>
        @endif
    @elseif (auth()->user()?->canAdmin('enquiries.convert'))
        <form method="post" action="{{ route('admin.enquiries.convert', $enquiry) }}" class="rw-admin-inline-form">
            @csrf
            <button class="rw-button rw-button--solid" type="submit">Create client file</button>
        </form>
    @endif
@endsection

@section('content')
    @php
        $metadata = is_array($enquiry->metadata) ? $enquiry->metadata : [];
        $initials = collect(preg_split('/\s+/', trim($enquiry->full_name)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
        $leadType = config('riskwisdom.lead_types')[$enquiry->lead_type] ?? $enquiry->lead_type;
        $loanLabel = config('riskwisdom.loan_types')[$enquiry->loan_type] ?? $enquiry->loan_type ?: '—';
        $timelineLabel = config('riskwisdom.timelines')[$enquiry->timeline] ?? $enquiry->timeline ?: '—';
        $isReadyNow = $enquiry->timeline === 'ready_now';
        $mailchimpLabel = $enquiry->mailchimp_synced_at
            ? 'Synced '.$enquiry->mailchimp_synced_at->format('d M Y H:i')
            : ($enquiry->marketing_consent && $enquiry->mailchimp_sync_error
                ? 'Error'
                : ($enquiry->marketing_consent ? 'Pending' : '—'));
    @endphp

    <div class="rw-lead-hub">
        <aside class="rw-lead-hub__profile">
            <div class="rw-client-profile">
                <div class="rw-client-profile__identity">
                    <div class="rw-client-profile__avatar rw-client-profile__avatar--lead" aria-hidden="true">{{ $initials ?: 'LD' }}</div>
                    <div>
                        <p class="rw-client-profile__eyebrow">Website lead</p>
                        <h2 class="rw-client-profile__name">{{ $enquiry->full_name }}</h2>
                        <div class="rw-client-profile__badges">
                            <span class="rw-admin-pill">{{ $leadType }}</span>
                            @if ($isReadyNow)
                                <span class="rw-admin-pill rw-admin-pill--urgent">Ready now</span>
                            @endif
                            @if ($enquiry->client)
                                <a class="rw-admin-pill rw-admin-pill--accent" href="{{ route('admin.clients.show', $enquiry->client) }}">Has client file</a>
                            @else
                                <span class="rw-admin-pill rw-admin-pill--muted">Lead only</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="rw-client-profile__actions">
                    <a class="rw-client-profile__action" href="mailto:{{ $enquiry->email }}">
                        <span>Email</span>
                        <strong>{{ $enquiry->email }}</strong>
                    </a>
                    @if ($enquiry->phone)
                        <a class="rw-client-profile__action" href="tel:{{ $enquiry->phone }}">
                            <span>Phone</span>
                            <strong>{{ $enquiry->phone }}</strong>
                        </a>
                    @endif
                    @if (calendly_url())
                        <a
                            class="rw-client-profile__action rw-client-profile__action--accent"
                            href="{{ calendly_prefill_url($enquiry->full_name, $enquiry->email, $enquiry->phone, $enquiry->first_name, $enquiry->last_name) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span>Meeting</span>
                            <strong>Book in Calendly</strong>
                        </a>
                    @endif
                </div>

                <dl class="rw-client-profile__meta">
                    <div>
                        <dt>Submitted</dt>
                        <dd>{{ $enquiry->created_at?->format('d M Y H:i') ?: '—' }}</dd>
                    </div>
                    @if (($enquiry->lead_type === 'calendly') && ! empty($metadata['calendly_start_time']))
                        <div>
                            <dt>Booked call</dt>
                            <dd>
                                @php
                                    try {
                                        $bookedAt = (new \DateTimeImmutable((string) $metadata['calendly_start_time']))
                                            ->setTimezone(new \DateTimeZone((string) ($metadata['calendly_timezone'] ?? 'Australia/Sydney')));
                                        $bookedLabel = $bookedAt->format('D j M Y g:ia T');
                                    } catch (\Throwable) {
                                        $bookedLabel = (string) $metadata['calendly_start_time'];
                                    }
                                @endphp
                                {{ $bookedLabel }}
                                @if (($metadata['calendly_status'] ?? '') === 'canceled')
                                    <br><span class="rw-admin-pill rw-admin-pill--urgent">Canceled</span>
                                @endif
                            </dd>
                        </div>
                    @endif
                    <div>
                        <dt>Loan type</dt>
                        <dd>{{ $loanLabel }}</dd>
                    </div>
                    <div>
                        <dt>Timeline</dt>
                        <dd>{{ $timelineLabel }}</dd>
                    </div>
                    <div>
                        <dt>State</dt>
                        <dd>{{ $enquiry->state ?: '—' }}</dd>
                    </div>
                </dl>

                <div class="rw-client-profile__stats" aria-label="Lead snapshot">
                    <div class="rw-client-stat @if ($isReadyNow) is-urgent @endif">
                        <strong>{{ $isReadyNow ? 'Now' : 'Later' }}</strong>
                        <span>Urgency</span>
                    </div>
                    <div class="rw-client-stat">
                        <strong>{{ $enquiry->marketing_consent ? 'Yes' : 'No' }}</strong>
                        <span>Marketing</span>
                    </div>
                    <div class="rw-client-stat">
                        <strong>{{ $enquiry->client ? 'Yes' : 'No' }}</strong>
                        <span>Client file</span>
                    </div>
                </div>
            </div>
        </aside>

        <div class="rw-lead-hub__workspace">
            <section class="rw-lead-panel">
                <div class="rw-lead-panel__intro">
                    <div>
                        <h2>{{ $enquiry->lead_type === 'calendly' ? 'Booking details' : 'Enquiry message' }}</h2>
                        <p>
                            @if ($enquiry->lead_type === 'calendly')
                                Calendly booking synced into admin so staff can call this contact.
                            @else
                                What they submitted through the website form.
                            @endif
                        </p>
                    </div>
                    @if ($enquiry->client)
                        @if (auth()->user()?->canAdmin('clients.view'))
                            <a class="rw-button rw-button--solid rw-button--sm" href="{{ route('admin.clients.show', $enquiry->client) }}">Open client file</a>
                        @endif
                    @elseif (auth()->user()?->canAdmin('enquiries.convert'))
                        <form method="post" action="{{ route('admin.enquiries.convert', $enquiry) }}" class="rw-admin-inline-form">
                            @csrf
                            <button class="rw-button rw-button--solid rw-button--sm" type="submit">Create client file</button>
                        </form>
                    @endif
                </div>

                @if ($enquiry->enquiry)
                    <blockquote class="rw-lead-message">{{ $enquiry->enquiry }}</blockquote>
                @else
                    <div class="rw-client-empty">
                        <strong>No message provided</strong>
                        <p>This lead submitted contact details without a written enquiry.</p>
                    </div>
                @endif
            </section>

            <section class="rw-lead-panel">
                <div class="rw-lead-panel__intro">
                    <div>
                        <h2>Attribution &amp; sync</h2>
                        <p>Where the lead came from and marketing sync status.</p>
                    </div>
                </div>

                <dl class="rw-lead-meta-grid">
                    <div>
                        <dt>Source</dt>
                        <dd>{{ $enquiry->source ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt>UTM</dt>
                        <dd>
                            @if ($enquiry->utm_source || $enquiry->utm_medium || $enquiry->utm_campaign)
                                {{ $enquiry->utm_source ?: '—' }} / {{ $enquiry->utm_medium ?: '—' }}
                                @if ($enquiry->utm_campaign)
                                    <br><small>{{ $enquiry->utm_campaign }}</small>
                                @endif
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    @if (! empty($metadata['campaign_label']))
                        <div>
                            <dt>Campaign</dt>
                            <dd>{{ $metadata['campaign_label'] }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt>Marketing consent</dt>
                        <dd>
                            @if ($enquiry->marketing_consent)
                                <span class="rw-admin-pill rw-admin-pill--accent">Opt-in</span>
                            @else
                                <span class="rw-admin-pill rw-admin-pill--muted">No</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt>Mailchimp</dt>
                        <dd>
                            @if ($enquiry->mailchimp_synced_at)
                                <span class="rw-admin-pill rw-admin-pill--accent">{{ $mailchimpLabel }}</span>
                            @elseif ($enquiry->marketing_consent && $enquiry->mailchimp_sync_error)
                                <span class="rw-admin-pill rw-admin-pill--urgent" title="{{ $enquiry->mailchimp_sync_error }}">Error</span>
                            @elseif ($enquiry->marketing_consent)
                                <span class="rw-admin-pill rw-admin-pill--muted">Pending</span>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                </dl>
            </section>

            <section class="rw-lead-panel rw-lead-panel--actions">
                <div class="rw-lead-panel__intro">
                    <div>
                        <h2>Next step</h2>
                        <p>
                            @if ($enquiry->client)
                                This lead already has a client file — continue checklist and e-sign there.
                            @else
                                Call the lead, book a meeting, or convert into a client file when you’re ready.
                            @endif
                        </p>
                    </div>
                </div>

                @include('admin.partials.book-meeting', [
                    'bookName' => $enquiry->full_name,
                    'bookEmail' => $enquiry->email,
                    'bookPhone' => $enquiry->phone,
                    'bookFirstName' => $enquiry->first_name,
                    'bookLastName' => $enquiry->last_name,
                ])

                <div class="rw-lead-next">
                    @if ($enquiry->client)
                        @if (auth()->user()?->canAdmin('clients.view'))
                            <a class="rw-button rw-button--solid" href="{{ route('admin.clients.show', $enquiry->client) }}">View client file</a>
                        @endif
                    @elseif (auth()->user()?->canAdmin('enquiries.convert'))
                        <form method="post" action="{{ route('admin.enquiries.convert', $enquiry) }}" class="rw-admin-inline-form">
                            @csrf
                            <button class="rw-button rw-button--ghost" type="submit">Create client file</button>
                        </form>
                    @endif
                    <a class="rw-button rw-button--ghost" href="mailto:{{ $enquiry->email }}">Email lead</a>
                    @if ($enquiry->phone)
                        <a class="rw-button rw-button--ghost" href="tel:{{ $enquiry->phone }}">Call lead</a>
                    @endif
                    @include('admin.enquiries.partials.actions', ['enquiry' => $enquiry])
                </div>
            </section>
        </div>
    </div>
@endsection
