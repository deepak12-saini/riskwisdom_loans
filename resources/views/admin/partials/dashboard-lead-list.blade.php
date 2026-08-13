@if ($items->isEmpty())
    <p class="rw-dash-empty">{{ $empty }}</p>
@else
    <ul class="rw-dash-list">
        @foreach ($items as $enquiry)
            <li>
                <a href="{{ route('admin.enquiries.show', $enquiry) }}">
                    <strong>{{ $enquiry->full_name }}</strong>
                    <span>
                        {{ $enquiry->phone }}
                        @if (! empty($showCallback) && $enquiry->callback_at)
                            · {{ $enquiry->callback_at->lt(now()->startOfDay()) ? 'Overdue' : $enquiry->callback_at->format('g:ia') }}
                        @elseif ($enquiry->timeline === 'ready_now')
                            · Ready now
                        @else
                            · {{ config('riskwisdom.lead_types')[$enquiry->lead_type] ?? $enquiry->lead_type }}
                        @endif
                    </span>
                </a>
                @if ($enquiry->phone)
                    <a class="rw-dash-list__call" href="tel:{{ $enquiry->phone }}" aria-label="Call {{ $enquiry->full_name }}">Call</a>
                @endif
            </li>
        @endforeach
    </ul>
@endif
