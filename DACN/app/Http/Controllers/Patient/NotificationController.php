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

        // Lấy thông báo mới hơn lastId
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
                } elseif (str_contains($notification->type, 'Order')) {
                    $icon = 'pills';
                    $bgClass = 'bg-danger';
                }

                $isRead = $notification->read_at !== null;
                $readClass = $isRead ? 'read-bg' : 'unread-border';
                $titleColor = $isRead ? 'text-secondary' : 'text-dark';

                // Routes & Tokens
                $markReadRoute = route('patient.notifications.mark-read', $notification->id);
                $deleteRoute = route('patient.notifications.delete', $notification->id);
                $csrfToken = csrf_token();

                // Tạo chuỗi HTML chuẩn (Khớp với View Patient Modern)
                $html .= '
                <div class="card mb-3 border-0 shadow-sm notification-card notification-item '.$readClass.'" data-id="'.$notification->id.'">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="icon-square '.$bgClass.' bg-opacity-10 text-'.str_replace('bg-', '', $bgClass).' rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-'.$icon.' fs-4"></i>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="mb-1 fw-bold '.$titleColor.'">
                                        '.($notification->data['title'] ?? 'Thông báo hệ thống').'
                                        '.(!$isRead ? '<span class="badge bg-danger badge-dot ms-1">Mới</span>' : '').'
                                    </h6>
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="far fa-clock me-1"></i>
                                        Vừa xong
                                    </small>
                                </div>

                                <p class="mb-2 text-muted small user-select-none" style="line-height: 1.5;">
                                    '.($notification->data['message'] ?? '').'
                                </p>

                                <div class="d-flex gap-2 mt-2">
                                    '.(isset($notification->data['action_url']) ?
                                    '<a href="'.$notification->data['action_url'].'" class="btn btn-sm btn-light text-primary rounded-pill px-3">
                                        Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                                    </a>' : '').'

                                    '.(!$isRead ? '
                                    <form action="'.$markReadRoute.'" method="POST" style="display: inline;">
                                        <input type="hidden" name="_token" value="'.$csrfToken.'">
                                        <button type="submit" class="btn btn-sm btn-link text-decoration-none text-success p-0 ms-2" title="Đánh dấu đã đọc">
                                            <i class="fas fa-check"></i> Đã đọc
                                        </button>
                                    </form>' : '').'

                                    <form action="'.$deleteRoute.'" method="POST" style="display: inline;">
                                        <input type="hidden" name="_token" value="'.$csrfToken.'">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-link text-decoration-none text-danger p-0 ms-3"
                                            onclick="return confirm(\'Xóa thông báo này?\')" title="Xóa thông báo">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }

        return response()->json([
            'html' => $html,
            'last_id' => $newNotifications->first()->id ?? $lastId, // Lấy ID của item mới nhất
            'count_unread' => auth()->user()->unreadNotifications()->count()
        ]);
    }
}
