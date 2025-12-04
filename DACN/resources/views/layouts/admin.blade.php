<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản trị Phòng khám</title>

    {{-- Bootstrap 5 + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    @stack('meta')
    @stack('styles')

    <style>
        html {
            scroll-behavior: auto;
        }

        body {
            background: #f5f6fa;
            font-family: "Segoe UI", sans-serif;
        }

        .admin-sidebar {
            width: 250px;
            height: 100vh;
            background: #ffffff;
            border-right: 1px solid #ddd;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        /* Scroll đẹp */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 5px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: #999;
        }

        .admin-sidebar ul {
            list-style: none;
            padding: 0;
        }

        .admin-sidebar li {
            margin-bottom: 10px;
        }

        .admin-sidebar a {
            display: block;
            padding: 10px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .admin-sidebar a:hover {
            background: #e9ecef;
        }

        .admin-sidebar a.active {
            background: #007bff;
            color: white;
        }

        main {
            margin-left: 270px;
            padding: 20px;
        }
    </style>
</head>

<body>

    <nav class="admin-sidebar">
        <h5 class="fw-bold mb-3"><i class="fas fa-clinic-medical me-2"></i>Quản trị</h5>

        <ul>
            <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i>Dashboard</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-link p-0" type="submit">🔒 Đăng xuất</button>
                </form>
            </li>
            <hr>

            <li><strong>Quản lý cơ bản</strong></li>
            <li><a href="{{ route('admin.bac-si.index') }}">👨‍⚕️ Quản lý Bác sĩ</a></li>
            <li><a href="{{ route('admin.nhanvien.index') }}">👥 Nhân viên</a></li>
            <li><a href="{{ route('admin.dich-vu.index') }}">🏥 Dịch vụ</a></li>
            <li><a href="{{ route('admin.chuyenkhoa.index') }}">🔬 Chuyên khoa</a></li>
            <li><a href="{{ route('admin.phong.index') }}">🚪 Phòng khám</a></li>
            <li><a href="{{ route('admin.phong.diagram') }}">🗺️ Sơ đồ phòng</a></li>

            <hr>

            <li><strong>Lịch & Hẹn</strong></li>
            <li><a href="{{ route('admin.lichhen.index') }}">📅 Lịch hẹn</a></li>
            <li><a href="{{ route('admin.calendar.index') }}">📆 Calendar</a></li>

            <hr>

            <li><strong>Bệnh án & Hóa đơn</strong></li>
            <li><a href="{{ route('admin.benhan.index') }}">📋 Bệnh án</a></li>
            <li><a href="{{ route('admin.hoadon.index') }}">💰 Hóa đơn</a></li>

            <hr>

            <li><strong>Quản lý kho</strong></li>
            <li><a href="{{ route('admin.thuoc.index') }}">💊 Thuốc</a></li>
            <li><a href="{{ route('admin.kho.index') }}">📦 Kho</a></li>
            <li><a href="{{ route('admin.kho.nhap.form') }}">📥 Nhập kho</a></li>
            <li><a href="{{ route('admin.kho.xuat.form') }}">📤 Xuất kho</a></li>
            <li><a href="{{ route('admin.kho.bao_cao') }}">📊 Báo cáo</a></li>
            <li><a href="{{ route('admin.ncc.index') }}">🏢 Nhà cung cấp</a></li>

            <hr>

            <li><strong>CMS</strong></li>
            <li><a href="{{ route('admin.baiviet.index') }}">📝 Bài viết</a></li>
            <li><a href="{{ route('admin.danhmuc.index') }}">📂 Danh mục</a></li>
            <li><a href="{{ route('admin.tag.index') }}">🏷️ Thẻ</a></li>

            <hr>

            <li><strong>Phân quyền</strong></li>
            <li><a href="{{ route('admin.users.index') }}">👤 Users</a></li>
            <li><a href="{{ route('admin.roles.index') }}">🎭 Vai trò</a></li>
            <li><a href="{{ route('admin.permissions.index') }}">🔐 Quyền</a></li>

            <hr>

            <li><strong>Tools</strong></li>
            <li><a href="{{ route('admin.tools.test-mail') }}">✉️ Test gửi mail</a></li>
        </ul>
    </nav>

    <main>

        {{-- Bổ sung render header --}}
        @hasSection('header')
            <div class="mb-4">
                @yield('header')
            </div>
        @endif

        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

    {{-- Active menu --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.admin-sidebar');
            if (!sidebar) return;

            // Khôi phục vị trí scroll từ sessionStorage
            const savedScrollPos = sessionStorage.getItem('adminSidebarScroll');
            if (savedScrollPos !== null) {
                sidebar.scrollTop = parseInt(savedScrollPos);
            }

            // Lưu vị trí scroll khi chuyển trang
            window.addEventListener('beforeunload', function() {
                sessionStorage.setItem('adminSidebarScroll', sidebar.scrollTop);
            });

            const links = sidebar.querySelectorAll('a');

            const normalize = href => {
                const a = document.createElement('a');
                a.href = href;
                return a.pathname.replace(/\/$/, '');
            };

            const current = normalize(window.location.href);

            links.forEach(link => {
                const path = normalize(link.href);
                if (current === path || current.startsWith(path + '/')) {
                    link.classList.add('active');
                }
            });
        });
    </script>

</body>

</html>
