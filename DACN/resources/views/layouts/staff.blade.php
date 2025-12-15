<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nhân viên - {{ config('app.name') }}</title>

    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- Vite: đồng bộ toàn hệ thống --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <!-- ADDED: Unified design system -->
    <link rel="stylesheet" href="{{ asset('css/design-system-unified.css') }}">

    <style>
        /* ENHANCED: Modern VietCare Design System for Staff (Parent: layouts/staff.blade.php) */
        :root {
            --vietcare-green: #10b981;
            --vietcare-green-dark: #059669;
            --primary-purple: #667eea;
            --primary-blue: #3b82f6;
            --warning-orange: #f59e0b;
            --sidebar-width: 260px;
        }

        body {
            background: #f5f6fa;
            font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Sidebar with VietCare styling */
        .staff-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%);
            border-right: 1px solid #e5e7eb;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        }

        .staff-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .staff-sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--vietcare-green) 0%, var(--vietcare-green-dark) 100%);
            border-radius: 10px;
        }

        /* Brand Header */
        .sidebar-brand {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--vietcare-green) 0%, var(--vietcare-green-dark) 100%);
            color: white;
            text-align: center;
            border-bottom: 3px solid var(--vietcare-green-dark);
        }

        .sidebar-brand h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .sidebar-brand small {
            opacity: 0.9;
            font-size: 0.85rem;
        }

        /* Menu Groups */
        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-group-title {
            padding: 0.75rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.5rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
        }

        .menu-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .menu-item:hover {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.1) 0%, transparent 100%);
            color: var(--vietcare-green);
            transform: translateX(5px);
        }

        .menu-item:hover i {
            transform: scale(1.15);
        }

        .menu-item.active {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%);
            color: var(--vietcare-green);
            font-weight: 600;
            border-left: 4px solid var(--vietcare-green);
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            right: 1.5rem;
            width: 8px;
            height: 8px;
            background: var(--vietcare-green);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--vietcare-green);
        }

        /* Main Content */
        .staff-main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .staff-topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--vietcare-green) 0%, var(--vietcare-green-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
        }

        /* Content Area */
        .content-wrapper {
            flex: 1;
            padding: 2rem;
        }

        /* Footer */
        .staff-footer {
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 1.5rem 2rem;
            text-align: center;
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .staff-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1000;
            }

            .staff-sidebar.show {
                transform: translateX(0);
            }

            .staff-main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block !important;
            }
        }

        .mobile-toggle {
            display: none;
            background: var(--vietcare-green);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
    </style>
</head>

<body>

    {{-- ENHANCED: Modern Sidebar Layout (Parent: layouts/staff.blade.php) --}}
    {{-- SIDEBAR --}}
    <aside class="staff-sidebar" id="staffSidebar">
        {{-- Brand --}}
        <div class="sidebar-brand">
            <i class="fas fa-hospital fs-3 mb-2"></i>
            <h4>VietCare</h4>
            <small>Hệ thống Nhân viên</small>
        </div>

        {{-- Menu --}}
        <nav class="sidebar-menu">
            {{-- Dashboard Section --}}
            <div class="menu-group-title">
                <i class="bi bi-grid-fill me-1"></i> Dashboard
            </div>
            <a href="{{ route('staff.dashboard') }}"
               class="menu-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Tổng quan</span>
            </a>

            {{-- Reception Section --}}
            <div class="menu-group-title mt-3">
                <i class="bi bi-person-badge-fill me-1"></i> Tiếp tân
            </div>
            <a href="{{ route('staff.checkin.index') }}"
               class="menu-item {{ request()->routeIs('staff.checkin.*') ? 'active' : '' }}">
                <i class="bi bi-person-check-fill"></i>
                <span>Check-in Bệnh nhân</span>
            </a>
            <a href="{{ route('staff.queue.index') }}"
               class="menu-item {{ request()->routeIs('staff.queue.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Quản lý Hàng đợi</span>
            </a>

            {{-- Schedule Section --}}
            <div class="menu-group-title mt-3">
                <i class="bi bi-calendar-week-fill me-1"></i> Lịch làm việc
            </div>
            <a href="{{ route('staff.dashboard') }}#lich"
               class="menu-item">
                <i class="bi bi-calendar3"></i>
                <span>Lịch của tôi</span>
            </a>

            {{-- Profile Section --}}
            <div class="menu-group-title mt-3">
                <i class="bi bi-person-fill me-1"></i> Cá nhân
            </div>
            <a href="{{ route('profile.edit') }}"
               class="menu-item">
                <i class="bi bi-person-gear"></i>
                <span>Hồ sơ của tôi</span>
            </a>
            <a href="{{ route('staff.notifications.index') }}"
               class="menu-item {{ request()->routeIs('staff.notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell-fill"></i>
                <span>Thông báo</span>
                @php
                    $unreadNotifications = auth()->user()->unreadNotifications()->count();
                @endphp
                @if($unreadNotifications > 0)
                    <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadNotifications }}</span>
                @endif
            </a>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="staff-main-content">
        {{-- TOP BAR --}}
        <header class="staff-topbar">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle me-3" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
            </div>

            <div class="topbar-user">
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none d-flex align-items-center gap-2"
                            type="button"
                            data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-start d-none d-md-block">
                            <div class="fw-semibold text-dark">{{ auth()->user()->name }}</div>
                            <small class="text-muted">Nhân viên</small>
                        </div>
                        <i class="bi bi-chevron-down text-muted"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-gear me-2"></i>Hồ sơ của tôi
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="content-wrapper">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="staff-footer">
            <p class="mb-0">&copy; {{ date('Y') }} VietCare Healthcare System. All rights reserved.</p>
        </footer>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            document.getElementById('staffSidebar').classList.toggle('show');
        }

        // Close sidebar on outside click (mobile)
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('staffSidebar');
            const toggle = document.querySelector('.mobile-toggle');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(event.target) &&
                !toggle.contains(event.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Realtime notifications polling
        let lastUnreadCount = 0;
        function updateSidebarBadge(count) {
            const sidebarBadge = document.querySelector('.staff-sidebar .badge');
            if (sidebarBadge) {
                if (count > 0) {
                    sidebarBadge.textContent = count;
                    sidebarBadge.style.display = 'inline-block';
                } else {
                    sidebarBadge.style.display = 'none';
                }
            }
        }

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

                    if (currentCount !== lastUnreadCount) {
                        updateSidebarBadge(currentCount);
                        lastUnreadCount = currentCount;
                    }
                }
            } catch (error) {
                console.log('Error fetching unread count:', error);
            }
        }

        // Initial fetch and poll every 200ms for ultra-fast realtime
        fetchUnreadCount();
        setInterval(fetchUnreadCount, 200);
    </script>

</body>

</html>
