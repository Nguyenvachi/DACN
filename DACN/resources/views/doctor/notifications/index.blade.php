@extends('layouts.doctor')

@section('title', 'Thông báo hệ thống')
@section('page-title', 'Thông báo')
@section('page-subtitle', 'Cập nhật tin tức, lịch hẹn và trạng thái hồ sơ')

@section('content')
    <div class="container-fluid p-0">
        <div class="row g-4">
            {{-- 1. Filter Section --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="nav nav-pills custom-pills">
                                <a href="{{ route('doctor.notifications.index') }}"
                                    class="nav-link {{ !request('filter') ? 'active bg-primary text-white shadow-sm' : 'text-muted bg-light' }} me-2 mb-1">
                                    <i class="fas fa-inbox me-1"></i> Tất cả
                                </a>
                                <a href="{{ route('doctor.notifications.index', ['filter' => 'unread']) }}"
                                    class="nav-link {{ request('filter') === 'unread' ? 'active bg-primary text-white shadow-sm' : 'text-muted bg-light' }} me-2 mb-1">
                                    <i class="fas fa-envelope-open-text me-1"></i> Chưa đọc
                                </a>
                                <a href="{{ route('doctor.notifications.index', ['filter' => 'read']) }}"
                                    class="nav-link {{ request('filter') === 'read' ? 'active bg-primary text-white shadow-sm' : 'text-muted bg-light' }} mb-1">
                                    <i class="fas fa-check-circle me-1"></i> Đã đọc
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Danh sách thông báo --}}
            <div class="col-12">
                <div class="notification-list">
                    @forelse($notifications as $notification)
                    <div
                        class="card mb-3 border-0 shadow-sm notification-card {{ $notification->read_at ? 'read-bg' : 'unread-border' }}">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
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
                                        }
                                    @endphp
                                    <div class="icon-square {{ $bgClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $bgClass) }} rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <i class="fas fa-{{ $icon }} fs-4"></i>
                                    </div>
                                </div>

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
                                            <form action="{{ route('doctor.notifications.mark-read', $notification) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-link text-decoration-none text-success p-0 ms-2"
                                                    title="Đánh dấu đã đọc">
                                                    <i class="fas fa-check"></i> Đã đọc
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm text-center py-5 rounded-3">
                            <div class="card-body">
                                <div class="mb-3">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076478.png" alt="No Notification"
                                        width="100" class="opacity-50">
                                </div>
                                <h5 class="text-muted fw-bold">Chưa có thông báo nào</h5>
                                <p class="text-muted mb-0">Bạn sẽ nhận được thông báo khi có lịch hẹn hoặc kết quả khám mới.</p>
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
    </style>

    {{-- Realtime polling script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let lastUnreadCount = 0;
            let lastNotificationsHtml = '';

            // Function to update unread count in sidebar
            function updateSidebarBadge(count) {
                const sidebarBadge = document.querySelector('.doctor-sidebar .sidebar-badge');
                if (sidebarBadge) {
                    if (count > 0) {
                        sidebarBadge.textContent = count;
                        sidebarBadge.style.display = 'inline-block';
                    } else {
                        sidebarBadge.style.display = 'none';
                    }
                }
            }

            // Function to refresh notifications list
            async function refreshNotifications() {
                try {
                    const currentFilter = new URLSearchParams(window.location.search).get('filter') || '';
                    const response = await fetch(window.location.pathname + (currentFilter ? '?filter=' + currentFilter : ''), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        credentials: 'same-origin'
                    });

                    if (response.ok) {
                        const html = await response.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newNotificationsHtml = doc.querySelector('.col-12 .row.g-4 .col-12:last-child')?.innerHTML || '';

                        // Only update if content changed
                        if (newNotificationsHtml && newNotificationsHtml !== lastNotificationsHtml) {
                            const notificationsContainer = document.querySelector('.col-12 .row.g-4 .col-12:last-child');
                            if (notificationsContainer) {
                                notificationsContainer.innerHTML = newNotificationsHtml;
                                lastNotificationsHtml = newNotificationsHtml;
                            }
                        }
                    }
                } catch (error) {
                    console.log('Error refreshing notifications:', error);
                }
            }

            // Function to fetch unread count
            async function fetchUnreadCount() {
                try {
                    const response = await fetch('/notifications/unread-count', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        credentials: 'same-origin'
                    });

                    if (response.ok) {
                        const data = await response.json();
                        const currentCount = data.count;

                        // Update sidebar badge if count changed
                        if (currentCount !== lastUnreadCount) {
                            updateSidebarBadge(currentCount);
                            lastUnreadCount = currentCount;
                            // Refresh notifications list when count changes
                            refreshNotifications();
                        }
                    }
                } catch (error) {
                    console.log('Error fetching unread count:', error);
                }
            }

            // Initial setup
            fetchUnreadCount();
            refreshNotifications().then(() => {
                lastNotificationsHtml = document.querySelector('.col-12 .row.g-4 .col-12:last-child')?.innerHTML || '';
            });

            // Poll every 200ms for ultra-fast realtime
            setInterval(fetchUnreadCount, 200);
        });
    </script>
