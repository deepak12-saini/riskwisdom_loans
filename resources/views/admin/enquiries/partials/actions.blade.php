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
