@if (session('success'))
    <div class="rw-admin-alert rw-admin-alert--success" role="status">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="rw-admin-alert rw-admin-alert--error" role="alert">
        {{ session('error') }}
    </div>
@endif