@push('scripts')
<script>
    $(document).ready(function() {
        let lastNotificationId = {{ $notifications->max('id') ?? 0 }};
        let lastUnreadCount = 0;
        let isLoadingNotifications = false;

        // Function to update sidebar badge
        function updateSidebarBadge(count) {
            const badge = document.querySelector('.sidebar-badge, .badge.bg-danger');
            if (badge) badge.textContent = count;
        }

        // Function to append notification to list
        function appendNotification(notification) {
            // Check if notification already exists
            if ($('.notification-card[data-notification-id="' + notification.id + '"]').length > 0) {
                return; // Already exists, skip
            }

            // Determine icon and color based on type
            let icon = 'bell';
            let bgClass = 'bg-primary';
            if (notification.type.includes('Appointment')) {
                icon = 'calendar-check';
                bgClass = 'bg-info';
            } else if (notification.type.includes('Payment')) {
                icon = 'file-invoice-dollar';
                bgClass = 'bg-success';
            } else if (notification.type.includes('Medical')) {
                icon = 'notes-medical';
                bgClass = 'bg-warning';
            }

            const isRead = notification.read_at !== null;
            const notificationHtml = `
                <div class="card mb-3 border-0 shadow-sm notification-card ${isRead ? 'read-bg' : 'unread-border'}" data-notification-id="${notification.id}">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="icon-square ${bgClass} bg-opacity-10 text-${bgClass.replace('bg-', '')} rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-${icon} fs-4"></i>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="mb-1 fw-bold ${isRead ? 'text-secondary' : 'text-dark'}">
                                        ${notification.data.title || 'Thông báo hệ thống'}
                                        ${!isRead ? '<span class="badge bg-danger badge-dot ms-1">Mới</span>' : ''}
                                    </h6>
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="far fa-clock me-1"></i>
                                        vừa xong
                                    </small>
                                </div>

                                <p class="mb-2 text-muted small user-select-none" style="line-height: 1.5;">
                                    ${notification.data.message || ''}
                                </p>

                                <div class="d-flex gap-2 mt-2">
                                    ${notification.data.action_url ? `<a href="${notification.data.action_url}" class="btn btn-sm btn-light text-primary rounded-pill px-3">Xem chi tiết <i class="fas fa-arrow-right ms-1"></i></a>` : ''}
                                    ${!isRead ? `<form action="{{ route('doctor.notifications.mark-read', ':id') }}".replace(':id', notification.id) method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-link text-decoration-none text-success p-0 ms-2" title="Đánh dấu đã đọc">
                                            <i class="fas fa-check"></i> Đã đọc
                                        </button>
                                    </form>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Insert at the top of the notifications list
            const notificationsContainer = document.querySelector('.col-12');
            if (notificationsContainer) {
                const firstCard = notificationsContainer.querySelector('.notification-card, .card');
                if (firstCard) {
                    firstCard.insertAdjacentHTML('beforebegin', notificationHtml);
                } else {
                    notificationsContainer.insertAdjacentHTML('afterbegin', notificationHtml);
                }
            }
        }

        // Function to load new notifications
        function loadNewNotifications() {
            if (isLoadingNotifications) return;

            isLoadingNotifications = true;

            $.ajax({
                url: '{{ route("doctor.notifications.fetch-new") }}',
                method: 'GET',
                data: { last_id: lastNotificationId },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.html && data.html.trim() !== '') {
                        // Prepend new notifications with fadeIn effect (mượt like Chat)
                        $(data.html).hide().prependTo('.notification-list').fadeIn('slow');

                        // Update lastNotificationId to prevent duplicates
                        if (data.last_id > lastNotificationId) {
                            lastNotificationId = data.last_id;
                        }

                        // Update unread count in sidebar
                        updateSidebarBadge(data.count_unread);
                    }

                    isLoadingNotifications = false;
                },
                error: function(xhr, status, error) {
                    console.log('Error loading new notifications:', error);
                    isLoadingNotifications = false;
                }
            });
        }

        // Function to fetch unread count
        function fetchUnreadCount() {
            $.ajax({
                url: '/notifications/unread-count',
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    const currentCount = data.count;
                    if (currentCount !== lastUnreadCount) {
                        updateSidebarBadge(currentCount);
                        lastUnreadCount = currentCount;
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error fetching unread count:', error);
                }
            });
        }

        // Initialize
        fetchUnreadCount();

        // Poll for new notifications every 500ms (same as chat)
        setInterval(loadNewNotifications, 500);
    });
</script>
@endpush
@endsection
