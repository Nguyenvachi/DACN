<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        // Eloquent attributes are not real PHP properties, so use attribute access
        $role = strtolower((string) ($user->role ?? ''));
        $isAdminFlag = (bool) ($user->is_admin ?? false);

        if (!($isAdminFlag || $role === 'admin')) {
            abort(403, 'Forbidden');
        }
        return $next($request);
    }
}
