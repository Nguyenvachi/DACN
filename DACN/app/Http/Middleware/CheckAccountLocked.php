<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountLocked
{
    /**
     * Kiểm tra tài khoản có bị khóa không.
     * Nếu locked_at tồn tại và locked_until chưa hết hạn -> logout + thông báo.
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User $user */  // <--- Thêm dòng này
        $user = Auth::user();

        if ($user && $user->isLocked()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.']);
        }

        return $next($request);
    }
}
