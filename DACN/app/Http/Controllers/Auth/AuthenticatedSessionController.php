<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Models\LoginAudit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Lấy email từ request để ghi audit
        $email = $request->input('email');

        // Tìm user trước khi authenticate
        $user = \App\Models\User::where('email', $email)->first();

        try {
            // Kiểm tra tài khoản có bị khóa không
            if ($user && $user->isLocked()) {
                LoginAudit::create([
                    'user_id' => $user->id,
                    'email' => $email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'failed',
                    'reason' => 'account_locked',
                ]);

                $lockedUntil = $user->locked_until ? $user->locked_until->format('H:i d/m/Y') : 'vĩnh viễn';
                return back()->withErrors([
                    'email' => "Tài khoản đã bị khóa đến {$lockedUntil}. Vui lòng liên hệ quản trị viên.",
                ])->onlyInput('email');
            }

            // Thử authenticate
            $request->authenticate();

            $request->session()->regenerate();

            // Đăng nhập thành công
            /** @var \App\Models\User $authenticatedUser */ // <--- Thêm dòng này
            $authenticatedUser = Auth::user();
            $authenticatedUser->resetLoginAttempts();

            // Ghi audit success
            LoginAudit::create([
                'user_id' => $authenticatedUser->id,
                'email' => $email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'reason' => null,
            ]);

            // Kiểm tra bắt buộc đổi mật khẩu
            if ($authenticatedUser->must_change_password) {
                return redirect()->route('password.request')->with('status', 'Bạn cần đặt lại mật khẩu trước khi tiếp tục.');
            }

            // Redirect theo role
            $intendedUrl = redirect()->intended()->getTargetUrl();

            if ($intendedUrl === url('/dashboard') || $intendedUrl === url('/')) {
                if ($authenticatedUser->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($authenticatedUser->role === 'doctor') {
                    return redirect()->route('doctor.calendar.index');
                } elseif ($authenticatedUser->role === 'staff') {
                    return redirect()->route('staff.dashboard');
                } elseif ($authenticatedUser->role === 'patient') {
                    return redirect()->route('dashboard');
                }
            }

            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Đăng nhập thất bại - tăng login attempts
            if ($user) {
                $user->incrementLoginAttempts();
            }

            LoginAudit::create([
                'user_id' => $user->id ?? null,
                'email' => $email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'failed',
                'reason' => 'invalid_credentials',
            ]);

            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
