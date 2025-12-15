@extends('layouts.patient-modern')

@section('title', 'Thông báo hệ thống')
@section('page-title', 'Thông báo')
@section('page-subtitle', 'Cập nhật tin tức, lịch hẹn và trạng thái hồ sơ')

@section('content')
    <div class="container-fluid p-0">
        <div class="row g-4">
            {{-- 1. Filter Section: Thiết kế lại dạng thanh công cụ --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="nav nav-pills custom-pills">
                                <a href="{{ route('patient.notifications') }}"
                                    class="nav-link {{ !request('filter') ? 'active bg-primary text-white shadow-sm' : 'text-muted bg-light' }} me-2 mb-1">
                                    <i class="fas fa-inbox me-1"></i> Tất cả
                                    <span class="badge bg-white text-primary ms-1 rounded-pill">{{ $allCount }}</span>
                                </a>
                                <a href="{{ route('patient.notifications', ['filter' => 'unread']) }}"
                                    class="nav-link {{ request('filter') === 'unread' ? 'active bg-primary text-white shadow-sm' : 'text-muted bg-light' }} me-2 mb-1">
                                    <i class="fas fa-envelope-open-text me-1"></i> Chưa đọc
                                    <span class="badge bg-danger ms-1 rounded-pill">{{ $unreadCount }}</span>
                                </a>
                                <a href="{{ route('patient.notifications', ['filter' => 'read']) }}"
                                    class="nav-link {{ request('filter') === 'read' ? 'active bg-primary text-white shadow-sm' : 'text-muted bg-light' }} mb-1">
                                    <i class="fas fa-check-circle me-1"></i> Đã đọc
                                </a>
                            </div>

                            @if ($unreadCount > 0)
                                <form action="{{ route('patient.notifications.mark-all-read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill hover-scale">
                                        <i class="fas fa-check-double me-1"></i> Đánh dấu tất cả đã đọc
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Danh sách thông báo --}}
            <div class="col-12">
                {{-- Container có ID để JS dễ tìm --}}
                <div id="notification-list" class="notification-list">
                    @forelse($notifications as $notification)
                        <div class="card mb-3 border-0 shadow-sm notification-card notification-item {{ $notification->read_at ? 'read-bg' : 'unread-border' }}"
                            data-id="{{ $notification->id }}">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start">
                                    {{-- Icon --}}
                                    <div class="flex-shrink-0 me-3">
                                        @php
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
                                        @endphp
                                        <div class="icon-square {{ $bgClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $bgClass) }} rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            <i class="fas fa-{{ $icon }} fs-4"></i>
                                        </div>
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6
                                                class="mb-1 fw-bold {{ $notification->read_at ? 'text-secondary' : 'text-dark' }}">
                                                {{ $notification->data['title'] ?? 'Thông báo hệ thống' }}
                                                @if (!$notification->read_at)
                                                    <span class="badge bg-danger badge-dot ms-1">Mới</span>
                                                @endif
                                            </h6>
                                            <small class="text-muted d-flex align-items-center">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $notification->created_at?->diffForHumans() }}
                                            </small>
                                        </div>

                                        <p class="mb-2 text-muted small user-select-none" style="line-height: 1.5;">
                                            {{ $notification->data['message'] ?? '' }}
                                        </p>

                                        <div class="d-flex gap-2 mt-2">
                                            @if (isset($notification->data['action_url']))
                                                <a href="{{ $notification->data['action_url'] }}"
                                                    class="btn btn-sm btn-light text-primary rounded-pill px-3">
                                                    Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            @endif

                                            @if (!$notification->read_at)
                                                <form
                                                    action="{{ route('patient.notifications.mark-read', $notification) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm btn-link text-decoration-none text-success p-0 ms-2"
                                                        title="Đánh dấu đã đọc">
                                                        <i class="fas fa-check"></i> Đã đọc
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('patient.notifications.delete', $notification) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-link text-decoration-none text-danger p-0 ms-3"
                                                    onclick="return confirm('Xóa thông báo này?')" title="Xóa thông báo">
                                                    <i class="fas fa-trash-alt"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- Empty State --}}
                        <div class="col-12 empty-state">
                            <div class="card border-0 shadow-sm text-center py-5 rounded-3">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076478.png"
                                            alt="No Notification" width="100" class="opacity-50">
                                    </div>
                                    <h5 class="text-muted fw-bold">Chưa có thông báo nào</h5>
                                    <p class="text-muted mb-0">Bạn sẽ nhận được thông báo khi có lịch hẹn hoặc kết quả khám
                                        mới.</p>
                                    <a href="{{ route('home') }}" class="btn btn-primary mt-3 rounded-pill px-4">
                                        Về trang chủ
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($notifications->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Style --}}
    <style>
        .notification-card {
            transition: all 0.2s ease-in-out;
            border-left: 4px solid transparent;
        }

        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
        }

        .unread-border {
            border-left-color: #0d6efd !important;
            background-color: #ffffff;
        }

        .read-bg {
            background-color: #f8f9fa;
            opacity: 0.85;
        }

        .badge-dot {
            font-size: 0.65em;
            vertical-align: text-top;
        }

        .custom-pills .nav-link {
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .hover-scale {
            transition: transform 0.2s;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>

    {{-- Realtime Polling Script (200ms) --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                // 1. Khởi tạo lastId
                let lastId = 0;
                $('.notification-item').each(function() {
                    let id = $(this).data('id');
                    if (id > lastId) lastId = id;
                });

                // 2. Polling siêu tốc 200ms
                setInterval(function() {
                    // Route của Patient
                    let url = "{{ route('patient.notifications.fetch-new') }}";

                    $.ajax({
                        url: url,
                        method: "GET",
                        data: {
                            last_id: lastId
                        },
                        success: function(response) {
                            if (response.html && response.html.trim() !== "") {
                                // Cập nhật lastId
                                lastId = response.last_id;

                                // Xóa empty state nếu có
                                $('.empty-state').remove();

                                // Tìm container
                                let container = $('#notification-list');

                                // Hiệu ứng mượt: Thêm lên đầu -> Ẩn -> Hiện dần
                                $(response.html).hide().prependTo(container).fadeIn(300);

                                // Cập nhật Badge số lượng (nếu có trên header)
                                if (response.count_unread !== undefined) {
                                    // Cập nhật badge đỏ trên icon chuông (nếu có class .badge-notification-count)
                                    $('.badge-notification-count').text(response.count_unread);
                                    // Cập nhật badge trong filter tab (chưa đọc)
                                    $('.nav-link .badge.bg-danger').text(response.count_unread);
                                }
                            }
                        }
                    });
                }, 200); // 200ms - Nhanh như chat
            });
        </script>
    @endpush
@endsection
