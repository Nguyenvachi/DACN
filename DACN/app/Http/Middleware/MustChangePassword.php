<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MustChangePassword
{
    /**
     * Nếu user phải đổi password (must_change_password=true),
     * redirect về trang change password (có thể dùng profile.edit hoặc trang riêng).
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Cho phép truy cập route password.*, profile.* và logout
        $allowedRoutes = ['password.request', 'password.reset', 'password.email', 'password.update', 'password.confirm', 'profile.edit', 'profile.update', 'logout'];
        
        if ($user && $user->must_change_password && !in_array($request->route()->getName(), $allowedRoutes)) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Bạn phải đổi mật khẩu trước khi tiếp tục sử dụng hệ thống.');
        }

        return $next($request);
    }
}
