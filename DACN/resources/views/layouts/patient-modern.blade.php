<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Phòng khám HealthCare')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Design System --}}
    <link rel="stylesheet" href="{{ asset('css/patient-design-system.css') }}">

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('meta')
    @stack('styles')

    <!-- ADDED: Unified design system -->
    <link rel="stylesheet" href="{{ asset('css/design-system-unified.css') }}">

    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
            --secondary-color: #3b82f6;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --border-color: #e5e7eb;
            --bg-light: #f9fafb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            border: none;
        }

        .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            background: linear-gradient(180deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
            height: 100vh;
            width: 280px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 40;
            transition: all 0.3s;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .sidebar-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            text-decoration: none;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        .top-nav {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 30;
            margin-bottom: 2rem;
            border-radius: 1rem;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div style="width: 48px; height: 48px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-hospital-alt" style="font-size: 1.5rem; color: #10b981;"></i>
                </div>
                <div>
                    <h1 class="text-white mb-0" style="font-size: 1.25rem; font-weight: 700;">HealthCare</h1>
                    <p class="mb-0" style="color: #d1fae5; font-size: 0.75rem;">Phòng khám đa khoa</p>
                </div>
            </div>

            {{-- User Info --}}
            <div style="background: rgba(255, 255, 255, 0.1); border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 48px; height: 48px; background: white; border-radius: 50%; overflow: hidden;">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: #10b981; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-white mb-0" style="font-weight: 600; font-size: 0.875rem;">{{ auth()->user()->name }}</p>
                        <p class="mb-0" style="color: #d1fae5; font-size: 0.75rem;">Bệnh nhân</p>
                    </div>
                </div>
            </div>

            {{-- Navigation Menu --}}
            <nav>
                <a href="{{ route('patient.dashboard.health') }}" class="sidebar-link {{ request()->routeIs('patient.dashboard.health') ? 'active' : '' }}">
                    <i class="fas fa-th-large" style="width: 20px;"></i>
                    <span>Tổng quan</span>
                </a>

                <a href="{{ route('lichhen.my') }}" class="sidebar-link {{ request()->routeIs('lichhen.my') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check" style="width: 20px;"></i>
                    <span>Lịch hẹn khám</span>
                    @php
                        $pendingCount = auth()->user()->lichHens()->where('trang_thai', 'cho_xac_nhan')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ms-auto" style="background: #ef4444; color: white; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 9999px;">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('patient.benhan.index') }}" class="sidebar-link {{ request()->routeIs('patient.benhan.*') ? 'active' : '' }}">
                    <i class="fas fa-file-medical" style="width: 20px;"></i>
                    <span>Hồ sơ bệnh án</span>
                </a>

                <a href="{{ route('patient.donthuoc.index') }}" class="sidebar-link {{ request()->routeIs('patient.donthuoc.*') ? 'active' : '' }}">
                    <i class="fas fa-prescription" style="width: 20px;"></i>
                    <span>Đơn thuốc</span>
                </a>

                <a href="{{ route('patient.xetnghiem.index') }}" class="sidebar-link {{ request()->routeIs('patient.xetnghiem.*') ? 'active' : '' }}">
                    <i class="fas fa-flask" style="width: 20px;"></i>
                    <span>Kết quả xét nghiệm</span>
                </a>

                <a href="{{ route('patient.hoadon.index') }}" class="sidebar-link {{ request()->routeIs('patient.hoadon.*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar" style="width: 20px;"></i>
                    <span>Hóa đơn</span>
                </a>

                <div style="border-top: 1px solid rgba(255, 255, 255, 0.2); margin: 1rem 0;"></div>

                <a href="{{ route('patient.shop.index') }}" class="sidebar-link {{ request()->routeIs('patient.shop.index') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart" style="width: 20px;"></i>
                    <span>Cửa hàng thuốc</span>
                </a>

                <a href="{{ route('patient.shop.orders') }}" class="sidebar-link {{ request()->routeIs('patient.shop.orders') ? 'active' : '' }}">
                    <i class="fas fa-box" style="width: 20px;"></i>
                    <span>Đơn hàng của tôi</span>
                </a>

                <a href="{{ route('patient.coupons.index') }}" class="sidebar-link {{ request()->routeIs('patient.coupons.*') ? 'active' : '' }}">
                    <i class="fas fa-ticket-alt" style="width: 20px;"></i>
                    <span>Mã giảm giá</span>
                </a>

                <div style="border-top: 1px solid rgba(255, 255, 255, 0.2); margin: 1rem 0;"></div>

                <a href="{{ route('public.bacsi.index') }}" class="sidebar-link {{ request()->routeIs('public.bacsi.*') ? 'active' : '' }}">
                    <i class="fas fa-user-md" style="width: 20px;"></i>
                    <span>Danh sách bác sĩ</span>
                </a>

                <a href="{{ route('patient.chat.index') }}" class="sidebar-link {{ request()->routeIs('patient.chat.*') ? 'active' : '' }}">
                    <i class="fas fa-comments" style="width: 20px;"></i>
                    <span>Tư vấn online</span>
                </a>

                <a href="{{ route('patient.danhgia.index') }}" class="sidebar-link {{ request()->routeIs('patient.danhgia.*') ? 'active' : '' }}">
                    <i class="fas fa-star" style="width: 20px;"></i>
                    <span>Đánh giá của tôi</span>
                </a>

                <a href="{{ route('patient.lich-su-kham') }}" class="sidebar-link {{ request()->routeIs('patient.lich-su-kham') ? 'active' : '' }}">
                    <i class="fas fa-history" style="width: 20px;"></i>
                    <span>Lịch sử khám</span>
                </a>

                <div style="border-top: 1px solid rgba(255, 255, 255, 0.2); margin: 1rem 0;"></div>

                <a href="{{ route('patient.family.index') }}" class="sidebar-link {{ request()->routeIs('patient.family.*') ? 'active' : '' }}">
                    <i class="fas fa-users" style="width: 20px;"></i>
                    <span>Thành viên gia đình</span>
                </a>

                <a href="{{ route('patient.notifications') }}" class="sidebar-link {{ request()->routeIs('patient.notifications') ? 'active' : '' }}">
                    <i class="fas fa-bell" style="width: 20px;"></i>
                    <span>Thông báo</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="ms-auto" style="background: #ef4444; color: white; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 9999px;">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>

                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-cog" style="width: 20px;"></i>
                    <span>Cài đặt</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="sidebar-link w-100 text-start border-0" style="background: transparent;">
                        <i class="fas fa-sign-out-alt" style="width: 20px;"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Top Navigation --}}
        <div class="top-nav d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center gap-4">
                <button id="mobile-menu-btn" class="btn d-lg-none">
                    <i class="fas fa-bars fs-5"></i>
                </button>
                <div>
                    <h2 class="mb-0" style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">@yield('page-title', 'Dashboard')</h2>
                    <p class="mb-0" style="font-size: 0.875rem; color: #6b7280;">@yield('page-subtitle', 'Chào mừng bạn trở lại')</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                {{-- Search --}}
                <div class="position-relative d-none d-md-block">
                    <input type="text" placeholder="Tìm kiếm..." class="form-control" style="padding-left: 2.5rem; border-radius: 0.5rem;">
                    <i class="fas fa-search position-absolute" style="left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                </div>

                {{-- Notifications --}}
                <div class="position-relative">
                    <a href="{{ route('patient.notifications') }}" class="btn p-2" style="color: #6b7280;">
                        <i class="fas fa-bell fs-5"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 end-0" style="width: 20px; height: 20px; background: #ef4444; color: white; font-size: 0.75rem; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>
                </div>

                {{-- User Menu --}}
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 40px; height: 40px; background: #10b981; border-radius: 50%; overflow: hidden;">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: #10b981; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Page Content --}}
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4" style="border-left: 4px solid #10b981;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-left: 4px solid #ef4444;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle text-danger me-3"></i>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-left: 4px solid #ef4444;">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-exclamation-triangle text-danger me-3"></i>
                        <p class="mb-0 fw-semibold">Có lỗi xảy ra:</p>
                    </div>
                    <ul class="mb-0 ms-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="mt-5 py-4 border-top">
            <div class="text-center text-muted">
                <p class="mb-1">&copy; {{ date('Y') }} HealthCare Clinic. All rights reserved.</p>
                <p class="mb-0" style="font-size: 0.875rem;">
                    <a href="#" class="text-success text-decoration-none">Điều khoản sử dụng</a> |
                    <a href="#" class="text-success text-decoration-none">Chính sách bảo mật</a> |
                    <a href="#" class="text-success text-decoration-none">Liên hệ hỗ trợ</a>
                </p>
            </div>
        </footer>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('mobile-open');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.getElementById('mobile-menu-btn');

            if (!sidebar?.contains(event.target) && !menuBtn?.contains(event.target)) {
                sidebar?.classList.remove('mobile-open');
            }
        });
    </script>

    <script>
        // Ensure the active sidebar item is visible (scroll into view)
        function scrollActiveSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;
            const active = sidebar.querySelector('.sidebar-link.active');
            if (active) {
                try {
                    // center the active item inside the sidebar
                    active.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } catch (e) {
                    // fallback: instant
                    active.scrollIntoView(false);
                }
            }
        }

        // On page load, scroll active into view
        document.addEventListener('DOMContentLoaded', function() {
            scrollActiveSidebar();

            // When clicking a sidebar link, after navigation (or immediately for SPA) ensure it's visible
            document.querySelectorAll('#sidebar .sidebar-link').forEach(function(el) {
                el.addEventListener('click', function() {
                    // small timeout to allow route highlighting on SPA; safe for full reload too
                    setTimeout(scrollActiveSidebar, 150);
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
