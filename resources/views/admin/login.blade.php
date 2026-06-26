<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Login | Riskwisdom Loans</title>
        @vite(['resources/css/app.css'])
    </head>
    <body class="site-body rw-theme rw-admin-body rw-admin-login-page">
        <main class="rw-admin-login">
            <form method="post" action="{{ route('admin.login.submit') }}" class="rw-admin-card rw-admin-login-card">
                @csrf
                <div class="rw-admin-login-card__brand">
                    <img
                        src="{{ asset('images/risk-wisdom-loans-logo.png') }}"
                        alt="Risk Wisdom Loans"
                        width="220"
                        height="64"
                    >
                    <span class="rw-admin-login-card__badge">Admin</span>
                </div>

                <h1>Admin login</h1>
                <p class="rw-admin-card__lead">Sign in with your admin username and password.</p>

                <label>
                    <span>Username</span>
                    <input
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required
                        autofocus
                    >
                    @error('username')
                        <small>{{ $message }}</small>
                    @enderror
                </label>

                <label>
                    <span>Password</span>
                    <input type="password" name="password" autocomplete="current-password" required>
                    @error('password')
                        <small>{{ $message }}</small>
                    @enderror
                </label>

                <button class="rw-button rw-button--solid rw-button--wide" type="submit">Sign in</button>
            </form>
        </main>
    </body>
</html>
