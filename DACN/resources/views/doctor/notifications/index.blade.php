@extends('layouts.doctor')

@section('title', 'Thông báo hệ thống')
@section('page-title', 'Thông báo')
@section('page-subtitle', 'Cập nhật tin tức, lịch hẹn và trạng thái hồ sơ')

@section('content')
    <div class="container-fluid p-0">
        {{-- Header Section --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="fw-bold text-dark mb-1">Thông báo</h3>
                <p class="text-muted mb-0">Theo dõi các cập nhật quan trọng từ hệ thống</p>
            </div>
        </div>

        <div class="row g-4">
            {{-- 1. Filter Tabs (Style hiện đại đồng bộ với Patient) --}}
            <div class="col-12">
                <div class="bg-white p-3 rounded-3 shadow-sm d-flex align-items-center flex-wrap gap-2">
                    <a href="{{ route('doctor.notifications.index') }}"
                        class="btn rounded-pill px-3 fw-bold {{ !request('filter') ? 'btn-primary text-white' : 'btn-light text-muted' }}">
                        <i class="fas fa-inbox me-1"></i> Tất cả
                    </a>
                    <a href="{{ route('doctor.notifications.index', ['filter' => 'unread']) }}"
                        class="btn rounded-pill px-3 fw-bold {{ request('filter') === 'unread' ? 'btn-primary text-white' : 'btn-light text-muted' }}">
                        <i class="fas fa-envelope-open-text me-1"></i> Chưa đọc
                    </a>
                    <a href="{{ route('doctor.notifications.index', ['filter' => 'read']) }}"
                        class="btn rounded-pill px-3 fw-bold {{ request('filter') === 'read' ? 'btn-primary text-white' : 'btn-light text-muted' }}">
                        <i class="fas fa-check-circle me-1"></i> Đã đọc
                    </a>
                </div>
            </div>

            {{-- 2. Danh sách thông báo --}}
            <div class="col-12">
                <div id="notification-list" class="notification-list">
                    @forelse($notifications as $notification)
                        {{-- Logic kiểm tra link --}}
                        @php
                            $hasLink = isset($notification->data['action_url']);
                            $link = $hasLink ? $notification->data['action_url'] : '#';
                        @endphp

                        <div class="card mb-3 border-0 shadow-sm rounded-3 notification-item position-relative {{ $notification->read_at ? 'bg-light opacity-75' : 'bg-white' }}"
                            data-id="{{ $notification->id }}">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    {{-- Icon Tròn --}}
                                    <div class="flex-shrink-0 me-3">
                                        @php
                                            // Màu sắc icon dựa trên loại thông báo
                                            $icon = 'bell';
                                            $bgInfo = 'background-color: #e3f2fd; color: #0d6efd;'; // Default Blue

                                            if (str_contains($notification->type, 'Appointment')) {
                                                $icon = 'calendar-check';
                                                $bgInfo = 'background-color: #e0f7fa; color: #00bcd4;'; // Cyan
                                            } elseif (str_contains($notification->type, 'Payment')) {
                                                $icon = 'file-invoice-dollar';
                                                $bgInfo = 'background-color: #e8f5e9; color: #4caf50;'; // Green
                                            } elseif (str_contains($notification->type, 'Medical')) {
                                                $icon = 'notes-medical';
                                                $bgInfo = 'background-color: #fff8e1; color: #ffc107;'; // Yellow
                                            }
                                        @endphp
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px; {{ $bgInfo }}">
                                            <i class="fas fa-{{ $icon }} fs-5"></i>
                                        </div>
                                    </div>

                                    {{-- Nội dung --}}
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            {{-- Tiêu đề + Stretched Link --}}
                                            <h6 class="fw-bold text-dark mb-0">
                                                @if ($hasLink)
                                                    <a href="{{ $link }}"
                                                        class="text-decoration-none text-dark stretched-link">
                                                        {{ $notification->data['title'] ?? 'Thông báo hệ thống' }}
                                                    </a>
                                                @else
                                                    {{ $notification->data['title'] ?? 'Thông báo hệ thống' }}
                                                @endif

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

                                        {{-- Message (Fix lỗi tràn chữ với text-break) --}}
                                        <p class="text-secondary mb-2 small text-break notification-message" style="line-height: 1.5;">
                                            {!! nl2br(e($notification->data['message'] ?? '')) !!}
                                        </p>

                                        {{-- Actions Buttons --}}
                                        <div class="d-flex align-items-center gap-3 position-relative" style="z-index: 2;">
                                            @if (!$notification->read_at)
                                                <form action="{{ route('doctor.notifications.mark-read', $notification) }}"
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
                                            <i class="fas fa-user-md fs-1 text-muted opacity-50"></i>
                                        </div>
                                    </div>
                                    <h5 class="text-dark fw-bold">Chưa có thông báo nào</h5>
                                    <p class="text-muted mb-0">Bạn sẽ nhận được thông báo khi có lịch hẹn mới.</p>
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

    {{-- Style Tùy chỉnh (Đồng bộ) --}}
    <style>
        .font-sm {
            font-size: 0.85rem;
        }

        /* Fix lỗi tràn chữ bắt buộc xuống dòng */
        .text-break {
            word-wrap: break-word !important;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* Giữ nguyên khoảng trắng và xuống dòng trong message */
        .notification-message {
            white-space: pre-wrap;
        }

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

    {{-- Realtime Script --}}
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

                // Polling 1 giây
                setInterval(function() {
                    if (isRequesting) return;
                    isRequesting = true;

                    $.ajax({
                        url: "{{ route('doctor.notifications.fetch-new') }}",
                        method: "GET",
                        data: {
                            last_id: lastId
                        },
                        success: function(response) {
                            if (response.html && response.html.trim() !== "") {
                                let newItems = $(response.html);
                                // Đảo ngược mảng để đảm bảo tin mới nhất lên đầu khi prepend
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

                                // Update Badge Sidebar
                                if (response.count_unread !== undefined) {
                                    $('.badge-notification-count').text(response.count_unread);
                                    let $sidebarBadge = $('.sidebar-badge');
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
