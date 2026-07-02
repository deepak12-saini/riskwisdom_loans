@extends('admin.layout')

@section('title', $enquiry->full_name)
@section('page_heading', $enquiry->full_name)

@section('topbar_actions')
    <a class="rw-button rw-button--ghost" href="{{ route('admin.enquiries.index') }}">All enquiries</a>
    @if ($enquiry->client)
        <a class="rw-button rw-button--solid" href="{{ route('admin.clients.show', $enquiry->client) }}">View client file</a>
    @else
        <form method="post" action="{{ route('admin.enquiries.convert', $enquiry) }}" class="rw-admin-inline-form">
            @csrf
            <button class="rw-button rw-button--solid" type="submit">Create client file</button>
        </form>
    @endif
@endsection

@section('content')
    @php
        $metadata = is_array($enquiry->metadata) ? $enquiry->metadata : [];
    @endphp

    <section class="rw-admin-card rw-admin-client-summary">
        <div class="rw-admin-client-summary__head">
            <div>
                <h2>{{ $enquiry->full_name }}</h2>
                <div class="rw-admin-client-summary__badges">
                    <span class="rw-admin-pill">{{ config('riskwisdom.lead_types')[$enquiry->lead_type] ?? $enquiry->lead_type }}</span>
                    @if ($enquiry->timeline === 'ready_now')
                        <span class="rw-admin-pill rw-admin-pill--urgent">Ready now</span>
                    @endif
                    @if ($enquiry->marketing_consent)
                        <span class="rw-admin-pill rw-admin-pill--accent">Marketing opt-in</span>
                    @endif
                </div>
            </div>
            <div class="rw-admin-client-summary__contact">
                <a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a>
                <a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a>
            </div>
        </div>

        <dl class="rw-admin-client-summary__meta">
            <div>
                <dt>Submitted</dt>
                <dd>{{ $enquiry->created_at?->format('d M Y H:i') ?: '—' }}</dd>
            </div>
            <div>
                <dt>Loan type</dt>
                <dd>{{ config('riskwisdom.loan_types')[$enquiry->loan_type] ?? $enquiry->loan_type ?: '—' }}</dd>
            </div>
            <div>
                <dt>Timeline</dt>
                <dd>{{ config('riskwisdom.timelines')[$enquiry->timeline] ?? $enquiry->timeline ?: '—' }}</dd>
            </div>
            <div>
                <dt>State</dt>
                <dd>{{ $enquiry->state ?: '—' }}</dd>
            </div>
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
                <dt>Mailchimp</dt>
                <dd>
                    @if ($enquiry->mailchimp_synced_at)
                        Synced {{ $enquiry->mailchimp_synced_at->format('d M Y H:i') }}
                    @elseif ($enquiry->marketing_consent && $enquiry->mailchimp_sync_error)
                        Error — {{ $enquiry->mailchimp_sync_error }}
                    @elseif ($enquiry->marketing_consent)
                        Pending
                    @else
                        —
                    @endif
                </dd>
            </div>
        </dl>

        @if ($enquiry->enquiry)
            <div class="rw-admin-client-summary__notes">
                <strong>Enquiry message</strong>
                <p>{{ $enquiry->enquiry }}</p>
            </div>
        @endif

        <div class="rw-admin-form-actions">
            @include('admin.enquiries.partials.actions', ['enquiry' => $enquiry])
        </div>
    </section>
@endsection
