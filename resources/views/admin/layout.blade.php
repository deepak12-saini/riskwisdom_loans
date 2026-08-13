<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Admin') | Riskwisdom Loans</title>
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
        @vite(['resources/css/app.css'])
    </head>
    <body class="site-body rw-theme rw-admin-body">
        @include('admin.partials.sidebar')

        <div class="rw-admin-shell">
            <header class="rw-admin-topbar">
                <div class="rw-admin-topbar__left">
                    <button type="button" class="rw-admin-menu-toggle" aria-label="Open menu" aria-expanded="false">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div>
                        <p class="rw-admin-topbar__eyebrow">Riskwisdom Loans</p>
                        <h1>@yield('page_heading', 'Dashboard')</h1>
                    </div>
                </div>
                <div class="rw-admin-topbar__actions">
                    @yield('topbar_actions')
                    @include('admin.partials.account')
                </div>
            </header>

            <main class="rw-admin-content">
                @include('admin.partials.flash')
                @yield('content')
            </main>
        </div>

        <script>
            document.querySelector('.rw-admin-menu-toggle')?.addEventListener('click', () => {
                document.body.classList.toggle('rw-admin-sidebar-open');
            });

            document.querySelectorAll('.js-copy-booking-link').forEach((button) => {
                button.addEventListener('click', async () => {
                    const url = button.getAttribute('data-copy-url');
                    const defaultLabel = button.getAttribute('data-copy-label') || 'Copy booking link';
                    const copiedLabel = button.getAttribute('data-copied-label') || 'Link copied';

                    if (!url) {
                        return;
                    }

                    try {
                        await navigator.clipboard.writeText(url);
                        button.textContent = copiedLabel;
                        button.classList.add('is-copied');
                        window.setTimeout(() => {
                            button.textContent = defaultLabel;
                            button.classList.remove('is-copied');
                        }, 1800);
                    } catch (error) {
                        window.prompt('Copy this booking link:', url);
                    }
                });
            });
        </script>
        @stack('scripts')
    </body>
</html>
