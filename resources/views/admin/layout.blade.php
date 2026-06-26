<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Admin') | Riskwisdom Loans</title>
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
                </div>
            </header>

            <main class="rw-admin-content">
                @yield('content')
            </main>
        </div>

        <script>
            document.querySelector('.rw-admin-menu-toggle')?.addEventListener('click', () => {
                document.body.classList.toggle('rw-admin-sidebar-open');
            });
        </script>
    </body>
</html>
