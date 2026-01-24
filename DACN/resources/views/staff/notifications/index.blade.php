@extends('layouts.staff')

@section('title', 'Thông báo hệ thống')
@section('page-title', 'Thông báo')
@section('page-subtitle', 'Cập nhật tin tức và trạng thái hệ thống')

@section('content')
    <div class="container-fluid p-0">
        {{-- Header Section --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="fw-bold text-dark mb-1">Thông báo</h3>
                <p class="text-muted mb-0">Quản lý và cập nhật thông tin nội bộ</p>
            </div>
        </div>

        <div class="row g-4">
            {{-- 1. Filter Section --}}
            <div class="col-12">
                <div class="bg-white p-3 rounded-3 shadow-sm d-flex align-items-center flex-wrap gap-2">
                    <a href="{{ route('staff.notifications.index') }}"
                        class="btn rounded-pill px-3 fw-bold {{ !request('filter') ? 'btn-primary text-white' : 'btn-light text-muted' }}">
                        <i class="fas fa-inbox me-1"></i> Tất cả
                    </a>
                    <a href="{{ route('staff.notifications.index', ['filter' => 'unread']) }}"
                        class="btn rounded-pill px-3 fw-bold {{ request('filter') === 'unread' ? 'btn-primary text-white' : 'btn-light text-muted' }}">
                        <i class="fas fa-envelope-open-text me-1"></i> Chưa đọc
                    </a>
                    <a href="{{ route('staff.notifications.index', ['filter' => 'read']) }}"
                        class="btn rounded-pill px-3 fw-bold {{ request('filter') === 'read' ? 'btn-primary text-white' : 'btn-light text-muted' }}">
                        <i class="fas fa-check-circle me-1"></i> Đã đọc
                    </a>
                </div>
            </div>

            {{-- 2. Danh sách thông báo --}}
            <div class="col-12">
                <div id="notification-list" class="notification-list">
                    @forelse($notifications as $notification)
                        {{-- Item thông báo --}}
                        <div class="card mb-3 border-0 shadow-sm rounded-3 notification-item {{ $notification->read_at ? 'bg-light opacity-75' : 'bg-white' }}"
                            data-id="{{ $notification->id }}">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    {{-- Icon Tròn --}}
                                    <div class="flex-shrink-0 me-3">
                                        @php
                                            $icon = 'bell';
                                            $bgInfo = 'background-color: #e3f2fd; color: #0d6efd;';

                                            if (str_contains($notification->type, 'Appointment')) {
                                                $icon = 'calendar-check';
                                                $bgInfo = 'background-color: #e0f7fa; color: #00bcd4;';
                                            } elseif (str_contains($notification->type, 'Payment')) {
                                                $icon = 'file-invoice-dollar';
                                                $bgInfo = 'background-color: #e8f5e9; color: #4caf50;';
                                            } elseif (str_contains($notification->type, 'Medical')) {
                                                $icon = 'notes-medical';
                                                $bgInfo = 'background-color: #fff8e1; color: #ffc107;';
                                            }
                                        @endphp
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px; {{ $bgInfo }}">
                                            <i class="fas fa-{{ $icon }} fs-5"></i>
                                        </div>
                                    </div>

                                    {{-- Nội dung hiển thị trực tiếp --}}
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            {{-- Tiêu đề (Không còn Link - Chỉ là Text) --}}
                                            <h6 class="fw-bold text-dark mb-0">
                                                {{ $notification->data['title'] ?? 'Thông báo hệ thống' }}

                                                @if (!$notification->read_at)
                                                    <span class="badge bg-danger rounded-pill ms-2"
                                                        style="font-size: 0.7rem; vertical-align: middle;">Mới</span>
                                                @endif
                                            </h6>

                                            {{-- Thời gian --}}
                                            <small class="text-muted text-nowrap ms-2">
                                                <i
                                                    class="far fa-clock me-1"></i>{{ $notification->created_at?->diffForHumans() }}
                                            </small>
                                        </div>

                                        {{-- Nội dung tin nhắn đầy đủ (Text-break xử lý tràn chữ) --}}
                                        <p class="text-secondary mb-2 small text-break notification-message" style="line-height: 1.5;">
                                            {!! nl2br(e($notification->data['message'] ?? '')) !!}
                                        </p>

                                        {{-- Actions --}}
                                        <div class="d-flex align-items-center gap-3">
                                            {{-- 1. Nếu có Link cụ thể (Backend gửi kèm) thì mới hiện nút Xem chi tiết --}}
                                            @if (isset($notification->data['action_url']))
                                                <a href="{{ $notification->data['action_url'] }}"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            @endif

                                            {{-- 2. Nút Đánh dấu đã đọc --}}
                                            @if (!$notification->read_at)
                                                <form action="{{ route('staff.notifications.mark-read', $notification) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-link p-0 text-decoration-none text-success font-sm fw-bold">
                                                        <i class="fas fa-check me-1"></i> Đã đọc
                                                    </button>
                                                </form>
                                            @endif
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
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 80px; height: 80px;">
                                            <i class="fas fa-clipboard-list fs-1 text-muted opacity-50"></i>
                                        </div>
                                    </div>
                                    <h5 class="text-dark fw-bold">Chưa có thông báo nào</h5>
                                    <p class="text-muted mb-0">Bạn sẽ nhận được thông báo khi có công việc mới.</p>
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
        .font-sm {
            font-size: 0.85rem;
        }

        .text-break {
            word-wrap: break-word !important;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* Giữ nguyên khoảng trắng và xuống dòng trong message */
        .notification-message {
            white-space: pre-wrap;
        }

        /* Hiệu ứng hover nhẹ */
        .notification-item {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .notification-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1) !important;
        }

        .btn-primary {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }
    </style>

    {{-- Script Realtime (Giữ nguyên logic chuẩn) --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                let lastId = 0;
                let isRequesting = false;

                function updateLastId() {
                    $('.notification-item').each(function() {
                        let id = $(this).data('id');
                        if (id > lastId) lastId = id;
                    });
                }
                updateLastId();

                setInterval(function() {
                    if (isRequesting) return;
                    isRequesting = true;

                    $.ajax({
                        url: "{{ route('staff.notifications.fetch-new') }}",
                        method: "GET",
                        data: {
                            last_id: lastId
                        },
                        success: function(response) {
                            if (response.html && response.html.trim() !== "") {
                                let newItems = $(response.html);
                                let itemsArray = newItems.get().reverse();

                                $.each(itemsArray, function(index, element) {
                                    let $element = $(element);
                                    let newItemId = $element.data('id');

                                    if ($('#notification-list .notification-item[data-id="' +
                                            newItemId + '"]').length === 0) {
                                        $('.empty-state').remove();
                                        $element.hide().prependTo('#notification-list')
                                            .slideDown('fast');
                                        if (newItemId > lastId) lastId = newItemId;
                                    }
                                });

                                if (response.count_unread !== undefined) {
                                    $('.badge-notification-count').text(response.count_unread);
                                    let $sidebarBadge = $('.staff-sidebar .badge');
                                    $sidebarBadge.text(response.count_unread);
                                    if (response.count_unread > 0) $sidebarBadge.show();
                                    else $sidebarBadge.hide();
                                }
                            }
                        },
                        complete: function() {
                            isRequesting = false;
                        }
                    });
                }, 1000);
            });
        </script>
    @endpush
@endsection
