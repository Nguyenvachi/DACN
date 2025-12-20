<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    // ADDED: notifications.id thường là UUID (string) => không thể where('id','>',...)
    private function isUuidLike($value): bool
    {
        return is_string($value) && (bool) preg_match(
            '/^[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$/',
            $value
        );
    }

    // ADDED: lấy mốc created_at từ last_id (UUID) để polling ổn định
    private function resolveCursorCreatedAtFromLastId($lastId): ?Carbon
    {
        if (!$this->isUuidLike($lastId)) {
            return null;
        }

        $last = auth()->user()
            ->notifications()
            ->where('id', $lastId)
            ->first();

        if (!$last || !$last->created_at) {
            return null;
        }

        return Carbon::parse($last->created_at);
    }

    public function index(Request $request)
    {
        $query = auth()->user()->notifications();

        if ($request->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($request->filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(10);
        return view('doctor.notifications.index', compact('notifications'));
    }

    public function markRead(DatabaseNotification $notification)
    {
        // ADDED: chặn user đánh dấu đã đọc notification không thuộc về mình
        if ((int) $notification->notifiable_id !== (int) auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();
        return back();
    }

    public function fetchNewNotifications(Request $request)
    {
        $lastId = $request->input('last_id', 0);

        // Lấy thông báo mới hơn lastId
        $newNotifications = auth()->user()->notifications()
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'desc')
            ->get();

        // ADDED: hỗ trợ cursor theo thời gian để không phụ thuộc so sánh UUID id
        $cursorCreatedAtInput = $request->input('last_created_at');
        $cursorCreatedAt = null;

        if (!empty($cursorCreatedAtInput)) {
            try {
                $cursorCreatedAt = Carbon::parse($cursorCreatedAtInput);
            } catch (\Exception $e) {
                $cursorCreatedAt = null;
            }
        }

        if (!$cursorCreatedAt) {
            $cursorCreatedAt = $this->resolveCursorCreatedAtFromLastId($lastId);
        }

        if ($cursorCreatedAt) {
            $newNotifications = auth()->user()->notifications()
                ->where('created_at', '>', $cursorCreatedAt)
                ->orderBy('created_at', 'desc')
                ->get();
        }

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
                $readClass = $isRead ? 'read-bg' : 'unread-border';
                $titleColor = $isRead ? 'text-secondary' : 'text-dark';

                // Routes & Tokens
                $markReadRoute = route('doctor.notifications.mark-read', $notification->id);
                $csrfToken = csrf_token();

                // Tạo chuỗi HTML chuẩn (Clean HTML String)
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

                                <p class="mb-2 text-muted small notification-message" style="line-height: 1.5;">
                                    '.nl2br(e($notification->data['message'] ?? '')).'
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }

        // ADDED: trả thêm last_created_at để frontend có thể polling ổn định theo thời gian
        $firstNew = $newNotifications->first();
        $lastCreatedAtOut = $firstNew && $firstNew->created_at
            ? Carbon::parse($firstNew->created_at)->toIso8601String()
            : $cursorCreatedAtInput;

        return response()->json([
            'html' => $html,
            'last_id' => $newNotifications->first()->id ?? $lastId, // Lấy ID của item mới nhất (vì sort desc nên là first)
            'last_created_at' => $lastCreatedAtOut,
            'count_unread' => auth()->user()->unreadNotifications()->count()
        ]);
    }
}
