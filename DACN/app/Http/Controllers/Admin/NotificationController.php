<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\CustomNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function create()
    {
        // Admin bypass authorize, staff/others need permission
        if (!auth()->user()->isAdmin()) {
            $this->authorize('send-reminders');
        }
        $users = User::all(); // Hoặc có thể lọc bớt nếu danh sách quá dài
        return view('admin.notifications.send', compact('users'));
    }

    public function store(Request $request)
    {
        // Admin bypass authorize
        if (!auth()->user()->isAdmin()) {
            $this->authorize('send-reminders');
        }

        $request->validate([
            'recipient_type' => 'required|in:all,patients,doctors,staff,specific',
            'user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $users = collect();

        // Logic lọc người nhận
        if ($request->recipient_type === 'all') {
            $users = User::all();
        } elseif ($request->recipient_type === 'patients') {
            $users = User::where('role', 'patient')->get();
        } elseif ($request->recipient_type === 'doctors') {
            $users = User::where('role', 'doctor')->get();
        } elseif ($request->recipient_type === 'staff') {
            $users = User::where('role', 'staff')->get();
        } elseif ($request->recipient_type === 'specific') {
            $users = User::where('id', $request->user_id)->get();
        }

        // Gửi thông báo
        foreach ($users as $user) {
            // Xác định URL chuyển hướng tùy theo role người nhận
            // Giúp người dùng bấm vào thông báo là đến đúng trang danh sách của họ
            $url = match($user->role) {
                'doctor' => route('doctor.notifications.index'),
                'staff' => route('staff.notifications.index'),
                'patient' => route('patient.notifications'),
                default => url('/') // Admin hoặc role khác về trang chủ
            };

            // Truyền tham số rời rạc vào Constructor (Title, Message, URL)
            // Thay vì truyền mảng như code cũ gây lỗi
            $user->notify(new CustomNotification(
                $request->title,    // Tham số 1: Tiêu đề
                $request->message,  // Tham số 2: Nội dung
                $url                // Tham số 3: Đường dẫn (Optional)
            ));
        }

        return redirect()->back()->with('success', 'Thông báo đã được gửi thành công!');
    }
}
