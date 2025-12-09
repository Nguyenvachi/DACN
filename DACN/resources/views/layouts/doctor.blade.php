<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bác sĩ - {{ config('app.name') }}</title>

    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- Google Fonts Inter (VietCare Standard) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- VietCare + Doctor Layout CSS --}}
    <link rel="stylesheet" href="{{ asset('css/vietcare.css') }}">
    <link rel="stylesheet" href="{{ asset('css/doctor-layout.css') }}">

    @stack('meta')
    @stack('styles')

    <!-- ADDED: Unified design system -->
    <link rel="stylesheet" href="{{ asset('css/design-system-unified.css') }}">
</head>

<body>

    {{-- SIDEBAR --}}
    <nav class="doctor-sidebar">
        {{-- Sidebar Header --}}
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-heartbeat"></i>
            </div>
            <div>
                <div class="sidebar-brand">VietCare</div>
                <small style="opacity: 0.9; font-size: 0.8rem;">Bác Sĩ</small>
            </div>
        </div>

        <ul>
            {{-- 1. DASHBOARD --}}
            <li>
                <a href="{{ route('doctor.dashboard') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Tổng quan</span>
                </a>
            </li>

            {{-- 2. QUẢN LÝ LỊCH HẸN --}}
            <li>
                <a href="{{ route('doctor.lichhen.pending') }}">
                    <i class="fas fa-clock"></i>
                    <span>Lịch chờ xác nhận</span>
                    @php
                        $pendingCount = auth()->user()->bacSi
                            ? \App\Models\LichHen::where('bac_si_id', auth()->user()->bacSi->id)
                                ->where('trang_thai', \App\Models\LichHen::STATUS_PENDING_VN)
                                ->count()
                            : 0;
                    @endphp
                    @if($pendingCount > 0)
                        <span class="sidebar-badge sidebar-badge-pulse">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            {{-- 3. HÀNG ĐỢI KHÁM - Quy trình chính --}}
            <li>
                <a href="{{ route('doctor.queue.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Hàng đợi khám</span>
                    @php
                        $queueCount = auth()->user()->bacSi
                            ? \App\Models\LichHen::where('bac_si_id', auth()->user()->bacSi->id)
                                ->whereIn('trang_thai', [\App\Models\LichHen::STATUS_CHECKED_IN_VN, \App\Models\LichHen::STATUS_IN_PROGRESS_VN])
                                ->whereDate('ngay_hen', today())
                                ->count()
                            : 0;
                    @endphp
                    @if($queueCount > 0)
                        <span class="sidebar-badge">{{ $queueCount }}</span>
                    @endif
                </a>
            </li>

            {{-- 4. BỆNH ÁN - Hồ sơ y khoa --}}
            <li>
                <a href="{{ route('doctor.benhan.index') }}">
                    <i class="fas fa-folder-open"></i>
                    <span>Bệnh án</span>
                    @php
                        $todayRecordsCount = auth()->user()->bacSi
                            ? \App\Models\BenhAn::where('bac_si_id', auth()->user()->bacSi->id)
                                ->whereDate('created_at', today())
                                ->count()
                            : 0;
                    @endphp
                    @if($todayRecordsCount > 0)
                        <span class="sidebar-badge">{{ $todayRecordsCount }}</span>
                    @endif
                </a>
            </li>

            {{-- 5. XÉT NGHIỆM - Quản lý kết quả --}}
            <li>
                <a href="{{ route('doctor.xetnghiem.index') }}">
                    <i class="fas fa-flask"></i>
                    <span>Xét nghiệm</span>
                </a>
            </li>

            {{-- 6. LỊCH LÀM VIỆC --}}
            <li>
                <a href="{{ route('doctor.calendar.index') }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Lịch làm việc</span>
                </a>
            </li>

            {{-- 7. TIN NHẮN --}}
            <li>
                <a href="{{ route('doctor.chat.index') }}">
                    <i class="fas fa-comments"></i>
                    <span>Tin nhắn</span>
                    @php
                        $unreadMessages = 0; // TODO: implement unread count
                    @endphp
                    @if($unreadMessages > 0)
                        <span class="sidebar-badge">{{ $unreadMessages }}</span>
                    @endif
                </a>
            </li>

            <hr style="opacity: 0.1; margin: 1rem 0;">

            {{-- HỒ SƠ CÁ NHÂN --}}
            <li>
                <a href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-cog"></i>
                    <span>Hồ sơ</span>
                </a>
            </li>

            {{-- TRANG CHỦ --}}
            <li>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                    <span>Trang chủ</span>
                </a>
            </li>

            {{-- ĐĂNG XUẤT --}}
            <li>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- HEADER (Optional) --}}
    @if (View::hasSection('header'))
        <header class="bg-white shadow-sm mb-3" style="margin-left: 260px;">
            <div class="container-fluid py-3 px-4">
                @yield('header')
            </div>
        </header>
    @endif

    {{-- MAIN CONTENT --}}
    <main class="doctor-main">
        <div class="container-fluid">
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    {{-- Active menu highlighting --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.doctor-sidebar');
            if (!sidebar) return;

            const links = sidebar.querySelectorAll('a[href]');
            const normalize = href => {
                const a = document.createElement('a');
                a.href = href;
                return a.pathname.replace(/\/$/, '');
            };
            const current = normalize(window.location.href);

            links.forEach(link => {
                const path = normalize(link.href);
                if (current === path || (current.startsWith(path + '/') && path !== '/')) {
                    link.classList.add('active');
                }
            });

            // Mobile sidebar toggle (optional)
            const toggleBtn = document.getElementById('sidebarToggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    {{-- DataTables Scripts Stack (removed duplicate scripts stack to avoid double-binding events) --}}

</body>

</html>
