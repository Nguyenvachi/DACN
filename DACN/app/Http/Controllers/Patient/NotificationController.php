<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        $query = auth()->user()->notifications();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(20);

        $allCount = auth()->user()->notifications()->count();
        $unreadCount = auth()->user()->unreadNotifications()->count();

        return view('patient.notifications', compact('notifications', 'allCount', 'unreadCount'));
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc');
    }

    public function markRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'Đã đánh dấu thông báo là đã đọc');
    }

    public function delete($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Đã xóa thông báo');
    }

    public function fetchNewNotifications(Request $request)
    {
        $lastId = $request->input('last_id', 0);
        $newNotifications = auth()->user()->notifications()
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'desc')
            ->get();

        $html = '';
        if ($newNotifications->count() > 0) {
            foreach ($newNotifications as $notification) {
                // Determine icon and color based on type
                $icon = 'bell';
                $bgClass = 'bg-primary';
                if (str_contains($notification->type, 'Appointment')) {
                    $icon = 'calendar-check';
                    $bgClass = 'bg-info';
                } elseif (str_contains($notification->type, 'Payment')) {
                    $icon = 'file-invoice-dollar';
                    $bgClass = 'bg-success';
                } elseif (str_contains($notification->type, 'Medical')) {
                    $icon = 'notes-medical';
                    $bgClass = 'bg-warning';
                }

                $isRead = $notification->read_at !== null;
                $html .= '<div class="notification-item ' . ($isRead ? '' : 'unread') . '" data-id="' . $notification->id . '">
                    <div class="notification-icon">
                        <i class="fas fa-' . $icon . ' text-' . str_replace('bg-', '', $bgClass) . '"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">
                            ' . ($notification->data['title'] ?? 'Thông báo hệ thống') . '
                            ' . (!$isRead ? '<span class="badge badge-danger">Mới</span>' : '') . '
                        </div>
                        <div class="notification-message">
                            ' . ($notification->data['message'] ?? '') . '
                        </div>
                        <div class="notification-time">
                            <i class="far fa-clock"></i> Vừa xong
                        </div>
                    </div>
                    <div class="notification-actions">
                        ' . (isset($notification->data['action_url']) ? '<a href="' . $notification->data['action_url'] . '" class="btn btn-sm btn-primary">Xem</a>' : '') . '
                        ' . (!$isRead ? '<button class="btn btn-sm btn-success mark-read-btn" data-id="' . $notification->id . '">Đã đọc</button>' : '') . '
                    </div>
                </div>';
            }
        }

        return response()->json([
            'html' => $html,
            'last_id' => $newNotifications->first()->id ?? $lastId,
            'count_unread' => auth()->user()->unreadNotifications()->count()
        ]);
    }
}
