<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = Auth::user();

        if ($user !== null && $user->is_admin && $user->canAdmin($permission)) {
            return $next($request);
        }

        abort(403, 'You do not have permission to perform this action.');
    }
}
