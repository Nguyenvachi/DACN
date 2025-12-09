<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Models\LoginAudit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
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
    public function store(LoginRequest $request): RedirectResponse|JsonResponse
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
                $errorMsg = "Tài khoản đã bị khóa đến {$lockedUntil}. Vui lòng liên hệ quản trị viên.";

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMsg,
                        'errors' => ['email' => [$errorMsg]]
                    ], 422);
                }

                return back()->withErrors(['email' => $errorMsg])->onlyInput('email');
            }

            // Thử authenticate
            $request->authenticate();

            $request->session()->regenerate();

            // Đăng nhập thành công
            /** @var \App\Models\User $authenticatedUser */
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
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'redirect' => route('password.request'),
                        'message' => 'Bạn cần đặt lại mật khẩu trước khi tiếp tục.'
                    ]);
                }
                return redirect()->route('password.request')->with('status', 'Bạn cần đặt lại mật khẩu trước khi tiếp tục.');
            }

            // Redirect theo role
            $intendedUrl = redirect()->intended()->getTargetUrl();
            $redirectUrl = null;

            if ($intendedUrl === url('/dashboard') || $intendedUrl === url('/')) {
                if ($authenticatedUser->role === 'admin') {
                    $redirectUrl = route('admin.dashboard');
                } elseif ($authenticatedUser->role === 'doctor') {
                    $redirectUrl = route('doctor.calendar.index');
                } elseif ($authenticatedUser->role === 'staff') {
                    $redirectUrl = route('staff.dashboard');
                } elseif ($authenticatedUser->role === 'patient') {
                    $redirectUrl = route('dashboard');
                }
            }

            if (!$redirectUrl) {
                $redirectUrl = RouteServiceProvider::HOME;
            }

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công!',
                    'redirect' => $redirectUrl
                ]);
            }

            return redirect($redirectUrl);
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

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email hoặc mật khẩu không đúng.',
                    'errors' => $e->errors()
                ], 422);
            }

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
