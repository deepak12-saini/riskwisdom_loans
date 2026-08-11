<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->where('username', $credentials['username'])
            ->where('is_admin', true)
            ->first();

        if ($user === null || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => 'Invalid username or password.',
                ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->canAdmin('enquiries.view')) {
            return redirect()->route('admin.enquiries.index');
        }

        if ($user->canAdmin('clients.view')) {
            return redirect()->route('admin.clients.index');
        }

        if ($user->canAdmin('tasks.view')) {
            return redirect()->route('admin.tasks.index');
        }

        if ($user->canAdmin('users.manage')) {
            return redirect()->route('admin.users.index');
        }

        return redirect()->route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
