<div class="rw-admin-icon-actions">
    <a
        class="rw-admin-icon-action"
        href="{{ route('admin.enquiries.show', $enquiry) }}"
        title="View enquiry"
        aria-label="View enquiry"
    >
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M2.5 12s3.5-6.5 9.5-6.5S21.5 12 21.5 12s-3.5 6.5-9.5 6.5S2.5 12 2.5 12Z" stroke="currentColor" stroke-width="1.8"/>
            <circle cx="12" cy="12" r="2.75" stroke="currentColor" stroke-width="1.8"/>
        </svg>
    </a>

    @if ($enquiry->client)
        <a
            class="rw-admin-icon-action"
            href="{{ route('admin.clients.show', $enquiry->client) }}"
            title="View client file"
            aria-label="View client file"
        >
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M7 3.75h7.5L19 8.25v12a1.5 1.5 0 0 1-1.5 1.5h-10A1.5 1.5 0 0 1 6 20.25v-15A1.5 1.5 0 0 1 7.5 3.75H7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M14.25 3.75V8.1H18.5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M9 12.5h6M9 16h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </a>
    @else
        <form
            method="post"
            action="{{ route('admin.enquiries.convert', $enquiry) }}"
            class="rw-admin-inline-form"
        >
            @csrf
            <button
                class="rw-admin-icon-action"
                type="submit"
                title="Create client file"
                aria-label="Create client file"
            >
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 7v10M7 12h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <rect x="4" y="4" width="16" height="16" rx="3" stroke="currentColor" stroke-width="1.8"/>
                </svg>
            </button>
        </form>
    @endif

    <form
        method="post"
        action="{{ route('admin.enquiries.destroy', $enquiry) }}"
        class="rw-admin-inline-form"
        onsubmit="return confirm('Delete this enquiry permanently?');"
    >
        @csrf
        @method('DELETE')
        <button
            class="rw-admin-icon-action rw-admin-icon-action--danger"
            type="submit"
            title="Delete enquiry"
            aria-label="Delete enquiry"
        >
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m1 0v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V7h12Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </form>
</div>
