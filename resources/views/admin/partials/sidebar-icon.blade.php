@switch($icon)
    @case('dashboard')
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 4h7v7H4V4Zm9 0h7v7h-7V4ZM4 13h7v7H4v-7Zm9 4h7v3h-7v-3Z" stroke="currentColor" stroke-width="1.6"/></svg>
        @break
    @case('leads')
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        @break
    @case('urgent')
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 8v5m0 3h.01M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        @break
    @case('week')
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 3v4m8-4v4M3 10h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        @break
    @case('today')
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.8"/><path d="M12 8v4l3 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        @break
    @case('export')
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v12m0 0 4-4m-4 4-4-4M5 19h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        @break
    @case('website')
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        @break
    @case('logout')
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 7V5a2 2 0 0 1 2-2h7v18h-7a2 2 0 0 1-2-2v-2M7 12H3m0 0 3-3m-3 3 3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        @break
    @case('clients')
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M16 11a3 3 0 1 0-6 0 3 3 0 0 0 6 0ZM4 20a4 4 0 0 1 8 0M16 14a4 4 0 0 1 4 4M19 8v6m3-3h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        @break
    @case('tasks')
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 11l2 2 4-4M7 3h10a2 2 0 0 1 2 2v14l-3-2-3 2-3-2-3 2V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        @break
@endswitch
