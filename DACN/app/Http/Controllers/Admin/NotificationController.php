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
        // Admin bypass authorize
        if (!auth()->user()->isAdmin()) {
            $this->authorize('send-reminders');
        }
        $users = User::all(); // Hoặc lọc theo role
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

        foreach ($users as $user) {
            $user->notify(new CustomNotification([
                'title' => $request->title,
                'message' => $request->message,
            ]));
        }

        return redirect()->back()->with('success', 'Thông báo đã được gửi!');
    }
}
