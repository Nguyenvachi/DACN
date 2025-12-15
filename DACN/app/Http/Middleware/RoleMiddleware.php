<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user) {
            \Log::info('RoleMiddleware: No user');
            abort(403);
        }

        $allowed = array_map('strtolower', $roles);
        $userRole = strtolower((string) $user->roleKey());

        \Log::info('RoleMiddleware: User role: ' . $userRole . ', Allowed: ' . implode(',', $allowed));

        if (in_array($userRole, $allowed, true)) {
            return $next($request);
        }

        abort(403);
    }
}
