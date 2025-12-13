<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginAudit;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'locked') {
                $query->whereNotNull('locked_at')
                    ->where(function ($q) {
                        $q->whereNull('locked_until')
                            ->orWhere('locked_until', '>', now());
                    });
            } elseif ($request->status === 'active') {
                $query->where(function ($q) {
                    $q->whereNull('locked_at')
                        ->orWhere('locked_until', '<', now());
                });
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $roles = ['admin', 'staff', 'doctor', 'patient']; // 4 role đơn giản

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'staff', 'doctor', 'patient'];
        $loginAudits = LoginAudit::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.users.edit', compact('user', 'roles', 'loginAudits'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff,doctor,patient',
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('status', 'Đã cập nhật thông tin user');
    }

    public function lock(User $user)
    {
        $user->update([
            'locked_at' => now(),
            'locked_until' => null, // Khóa vĩnh viễn
        ]);

        return back()->with('status', 'Đã khóa tài khoản: ' . $user->email);
    }

    public function unlock(User $user)
    {
        $user->update([
            'locked_at' => null,
            'locked_until' => null,
            'login_attempts' => 0,
        ]);

        return back()->with('status', 'Đã mở khóa tài khoản: ' . $user->email);
    }

    public function forcePasswordChange(User $user)
    {
        $user->update(['must_change_password' => true]);
        return back()->with('status', 'User phải đổi password lần đăng nhập sau');
    }
}
